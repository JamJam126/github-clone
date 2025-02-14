<?php

namespace App\Http\Controllers;

use App\Models\Repo;
use App\Models\User;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use function Laravel\Prompts\search;

class RepoController extends Controller
{
    //
    public function createRepo()
    {
        return Inertia::render('New');
    }

    public function getRepo()
    {
        $repos = Repo::select('name', 'id')
            ->addSelect([
                'user_name' => User::select('name')
                    ->whereColumn('id', 'repos.user_id')
            ])
            ->limit(5)
            ->orderBy('created_at')
            ->get();

        return Inertia::render('Home', ['repos' => $repos]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:repos,name',
            'description' => 'nullable|string',
            'visibility' => 'required|in:Public,Private',
        ]);

        $repo = new Repo();
        $repo->name = $request->input('name');
        $repo->description = $request->input('description');
        $repo->visibility = $request->input('visibility');
        $repo->user_id = Auth::id();
        $repo->save();

        return response()->json([
            'success' => true,
            'message' => 'Repository created successfully',
            'repository' => $repo
        ], 201);
    }

    public function repo($user, $repo)
    {

        $info = Repo::select('repos.name', 'repos.id', 'repos.created_at', 'repos.visibility', 'users.name as user_name')
            ->join('users', 'repos.user_id', '=', 'users.id')
            ->where('users.name', $user)
            ->where('repos.name', $repo)
            ->first();

        if (!$info) return abort(404, 'Repository not found');
        $file = File::where('repo_id',  $info->id)->whereNull('folder_id')->get();

        $folder = Folder::where('repo_id', $info->id)->whereNull('parent_id')->get();

        return Inertia::render('Repo', [
            'info' => $info,
            'files' => $file,
            'folders' => $folder,
            'repo_owner' => $user
        ]);
    }

    public function subdir($repoName, $folderName)
    {

        $repo = Repo::where('name', $repoName)->first();
        $repo_owner = $repo->user->name;
        dd($repo_owner);
        $files = File::select('id', 'name')
            ->where('repo_id', $repo->id)
            ->get()
            ->map(function ($file) {
                $file->type = 'file';
                return $file;
            });
        $folders = Folder::select('id', 'name')
            ->where('repo_id', $repo->id)
            ->whereNull('parent_id')
            ->get()
            ->map(function ($folder) {
                $folder->type = 'folder';
                return $folder;
            });

        $filesArray = $files->toArray();
        $foldersArray = $folders->toArray();
        $repoFoldersFilesTree = array_merge($foldersArray, $filesArray);
        // dd($repoFoldersFilesTree);

        $folder = Folder::where('name', $folderName)->first();

        $files = File::where('folder_id',  $folder->id)->get();

        $subfolders = Folder::where('parent_id', $folder->id)->get();


        // dd($test->id);

        return Inertia::render('RepoDir', [
            'repo_owner' => $repo_owner,
            'repo' => $repo,
            'repo_tree' => $repoFoldersFilesTree,
            'files' => $files,
            'folders' => $subfolders,
            'currFolder' => $folderName,
        ]);
    }

    public function displayRootFileContent($user, $repoName, $file)
    {
        // $fileNameCheck = File::where('name', $file)->exists();
        // dd($fileNameCheck);

        // $repoIdCheck = File::where('repo_id', $repoId)->exists();
        // dd($repoIdCheck);

        // $folderIdCheck = File::whereNull('folder_id')->exists();
        // dd($folderIdCheck);

        // $repo = Repo::where('name', $repoName)->first();
        $repoId = Repo::select('id')->where('name', $repoName)->value('id');

        $fileContent = File::select('content')
            ->where('name', $file)
            ->whereNull('folder_id')
            ->where('repo_id', $repoId)
            ->value('content');

        $decodedContent = base64_decode($fileContent);

        return Inertia::render('DisplayFileContent', [
            'content' => $decodedContent,
            // 'test' => $file,
        ]);
    }

    public function displayFileContent($repoName, $folderName, $fileName)
    {

        $folderId = Folder::select('id')->where('name', $folderName)->value('id');

        $fileContent = File::select('content')
            ->where('name', $fileName)
            ->where('folder_id', $folderId)
            ->whereNull('repo_id')
            ->value('content');

        $decodedContent = base64_decode($fileContent);
        // dd($decodedContent);

        return Inertia::render('DisplayFileContent', [
            'content' => $decodedContent,
            // 'test' => $file,
        ]);
    }
    // This will handle folder and stuff
    public function folderHandler($user, $repoName, $path)
    {
        $checkRepo = Repo::select()->where("name", $repoName)->get();
        $checkRepo = $checkRepo[0];
        if ($checkRepo->visibility == "private" && Auth::id() !== $checkRepo->user_id) {
            abort(404);
        }
        // Show the file base on the path that will be list in $path
        $pathArray = explode('/', $path);

        $mainFolders = Folder::select('id', 'name')
            ->where('repo_id', $checkRepo->id)
            ->whereNull('parent_id')
            ->first();
        $parentFolderId = $mainFolders->id;
        $searchFolder = NULL;
        $files = File::select('id', 'name')
            ->where('repo_id', $checkRepo->id)
            ->whereNull('folder_id')
            ->get()
            ->map(function ($file) {
                $file->type = 'file';
                return $file;
            });
        $folders = Folder::select('id', 'name')
            ->where('repo_id', $checkRepo->id)
            ->where('parent_id', $parentFolderId)
            ->get()
            ->map(function ($folder) {
                $folder->type = 'folder';
                return $folder;
            });

        $filesArray = $files->toArray();
        $foldersArray = $folders->toArray();
        $repoFoldersFilesTree = array_merge($foldersArray, $filesArray);
        // It does not take null as parent id because if the parent id is Null is it parent
        // so we retrieve the the first parent id
        for ($i = 0; $i < sizeof($pathArray); $i++) {
            $searchFolder =
                Folder::select('id', 'name', 'parent_id')
                ->where('repo_id', $checkRepo->id)
                ->where('parent_id', $parentFolderId)
                ->where('name', $pathArray[$i])
                ->first();
            if ($searchFolder) {
                $parentFolderId = $searchFolder->id;
            }
        }

        // dd($parentFolderId);
        $files = File::where('folder_id',  $parentFolderId)->get();
        // dd($files);
        $subfolders = Folder::where('parent_id', $parentFolderId)->where('repo_id', $checkRepo->id)->get();

        // dd($test->id);

        return Inertia::render('RepoDir', [
            'repo_owner' => $user,
            'repo' => $checkRepo,
            'repo_tree' => $repoFoldersFilesTree,
            'files' => $files,
            'folders' => $subfolders,
            'currFolder' => $path,
        ]);
    }


    public function fileHandler($user, $repoName, $path) {
            $checkRepo = Repo::select()->where("name", $repoName)->get();
            $checkRepo = $checkRepo[0];
            if ($checkRepo->visibility == "private" && Auth::id() !== $checkRepo->user_id) {
                abort(404);
            }
    
            // Show the file base on the path that will be list in $path
            $pathArray = explode('/', $path);
            $parentFolderId = null;
        
            foreach ($pathArray as $folderName) {
    
                $searchFolder = Folder::select('id', 'name', 'parent_id')
                        ->where('repo_id', $checkRepo->id)
                        ->where('parent_id', $parentFolderId)
                        ->where('name', $folderName)
                        ->first();
                
                if ($searchFolder)
                    $parentFolderId = $searchFolder->id;
    
            }
    
            $fileContent = File::select('content')
                                ->where('folder_id', $parentFolderId)
                                ->where('name', $pathArray[sizeof($pathArray) - 1])
                                ->value('content');
    
            $decodedContent = base64_decode($fileContent);
            // dd($decodedContent);
            return Inertia::render('DisplayFileContent', [
                'content' => $decodedContent,
            ]);
    }

    public function getChildren($repoName, $folderName, $folderId)
    {
        // $repo_id = Repo::select("id")->where('name', $repoName)->value('id');

        $repo_id = Repo::select("id")->where('name', $repoName)->value('id');
        $files = File::select('id', 'name')
            ->where('folder_id', $folderId)
            ->whereNull('repo_id')
            ->get()
            ->map(function ($file) {
                $file->type = 'file';
                return $file;
            });

        $folders = Folder::select('id', 'name')
            ->where('parent_id', $folderId)
            ->get()
            ->map(function ($folder) {
                $folder->type = 'folder';
                return $folder;
            });

        $filesArray = $files->toArray();
        $foldersArray = $folders->toArray();
        $repoFoldersFilesTree = array_merge($foldersArray, $filesArray);

        return response()->json([$repoFoldersFilesTree]);
    }
}
