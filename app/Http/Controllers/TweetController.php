<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tweets = Tweet::with(['user', 'liked'])->latest()->get();
        return view('tweets.index', compact('tweets'));
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
         return view('tweets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $request->validate([
        'tweet' => 'required|max:255',
    ]);

    $request->user()->tweets()->create($request->only('tweet'));

    return redirect()->route('tweets.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        //
        $tweet->load('comments');
        return view('tweets.show', compact('tweet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tweet $tweet)
    {
        //
        //dd($tweet);
        return view('tweets.edit', compact('tweet'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        //
         $request->validate([
        'tweet' => 'required|max:255',
    ]);

    $tweet->update($request->only('tweet'));

    return redirect()->route('tweets.show', $tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        //
        //dd($tweet);
        $tweet->delete();

    return redirect()->route('tweets.index');
    }
    
    /**
 * Search for tweets containing the keyword.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\View\View
 */
public function search(Request $request)
{

  $query = Tweet::query();

  // キーワードが指定されている場合のみ検索を実行
  if ($request->filled('keyword')) {
    $keyword = $request->keyword;
    $query->where('tweet', 'like', '%' . $keyword . '%');
  }

  // ページネーションを追加（1ページに10件表示）
  $tweets = $query
    ->latest()
    ->paginate(10);

  return view('tweets.search', compact('tweets'));
}

/*public function retweet(Tweet $tweet)
{
    // すでにリツイートされているか確認
    $existingRetweet = Tweet::where('user_id', auth()->id())
                            ->where('original_tweet_id', $tweet->id)
                            ->first();

    if ($existingRetweet) {
        return back()->with('error', 'すでにリツイートしています。');
    }

    // リツイートを作成
    Tweet::create([
        'user_id' => auth()->id(), // 現在ログイン中のユーザー
        'content' => $tweet->content, // 元のツイートの内容をコピー
        'original_tweet_id' => $tweet->id, // 元のツイートID
    ]);

    return back()->with('success', 'リツイートしました。');
}
public function unretweet(Tweet $tweet)
{
    // ログインユーザーのリツイートを検索して削除
    $retweet = Tweet::where('user_id', auth()->id())
                    ->where('original_tweet_id', $tweet->id)
                    ->first();

    if ($retweet) {
        $retweet->delete();
        return back()->with('success', 'リツイートを解除しました。');
    }

    return back()->with('error', 'リツイートが見つかりません。');
} */


}
