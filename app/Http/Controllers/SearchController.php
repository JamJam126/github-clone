<?php

namespace App\Http\Controllers;

use App\Models\Content;
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

        if ($type === "Repos") {
            $result = Repo::select('repos.*', 'user.name as user')
                          ->join('users as user', 'user.id', '=', 'repos.user_id')
                          ->where('repos.name', 'LIKE' ,"%{$query}%")
                          ->get();
        }

        else if ($type === "Users") {
            $result = User::where('name', 'LIKE', "%{$query}%")->get();    
        }

        else if ($type === "Code") {
            $encodedQuery = base64_encode($query);
            $encodedContent = Content::where('content', 'LIKE', "%{$encodedQuery}%")->get();
        }

        else {
            return response()->json([
                'Error' => 'Invalid search type.'
            ], 404);
        }

        // return response()->json([
        //     'encodedQuery' => $encodedQuery,
        //     'encodedContent' => $encodedContent,
        // ]);

        // dd($result);
        return Inertia::render('SearchResult', [
            'result' => $result,
            'query' => $query,
            'type' => $type,
        ]);

        // return response()->json([
        //     'query' => $query,
        //     'type' => $type,
        //     'repos' => $result,
        //     'message' => 'This is a search test.'
        // ]);
    }
}
