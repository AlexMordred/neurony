<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $with = ['author'];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
