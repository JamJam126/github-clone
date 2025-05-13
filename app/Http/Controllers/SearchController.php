<?php

namespace App\Http\Controllers;

use App\Models\Repo;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    //
    public function search(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');

        if ($type === "Repo") {
            $result = Repo::where('name', 'LIKE' ,"%{$query}%")->get();
        }

        else if ($type === "User") {
            $result = User::where('name', 'LIKE', "%{$query}%")->get();    
        }

        else {
            return response()->json([
                'Error' => 'Invalid search type.'
            ], 404);
        }

        return Inertia::render('SearchResult', [
            'result' => $result,
            'query' => $query,
        ]);
        // return response()->json([
        //     'query' => $query,
        //     'type' => $type,
        //     'repos' => $result,
        //     'message' => 'This is a search test.'
        // ]);
    }
}
