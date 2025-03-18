<?php

namespace App\Http\Controllers;

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

        $test = [];

        foreach ($files as $index => $file) {
            # code...
            $bomb = explode('/', $paths[$index]);
            $parent = null;
            $curr = 0;

            if (sizeof($bomb) > 2) {

                while ($curr < count($bomb) -1) {
                    $folder = Folder::where('name', $bomb[$curr])
                                    ->where('repo_id', $repo)
                                    ->where('parent_id', $parent)
                                    ->first();

                    if (!$folder) {

                        $newFolder = new Folder();
                        $newFolder->repo_id = $repo;
                        $newFolder->name = $bomb[$curr];
                        $newFolder->parent_id = $parent;
                        $newFolder->save();

                        $folder = $newFolder;
                    }

                    $parent = $folder->id;
                    $curr++;
                }
            }

            $existingFile = File::select()
                                ->where('name', $bomb[count($bomb) - 1])
                                ->where('file_path', $paths[$index])
                                ->where('repo_id', $repo)
                                ->first();

            if (!$existingFile) {

                $content = file_get_contents($file->getRealPath());
                $base64Content = base64_encode($content);

                $newFile = new File();
                $newFile->name = $file->getClientOriginalName();
                $newFile->content = $base64Content;
                $newFile->file_size = $file->getSize();
                $newFile->file_path = $paths[$index];
                $newFile->repo_id = $repo ;
                $newFile->folder_id = (count($bomb) <= 2) ? null : $parent;

                $newFile->save();
            }
        }

        $responseData = [

            'files' => $test
        ];


        return Inertia::render('Repo', ['info' => $responseData]);

    }
}
