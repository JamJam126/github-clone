<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Repo;
use App\Models\User;
use App\Models\File;
use App\Models\Folder;
use App\Models\Star;
use App\Models\Pin;
use App\Models\Content;
use App\Models\Commit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;

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
        // FETCH THE LIST OF REPOS THAT BELONG TO USER
        $userRepos = Repo::select('name', 'id')
            ->addSelect([
                'user_name' => User::select('name')
                    ->whereColumn('id', 'repos.user_id')
            ])
            ->where('user_id', Auth::id())
            ->limit(5)
            ->orderBy('created_at')
            ->get();

        // FETCH THE LIST OF ALL THE REPOS THAT ARE PUBLIC
        $repos = Repo::select('repos.name', 'repos.id', 'u.name as user_name')
            ->join('users as u', 'u.id', '=', 'repos.user_id')
            ->where('repos.visibility', 'Public')
            ->orderBy('repos.created_at')
            ->limit(10)
            ->get();

        return Inertia::render('Home', [
            'repos' => $userRepos

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:repos,name',
            'description' => 'nullable|string',
            'visibility' => 'required|in:Public,Private',
        ]);
        // FOR UNKNOWN REASON THE REPO THAT CONTAIN SPACE
        // WILL NOT SHOW AND I TOO LAZY TO INVESTIGATE
        // QUICK SOLUTION
        // TODO: HANDLE SPACE IN REPO NAME
        $repo_name = $request->input("name");
        if (str_contains($repo_name, " ")) {
            $repo_name = str_replace(' ', '_', $repo_name);
        }
        $repo = new Repo();
        $repo->name = $repo_name;
        $repo->description = $request->input('description');
        $repo->visibility = strtolower($request->input('visibility'));
        $repo->user_id = Auth::id();
        $repo->save();
        $username = $request->user()->name;

        return redirect(
            "/$username/$repo_name"
        );
    }

    public function repo($user, $repo)
    {
        $info = Repo::select('repos.name', 'repos.id', 'repos.created_at', 'repos.visibility', 'repos.total_stars', 'users.name as user_name')
            ->join('users', 'repos.user_id', '=', 'users.id')
            ->where('users.name', $user)
            ->where('repos.name', $repo)
            ->first();

        $isStarred = Star::where('user_id', Auth::id())->where('repo_id', $info->id)->first();
        $isPinned = Pin::where('user_id', Auth::id())->where('repo_id', $info->id)->first();

        if (!$info) return abort(404, 'Repository not found');
        $file = File::where('repo_id',  $info->id)->whereNull('folder_id')->get();

        $folder = Folder::where('repo_id', $info->id)->whereNull('parent_id')->get();

        return Inertia::render('Repo', [
            'info' => $info,
            'files' => $file,
            'folders' => $folder,
            'star' => $isStarred,
            'pin' => $isPinned,
            'repo_owner' => $user // THIS ONE IS DUP, SHOULD BE REMOVED
        ]);
    }

    public function handleSearch() {}

    public function displayRootFileContent($user, $repoName, $file)
    {

        $repoId = Repo::select('id')->where('name', $repoName)->value('id');

        $fileContent = File::select('content')
            ->where('name', $file)
            ->whereNull('folder_id')
            ->where('repo_id', $repoId)
            ->value('content');

        $decodedContent = base64_decode($fileContent);

        return Inertia::render('DisplayFileContent', [
            'content' => $decodedContent,
            'filename' => $file,
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
            'filename' => $fileName,
            // 'test' => $file,
        ]);
    }

    // This will handle folder and stuff
    public function folderHandler($user, $repoName, $path)
    {
        $checkRepo = Repo::where("name", $repoName)->firstOrFail();

        if ($checkRepo->visibility == "private" && Auth::id() !== $checkRepo->user_id) {
            abort(404);
        }

        // Show the file base on the path that will be list in $path
        $pathArray = explode('/', $path);
        $mainFolders = Folder::where('repo_id', $checkRepo->id)
            ->whereNull('parent_id')
            ->first();

        $parentFolderId = $mainFolders->id;
        $searchFolder = NULL;

        // It does not take null as parent id because if the parent id is Null is it parent
        // so we retrieve the the first parent id
        for ($i = 0; $i < sizeof($pathArray); $i++) {
            $searchFolder =
                Folder::select('id', 'name', 'parent_id')
                ->where('repo_id', $checkRepo->id)
                ->where('parent_id', $parentFolderId)
                ->where('name', $pathArray[$i])
                ->first();

            if ($searchFolder) $parentFolderId = $searchFolder->id;
        }

        $subfolders = Folder::where('parent_id', $parentFolderId)
            ->where('repo_id', $checkRepo->id)
            ->get();
        $files = File::where('folder_id',  $parentFolderId)->get();

        $subfolders = $subfolders->map(function ($folder) {
            $latestFolderCommit = $this->getFolderLastCommit($folder->id);
            $latestFolderCommit->created_at = Carbon::parse($latestFolderCommit->created_at)
                ->diffForHumans();

            $folder->latest_commit = $latestFolderCommit;
            return $folder;
        });

        $files = $files->map(function ($file) {
            $latestCommit = DB::table('commit_files')
                ->join('commits', 'commit_files.commit_id', '=', 'commits.id')
                ->where('commit_files.file_id', $file->id)
                ->orderByDesc('commits.created_at')
                ->select('commits.message', 'commits.created_at')
                ->first();

            $createdAt = Carbon::parse($latestCommit->created_at);
            $latestCommit->created_at = $createdAt->diffForHumans();

            $file->latest_commit = $latestCommit;
            return $file;
        });

        $filesTree = File::select('id', 'name')
            ->where('repo_id', $checkRepo->id)
            ->whereNull('folder_id')
            ->get()
            ->map(function ($file) {
                $file->type = 'file';
                return $file;
            });

        $foldersTree = Folder::select('id', 'name')
            ->where('repo_id', $checkRepo->id)
            ->whereNull('parent_id')
            ->get()
            ->map(function ($folder) {
                $folder->type = 'folder';
                return $folder;
            });

        $filesArray = $filesTree->toArray();
        $foldersArray = $foldersTree->toArray();
        $repoFoldersFilesTree = array_merge($foldersArray, $filesArray);

        $latestFolderCommit = $this->getFolderLastCommit($parentFolderId);
        $latestFolderCommit->created_at = Carbon::parse($latestFolderCommit->created_at)
            ->diffForHumans();

        return Inertia::render('RepoDir', [
            'repo_owner' => $user,
            'repo' => $checkRepo,
            'repo_tree' => $repoFoldersFilesTree,
            'files' => $files,
            'folders' => $subfolders,
            'currFolder' => $path,
            'commit' => $latestFolderCommit,
        ]);
    }


    public function fileHandler($user, $repoName, $path)
    {
        $checkRepo = Repo::select()->where("name", $repoName)->get();
        $checkRepo = $checkRepo[0];
        if ($checkRepo->visibility == "private" && Auth::id() !== $checkRepo->user_id) {
            abort(404);
        }

        // Show the file base on the path that will be list in $path
        $pathArray = explode('/', $path);
        $parentFolderId = null;

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
            ->whereNull('parent_id')
            ->get()
            ->map(function ($folder) {
                $folder->type = 'folder';
                return $folder;
            });


        $filesArray = $files->toArray();
        $foldersArray = $folders->toArray();
        $repoFoldersFilesTree = array_merge($foldersArray, $filesArray);

        foreach ($pathArray as $folderName) {

            $searchFolder = Folder::select('id', 'name', 'parent_id')
                ->where('repo_id', $checkRepo->id)
                ->where('parent_id', $parentFolderId)
                ->where('name', $folderName)
                ->first();

            if ($searchFolder)
                $parentFolderId = $searchFolder->id;
        }

        $fileId = File::select('id')
            ->where('folder_id', $parentFolderId)
            ->where('name', $pathArray[sizeof($pathArray) - 1])
            ->value('id');

        $fileContent = Content::select('content', 'id')
            ->where('file_id', $fileId)
            ->where('is_latest', 1)
            ->first();

        $decodedContent = base64_decode($fileContent->content);

        $commit = DB::table('commit_files')
            ->join('commits', 'commit_files.commit_id', '=', 'commits.id')
            ->join('users', 'commits.user_id', '=', 'users.id')
            ->where('file_id', $fileId)
            ->where('content_id', $fileContent->id)
            ->select('commits.message', 'users.name', 'commits.created_at')
            ->first();
        // dd($commit);

        return Inertia::render('RepoDir', [
            'repo_owner' => $user,
            'repo' => $checkRepo,
            'repo_tree' => $repoFoldersFilesTree,
            'content' => $decodedContent,
            'currFolder' => $path,
            'filename' => $pathArray[sizeof($pathArray) - 1],
            'commit' => $commit,
        ]);
    }

    public function commitHandler($user, $repoName, $path)
    {
        // dd("Hi");
        $checkRepo = Repo::select()->where("name", $repoName)->get();
        $checkRepo = $checkRepo[0];
        if ($checkRepo->visibility == "private" && Auth::id() !== $checkRepo->user_id) {
            abort(404);
        }

        $pathArray = explode('/', $path);
        $parentFolderId = null;
        $searchFolder = null;

        foreach ($pathArray as $folderName) {
            $searchFolder = Folder::select('id', 'name', 'parent_id')
                ->where('repo_id', $checkRepo->id)
                ->where('parent_id', $parentFolderId)
                ->where('name', $folderName)
                ->first();
            if ($searchFolder)
                $parentFolderId = $searchFolder->id;
        }

        $commits = DB::table('commit_files')
            ->join('commits', 'commit_files.commit_id', '=', 'commits.id')
            ->join('files', 'commit_files.file_id', '=', 'files.id')
            ->join('users', 'commits.user_id', '=', 'users.id')
            ->where('files.folder_id', $parentFolderId)
            ->select('commits.id', 'commits.message', 'users.name', 'commits.created_at')
            ->distinct()
            ->get();

        return Inertia::render('Commits', [
            'commits' => $commits,
            'currPath' => $path,
            'repo_owner' => $user,
            'repo' => $checkRepo,
        ]);
    }

    public function commitView($user, $repoName, $commitId, $path)
    {
        $pathArray = explode('/', $path);
        $parentFolderId = null;
        $fileId = null;
        $searchFolder = null;
        $contents = null;
        $decodedContents = [];

        $checkRepo = Repo::select('repos.*', 'repos.id')
            ->join('users', 'users.id', '=', 'repos.user_id')
            ->where("repos.name", $repoName)
            ->where('users.name', $user)
            ->first();

        foreach ($pathArray as $folderName) {
            $searchFolder = Folder::select('id', 'name', 'parent_id')
                ->where('repo_id', $checkRepo->id)
                ->where('parent_id', $parentFolderId)
                ->where('name', $folderName)
                ->first();

            if ($searchFolder) $parentFolderId = $searchFolder->id;

            else {
                $fileId = File::select('id')
                    ->where('repo_id', $checkRepo->id)
                    ->where('folder_id', $parentFolderId)
                    ->where('name', $folderName)
                    ->value('id');
            }
        }

        $commit = Commit::select()
            ->where('id', $commitId)
            ->first();

        if (!$fileId) {
            // FOLDERS
            $contents = Content::join('commit_files as cf', 'cf.content_id', '=', 'contents.id')
                ->join('files as f', 'f.id', '=', 'cf.file_id')
                ->where('cf.commit_id', $commitId)
                ->where('f.folder_id', $parentFolderId)
                ->select('f.file_path', 'contents.content')
                ->get();
            //    ->pluck('contents.content');

            // DECODE THEM
            for ($i = 0; $i < sizeof($contents); $i++) {
                $decodedContents[] = [
                    'content' => base64_decode($contents[$i]->content),
                    'path' => $contents[$i]->file_path,
                ];
            }
        } else {
            // FILE
            $contents = Content::select('contents.content', 'f.file_path')
                ->join('commit_files as cf', 'cf.content_id', '=', 'contents.id')
                ->join('files as f', 'cf.file_id', '=', 'f.id')
                ->where('cf.commit_id', $commitId)
                ->where('cf.file_id', $fileId)
                ->get();

            // DECODE IT
            $decodedContents[] = [
                'content' => base64_decode($contents[0]->content),
                'path' => $contents[0]->file_path,
            ];
        }

        return Inertia::render('CommitHistory', [
            'test' => $decodedContents,
            'commit' => $commit,
        ]);
    }

    public function getChildren($repoName, $folderName, $folderId)
    {
        // $repo_id = Repo::select("id")->where('name', $repoName)->value('id');

        $repo_id = Repo::select("id")->where('name', $repoName)->value('id');
        $files = File::select('id', 'name')
            ->where('folder_id', $folderId)
            ->where('repo_id', $repo_id)
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

    public function handleStar($star, $user, $repoId)
    {
        // $starStatus = $request->input('star');


        $userId = User::select('id')->where('name', $user)->value('id');
        $text = ""; // FOR DEBUGGING
        $starStatus = filter_var($star, FILTER_VALIDATE_BOOLEAN); // THIS WILL CONVERT TO BOOLEAN

        if ($starStatus === true) {
            $star = new Star();
            $star->user_id = $userId;
            $star->repo_id = $repoId;
            $star->save();

            Repo::find($repoId)->increment('total_stars');

            $text = "Starred";
        } else if ($starStatus === false) {
            Star::where('user_id', $userId)
                ->where('repo_id', $repoId)
                ->delete();

            Repo::find($repoId)->decrement('total_stars');
            $text = "Unstarred";
        }

        return response()->json([
            "userId" => $repoId,
            "repoId" => $repoId,
            "starStatus" => $text
        ]);

        // return Inertia::render('ButtonsContainer', [
        //     'star' => $star,
        // ]);
    }

    public function handlePin($pin, $user, $repoId)
    {
        $userId = User::select('id')->where('name', $user)->value('id');
        $text = ""; // FOR DEBUGGING
        $pinStatus = filter_var($pin, FILTER_VALIDATE_BOOLEAN); // THIS WILL CONVERT TO BOOLEAN

        if ($pinStatus === true) {
            $pin = new Pin();
            $pin->user_id = $userId;
            $pin->repo_id = $repoId;
            $pin->save();

            $text = "Unpin";
        } else if ($pinStatus === false) {
            Pin::where('user_id', $userId)
                ->where('repo_id', $repoId)
                ->delete();

            $text = "Pin";
        }

        return response()->json([
            "userId" => $repoId,
            "repoId" => $repoId,
            "pinStatus" => $text
        ]);
    }

    private function getFolderLastCommit($folderId)
    {
        return DB::table('commit_files')
            ->join('commits', 'commits.id', '=', 'commit_files.commit_id')
            ->join('users', 'commits.user_id', '=', 'users.id')
            ->whereIn('commit_files.file_id', function ($query) use ($folderId) {
                $query->select('id')
                    ->from('files')
                    ->where('folder_id', $folderId);
            })
            ->orderByDesc('commits.created_at')
            ->select(
                'commits.id',
                'commits.message',
                'commits.created_at',
                'users.name',
            )
            ->first();
    }
}
