<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class replay extends Model
{
    protected $fillable = ['message_content', 'user_id', 'review_id'];

    public function review()
{
    return $this->belongsTo(Review::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}



}
