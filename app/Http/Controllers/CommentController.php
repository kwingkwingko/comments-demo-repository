<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = DB::table('comments')
            ->where('header_id', '0')
            ->orderByDesc('id')
            ->get();

        return response($comments, 200);
    }
    
    public function getReplies(Request $request)
    {
        $comments = DB::table('comments')
            ->where('header_id', $request->id)
            ->orderByDesc('id')
            ->get();

        return response($comments, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string',
            'header_id' => 'required|integer'
        ]);

        $comment = auth()->user()
            ->comments()
            ->create($data);

        $comment->load('author');

        return response($comment, 200);
    }
}
