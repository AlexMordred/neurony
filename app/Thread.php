<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'content',
    ];

    protected $with = ['author'];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
