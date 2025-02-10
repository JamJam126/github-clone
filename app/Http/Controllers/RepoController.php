<?php

namespace App\Http\Controllers;

use App\Models\Repo;
use App\Models\User;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

        $file = File::where('repo_id',  $info->id)->get();

        $folder = Folder::where('repo_id', $info->id)->whereNull('parent_id')->get();

        return Inertia::render('Repo', [
            'info' => $info,
            'files' => $file,
            'folders' => $folder,
        ]);
    }

    public function subdir($repoName, $folderName)
    {

        $repo = Repo::where('name', $repoName)->first();

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
}
