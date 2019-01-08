<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;

class ThreadRepliesController extends Controller
{
    public function index(Thread $thread)
    {
        return Reply::where('thread_id', $thread->id)
            ->paginate(5);
    }
}
