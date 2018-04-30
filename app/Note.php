<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'user_id',
        ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
