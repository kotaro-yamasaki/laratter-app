<!-- resources/views/tweets/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Tweet一覧') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          @foreach ($tweets as $tweet)
          <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
            <p class="text-gray-800 dark:text-gray-300">{{ $tweet->tweet }}</p>
            <a href="{{ route('profile.show', $tweet->user) }}">
              <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
            </a>
            <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>

            <p class="text-gray-600 dark:text-gray-400 text-sm">投稿者: {{ $tweet->user->name }}</p>
            <a href="{{ route('tweets.show', $tweet) }}" class="text-blue-500 hover:text-blue-700">詳細を見る</a>
            <div class="flex">
              @if ($tweet->liked->contains(auth()->id()))
              <form action="{{ route('tweets.dislike', $tweet) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:text-red-700">いいねをやめる {{$tweet->liked->count()}}</button>
              </form>
              @else
              <form action="{{ route('tweets.like', $tweet) }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-500 hover:text-blue-700">いいね {{$tweet->liked->count()}}</button>
              </form>
              @endif
            </div>
            
            <div class="flex">
              @if(auth()->user()->retweets->contains($tweet->id))
                  <!-- リツイート済みの場合は取り消しボタン -->
                <form action="{{ route('tweets.unretweet', $tweet) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <!--<label for="tweet" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tweet</label>-->
                  <!--<input type="text" name="tweet" id="tweet" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">-->
                  <button type="submit" class="text-red-500 hover:text-red-700">投稿されました</button>
                  <!--<a href=style.css class="button09">リツイートをやめる</a>-->
                  <!--<button type="button" name="button" class="text-blue-500 hover:text-blue-700">リツイートをやめる</button>-->
                </form>
              @else
                  <!-- リツイートボタン -->
                <form action="{{ route('tweets.retweet', $tweet) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-blue-500 hover:text-blue-700">連投ボタン</button>
                    <!--<input type="text" name="tweet" id="tweet" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline">-->
                    <!--<a href=style.css class="button09">リツイート</a>-->
                    <!--<button type="button" name="button" class="text-blue-500 hover:text-blue-700">リツイート</button>-->
                </form>
              @endif
            </div>
            


            </div>
          @endforeach
          
        </div>
      </div>
    </div>
  </div>

</x-app-layout>

