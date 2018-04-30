<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * Get the user that owns the note.
     */
    public function post()
    {
        return $this->belongsTo('App\User');
    }
}
