<?php

namespace App\Http\Controllers;

use App\Models\Commit;
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

        $files = $request->input('files');
  
        $responseData = [
            'id' => $request->input('id'),
            'message' => $request->input('message'),
            'files' =>  array_map(function ($file) {
                            // return $file['content'];
                            return base64_encode($file['content']);    
                        }, $files)  
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
        // ])->with('redirect_url', route('repo.show', ['id' => $request->input('id')]));
        // return response()->json([
        //     'data' => $request->all(), 
        //     'user_name' => $user ? $user->name : null,
        // ]);
    }
} 
