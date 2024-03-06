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
@else
<p>No comment matches found.</p>
@endif
