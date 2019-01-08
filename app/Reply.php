<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
        'thread_id',
        'created_by',
        'content',
    ];

    protected $with = ['author'];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
