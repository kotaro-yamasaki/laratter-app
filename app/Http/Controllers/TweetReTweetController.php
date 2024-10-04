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

    /*public function retweet(Tweet $tweet)
    {
        $user = auth()->user();

        if ($user->hasRetweeted($tweet->id)) {
        // リツイート解除
            $user->retweets()->detach($tweet->id);
        } else {
        // リツイート追加
            $user->retweets()->attach($tweet->id);
        }

        return back();
    }*/
    /*public function retweet(Tweet $tweet)
    {
    // 現在ログインしているユーザーを取得
    $user = auth()->user();

    // リツイートがすでに中間テーブルに存在するか確認
    $existingRetweet = DB::table('retweet_user')
        ->where('tweet_id', $tweet->id)
        ->where('user_id', $user->id)
        ->first();

    if ($existingRetweet) {
        // すでにリツイートしている場合はリツイートを解除
        DB::table('retweet_user')
            ->where('tweet_id', $tweet->id)
            ->where('user_id', $user->id)
            ->delete();

        return redirect()->route('tweets.index')->with('success', 'リツイートを解除しました。');
    } else {
        // リツイートしていない場合は中間テーブルにリツイート情報を追加
        DB::table('retweet_user')->insert([
            'tweet_id' => $tweet->id,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 元ツイートのデータを`tweets`テーブルに新しいツイートとして再投稿
        $newTweet = Tweet::create([
            'user_id' => $user->id,          // 現在ログインしているユーザーを設定
            'content' => $tweet->content,    // 元ツイートの内容をコピー
            'original_tweet_id' => $tweet->id, // 元ツイートのIDを保存（リツイート元を記録）
        ]);

        return redirect()->route('tweets.index')->with('success', 'リツイートしました。');
    }
}*/


}
