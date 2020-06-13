<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Review extends Model
{
	use Notifiable;

    protected $fillable = ['review'];

    public function user()
	{
		return $this->belongsTo('App\User');
	}
}
