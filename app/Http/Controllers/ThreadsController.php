<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Thread;
use Illuminate\Validation\Rule;

class ThreadsController extends Controller
{
    public function index()
    {
        return view('threads.index');
    }

    public function store()
    {
        $this->validator(request()->all())->validate();

        $thread = $this->storeOrUpdate(request()->all());

        return $thread;
    }

    public function update(Thread $thread)
    {
        $this->authorize('update', $thread);

        $this->validator(request()->all(), $thread)->validate();

        $thread = $this->storeOrUpdate(request()->all(), $thread);

        return $thread;
    }

    public function validator($data, $thread = null)
    {
        return Validator::make($data, [
            'title' => [
                'required',
                'min:3',
                Rule::unique('threads')->ignore($thread),
                'not_regex:/^.*\d.*/i'
            ],
            'content' => [
                'nullable',
                'max:255',
                'regex:/^.*?\.$/i',
            ],
        ]);
    }

    public function storeOrUpdate($data, $thread = null)
    {
        // If a user already has 5 threads - delete the oldest one
        $threads = auth()->user()->threads;
        
        if (!$thread && count($threads) == 5) {
            $threads[0]->delete();
        }

        // Create or update the thread
        $data['created_by'] = auth()->id();

        if (!$thread) {
            $thread = Thread::create($data);
        } else {
            $thread->update($data);
        }

        return $thread;
    }
}
