<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;

class TweetReTweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        /*$tweets = Tweet::with(['user','retweeted'])->latest()->get();
        return view('tweets.index',compact('tweets'));*/
        // コントローラでツイートの取得
        /*$tweets = Tweet::with('retweets')
            ->latest('created_at')
            ->orWhereHas('retweets', function($query) {
                $query->where('user_id', auth()->id());
        })
        ->get();*/

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tweet $tweet)
    {
        //
        //$tweet->retweeted()->attach(auth()->id());
        
        $user = auth()->user();

        // リツイートがすでに存在するかチェック
        if (!$tweet->retweetedBy($user)) {
            $user->retweets()->attach($tweet->id);
        
         // 新しいツイートとしてリツイートを投稿
        $newTweet = $user->tweets()->create([
            'tweet' => $tweet->tweet, // 元のツイート内容
            'original_tweet_id' => $tweet->id, // 元のツイートIDを保存
            'tweet_id'=>$tweet->id,
        ]);

        
        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Tweet $tweet)
    {
        //
        //$tweet->update($request->only('tweet'));

        //return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        //
        //$tweet->retweeted()->detach(auth()->id());
        
        $user = auth()->user();

        // リツイートが存在する場合に削除
        if ($tweet->retweetedBy($user)) {
            $user->retweets()->detach($tweet->id);
        
            $retweet = $user->tweets()->where('original_tweet_id', $tweet->id)->first();
            if ($retweet) {
                $retweet->delete();
            }
        }

        return back();

    }

    
}
