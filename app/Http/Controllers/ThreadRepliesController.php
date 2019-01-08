<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use Illuminate\Support\Facades\Validator;
use App\ThreadCollaborator;
use App\Events\NewReply;

class ThreadRepliesController extends Controller
{
    public function store(Thread $thread)
    {
        $this->validator(request()->all())->validate();

        $reply = $this->storeOrUpdate(request()->all(), $thread);

        return $reply;
    }

    public function validator($data)
    {
        return Validator::make($data, [
            'content' => 'required',
        ]);
    }

    public function storeOrUpdate($data, $thread, $reply = null)
    {
        // Create or update the reply
        $data['created_by'] = auth()->id();
        $data['thread_id'] = $thread->id;

        if (!$reply) {
            $reply = Reply::create($data);

            // Add the current user as a thread collaborator
            $thread->collaborators()->attach(auth()->id());

            // Dispatch an event to notify the thread author
            event(new NewReply($reply));
        } else {
            $reply->update($data);
        }

        return $reply;
    }

    public function index(Thread $thread)
    {
        return Reply::where('thread_id', $thread->id)
            ->paginate(5);
    }
}
