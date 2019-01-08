<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Thread;

class AdminController extends Controller
{
    public function index()
    {
        $threads = Thread::orderBy('id', 'DESC')->paginate(5);

        return request()->wantsJson()
            ? $threads
            : view('admin.index');
    }

    public function destroy(Thread $thread)
    {
        $thread->delete();

        return response()->json();
    }
}
