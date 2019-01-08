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
        $query = Thread::query();

        if (request()->has('sort') && request('sort') == 'abc') {
            $query->orderBy('title');
        } else {
            $query->orderBy('id', 'desc');
        }

        if (request()->has('authors')) {
            $authors = explode(',', request('authors'));
            
            if (count($authors)) {
                $query->whereIn('created_by', $authors);
            }
        }

        $threads = $query->paginate(5);

        return request()->wantsJson()
            ? $threads
            : view('threads.index');
    }

    public function store()
    {
        $this->validator(request()->all())->validate();

        $thread = $this->storeOrUpdate(request()->all());

        return request()->wantsJson()
            ? $thread
            // TODO: Redirect to the newly created thread page
            : redirect()->route('profile');
    }

    public function update(Thread $thread)
    {
        $this->authorize('update', $thread);

        $this->validator(request()->all(), $thread)->validate();

        $thread = $this->storeOrUpdate(request()->all(), $thread);

        return request()->wantsJson()
            ? $thread
            // TODO: Redirect to the thread page
            : redirect()->route('profile');
    }

    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);

        $thread->delete();

        return response()->json();
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
        ], [
            'title.not_regex' => 'Title cannot contain numbers.',
            'content.regex' => 'The content must end with a dot.'
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

    public function create()
    {
        return view('threads.edit', [
            'thread' => null,
        ]);
    }

    public function edit(Thread $thread)
    {
        return view('threads.edit', [
            'thread' => $thread,
        ]);
    }
}
