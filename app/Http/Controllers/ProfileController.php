<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $threads = auth()->user()->threads;

        return request()->wantsJson()
            ? $threads
            : view('profile.show');
    }
}
