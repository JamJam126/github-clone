<?php

namespace App\Http\Controllers;

use App\Models\Repo;
use App\Models\User;
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

    public function getRepo() {
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
            'visibility' => 'required|in:public,private',
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

    public function repo($user, $repo) {

        $info = Repo::select('name', 'id', 'created_at')
            ->where('name', $repo)
            ->addSelect([
                'user_id' => User::select('id')
                                ->where('name', $user)
            ])
            ->first();
        return Inertia::render('Repo', ['info' => $info]);
    }
}
