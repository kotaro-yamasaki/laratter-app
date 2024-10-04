<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;
    protected $fillable = ['tweet'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function liked()
    {
      return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function comments()
    {
      return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }
    public function retweetedBy(User $user)
    {
      return $this->retweets()->where('user_id', $user->id)->exists();
    }
    /*public function retweetedBy(User $user)
    {
    return $this->retweets->contains($user);
    }*/


    public function retweets()
    {
      return $this->belongsToMany(User::class, 'retweet_user', 'tweet_id', 'user_id')->withTimestamps();
    }

}
