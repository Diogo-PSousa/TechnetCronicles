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
@else
<p>No news matches found.</p>
@endif
