@extends('layouts.app')

@section('content')
<div class="main-container row">
    <div class="top-feed-container">
        <div class="tag-name">
            <h2>{{ $tag->name }}</h2>
            @include('partials.follow_tag_button')
        </div>
        <div class="news-feed-options">
            @auth
            <a href="{{ route('news.tag', ['tag' => $tag->name, 'sort' => 'followed']) }}"
                class="{{ request('sort') === 'followed' ? 'active' : '' }}" data-sort="followed">Feed</a>
            @endauth
            <a href="{{ route('news.tag', ['tag' => $tag->name, 'sort' => 'top']) }}"
                class="{{ request('sort', 'top') === 'top' ? 'active' : '' }}" data-sort="top">Top News</a>
            <a href="{{ route('news.tag', ['tag' => $tag->name, 'sort' => 'recent']) }}"
                class="{{ request('sort') === 'recent' ? 'active' : '' }}" data-sort="recent">Recent News</a>
        </div>
        <div class="article-container">
            @include('partials.articles')
        </div>
    </div>

    <div class="articles-right-side">
        <div class="popular-topics">
            <h3 class="sans-serif">Popular Topics</h3>
            <ol>
                @foreach($popularTags as $topic)
                <li><a href="{{ route('news.tag', $topic->name) }}">{{ $topic->name }} <p>{{ $topic->news_articles_count
                            }} articles</p></a>
                </li>
                @endforeach
            </ol>
        </div>
        @auth
        <div class="write-article">
            <a href="{{ route('news.post.write') }}">
                <button>Write Article</button>
            </a>
            <div id="followMessageContainer"></div>
        </div>
        @endauth
    </div>
</div>
@endsection