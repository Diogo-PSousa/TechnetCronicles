<div class="article-tags">
    @foreach($article->tags as $tag)
    <a href="{{ route('news.tag', $tag->name) }}" class="tag tooltip">{{ $tag->name }}
        <span class="followBalloon">
            @include('partials.follow_tag_button')
        </span>
    </a>
    @endforeach
</div>