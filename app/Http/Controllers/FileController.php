<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class FileController extends Controller
{
    //
    public function store(Request $request) 
    {
        // return response()->json($request->all());
        return Inertia::render('Repo', ['reponse' => $request]);
    }
}
