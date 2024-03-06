@if (count($accounts) > 0)
<div class="results-section">
    <h2>Users</h2>
    <div class="user-list-search">
        @foreach ($accounts as $account)   
            <a href="/user/{{ $account->user_id }}" class="user-box">
                <img src="{{ asset('img/pessoa.png') }}" alt="User Image">
                {{ $account->username }}
            </a>
        @endforeach
    </div>
</div>
@endif

@if (count($news) > 0)
<div class="results-section">
    <h2>News</h2>
    <div class="user-list-search">
        @foreach ($news as $article)
        <a href="{{ route('news.post', ['article' => $article->newsarticle_id]) }}" class="user-box">
                <img src="{{ asset('img/default.png') }}" alt="Default Image">
                {{ $article->title }}
        </a>
        @endforeach
    </div>
</div>
@endif

@if (count($comments) > 0)
<div class="results-section">
    <h2>Comments</h2>
    <div class="user-list-search">
        @foreach ($comments as $comment)
        <a href="{{ route('news.post', ['article' => $comment->article_id]) }}" class="user-box">
            <img src="{{ asset('img/pessoa.png') }}" alt="User Image">        
            {{ $comment->body }}
        </a>
        @endforeach
    </div>
</div>
@endif

@if (count($tags) > 0)
<div class="results-section">
    <h2>Tags</h2>
    <div class="user-list-search">
        @foreach ($tags as $tag)
        <a href="{{ route('news.tag', $tag->name) }}" class="user-box">
                {{ $tag->name }}
        </a>
        @endforeach
    </div>
</div>
@endif
