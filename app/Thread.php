<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Thread extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'content',
    ];

    protected $with = ['author'];

    protected $withCount = ['replies'];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function collaborators()
    {
        return $this->belongsToMany(
            User::class,
            'thread_collaborators',
            'thread_id',
            'collaborator_id'
        );
    }
}
