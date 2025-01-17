<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use function Laravel\Prompts\select;

class CommitController extends Controller
{
    //
    public function store(Request $request)
    {
        $id = Auth::id();
        $user = User::select('name')->where('id', $id)->first();

        // return response()->json([
        //     'data' => $request->all(), 
        //     'user_name' => $user ? $user->name : null,
        // ]);
    }
} 
