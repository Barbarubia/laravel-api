<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
        'user_id', 'avatar', 'city', 'birthday',
    ];

    // Collegamento One-to-One con tabella users
    public function user() {
        return $this->belongsTo('App\User');
    }

    // Calcolo età dell'utente
    public function age() {
        return Carbon::parse($this->attributes['birthday'])->age;
    }
}
