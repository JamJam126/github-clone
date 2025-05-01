<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Content;
use App\Models\Commit;
use App\Models\Repo;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;

use function Laravel\Prompts\select;

class CommitController extends Controller
{
    //
    private function createContent($fileId, $base64Content, $prevId = null)
    {
        return Content::create([
            'file_id' => $fileId,
            'content' => $base64Content,
            'prev_id' => $prevId,
            'is_lastest' => 1
        ]);
    }

    private function getOrCreateFolderPath($segments, $repoId)
    {
        $parent = null;

        foreach ($segments as $segment) {
            $folder = Folder::where('name', $segment)
                            ->where('repo_id', $repoId)
                            ->where('parent_id', $parent)
                            ->first();

            if (!$folder) {
                $folder = Folder::create([
                    'repo_id' => $repoId,
                    'name' => $segment,
                    'parent_id' => $parent
                ]);
            }

            $parent = $folder->id;
        }

        return $parent;
    }

    private function getEncodedFileContent($file)
    {
        return base64_encode(file_get_contents($file->getRealPath()));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:repos,id',
            'message' => 'required|string',
            'files' => 'required|array',
        ]);

        $repo = $request->input('id');
        $files = $request->file('files');
        $paths = $request->input('relativePath');

        $commitMessage = $request->input('message');
        // dd($commitMessage);
        $commit = null;
        $test = [];

        foreach ($files as $index => $file) {
            $pathParts = explode('/', $paths[$index]);
            $fileName = end($pathParts);
            $folderSegments = array_slice($pathParts, 0, -1);
    
            $parent = count($folderSegments) > 0
                ? $this->getOrCreateFolderPath($folderSegments, $repo)
                : null;
    

            $existingFile = File::where('name', $fileName)
                                ->where('file_path', $paths[$index])
                                ->where('repo_id', $repo)
                                ->first();
    
            $base64Content = $this->getEncodedFileContent($file);
            $hash = hash('sha256', $base64Content);
    
            if ($existingFile) 
            {
                $existingContent = Content::where('file_id', $existingFile->id)
                                          ->where('is_latest', 1)
                                          ->first();
    
                // Hash for comparing because it's faster than comparing base64 string
                if ($existingContent && hash('sha256', $existingContent->content) === $hash) 
                    continue; // Skip if no changes

                if ($existingContent) 
                {
                    $existingContent->is_latest = 0;
                    $existingContent->save();
                }
    
                $newContent = $this->createContent($existingFile->id, $base64Content, $existingContent?->id);
                
                if(!$commit)
                {
                    $commit = Commit::create([
                        'repo_id' => $repo,
                        'user_id' => Auth::id(),
                        'message' => $commitMessage,
                    ]);
                }

                DB::table('commit_files')->insert([
                    'commit_id' => $commit->id,
                    'file_id' => $existingFile->id,
                    'content_id' => $newContent->id,
                ]);
            } 
            else 
            {
                $newFile = File::create([
                    'name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_path' => $paths[$index],
                    'repo_id' => $repo,
                    'folder_id' => $parent,
                ]);
    
                $newContent = $this->createContent($newFile->id, $base64Content);
            
                if (!$commit)
                {
                    $commit = Commit::create([
                        'repo_id' => $repo,
                        'user_id' => Auth::id(),
                        'message' => $commitMessage,
                    ]);
                }

                DB::table('commit_files')->insert([
                    'commit_id' => $commit->id,
                    'file_id' => $newFile->id,
                    'content_id' => $newContent->id,
                ]);
            }
        }

        $responseData = [
            'files' => $test
        ];

        return Inertia::render('Repo', ['info' => $responseData]);

    }
}
