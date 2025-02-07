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
        
        // $fileTest = File::where('id', 6)->first();
        // $cont = $fileTest->content;
        // $test = base64_decode($cont);
        // $test = $con->content;
        $test = []; 

        foreach ($files as $index => $file) {
            # code...
            $bomb = explode('/', $paths[$index]);
            $parent = null;
            $curr = 1;

            if (sizeof($bomb) > 2) {
                while ($curr != sizeof($bomb) - 1) {
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
                        ->first();

            if (!$existingFile) {

                $content = file_get_contents($file->getRealPath());
                $base64Content = base64_encode($content);
    
                $newFile = new File();
                $newFile->name = $file->getClientOriginalName();
                $newFile->content = $base64Content;
                $newFile->file_size = $file->getSize();
                $newFile->file_path = $paths[$index];
                $newFile->repo_id = (count($bomb) <= 2) ? $repo : null;
                $newFile->folder_id = (count($bomb) <= 2) ? null : $parent;
                
                $newFile->save();
            }

        }
        

        // foreach ($files as $index => $value) {
        //     # code...
        //     $test[] = $value->getClientOriginalName() . $paths[$index];
        // }

        // TEST CODES of files
        // foreach ($files as $file) {
        //     # code...
        //     $content = file_get_contents($file->getRealPath());
        //     $base64 = base64_encode($content);

        //     $size = $file->getSize();
        //     $type = $file->getType();
        //     $name = $file->getClientOriginalName();
        //     $path = $file->getRealPath();

        //     $real = base64_decode($base64);
        //     $test = $content;

        //     $test[] = $path;
        // }


        // foreach($files as $file)
        // {
        //     $path = $file->getRealPath();
        //     $test[] = $path;
        // }

        
        foreach($paths as $path)
        {
            // $test1 = [];

            $bombs = explode('/', $path);
            // $test1[] = "Parent Folder: " . $bomb[sizeof($bomb) - 2];
            // $test1[] = "File Name: " . $bomb[sizeof($bomb) - 1];

            // $test[] = $test1;

            // if ($bomb[0] == $repoName) $test[] = 1;
            // else $test[] = 0;

            // REMOVE THE FIRST folder OF PATH

            // $newBomb = [];
            // foreach ($bombs as $index => $value) 
            //     if ($index != 0)  $newBomb[] = $value;

            // $newPath = implode('/', $newBomb);

            // $test[] = $newPath;

            // foreach ($bombs as $index => $value) {
            //     # code...
            //     $dummyData = Repo::select('name')
            //                         ->where('id', $index + 1)
            //                         ->first();
                
            //     if($dummyData) $test[] = $dummyData;
            //     else $test[] = 1;
            // }
        }
  
        $responseData = [
        
            'files' => $test  
        ];


        return Inertia::render('Repo', ['info' => $responseData]);

        // $id = Auth::id();
        // $user = User::select('name')->where('id', $id)->first();
        // $info = [
        //     "id" => $id,
        //     "user_name" => $user,
        //     "name" => "",
        //     "test" => 'jrwiherjgh'
        // ];

        // return Inertia::render('CommitModal', [
        //     'repo' => $info
        // ])->with('refolderect_url', route('repo.show', ['id' => $request->input('id')]));
        // return response()->json([
        //     'data' => $request->all(), 
        //     'user_name' => $user ? $user->name : null,
        // ]);
    }
} 
