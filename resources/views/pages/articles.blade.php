@extends('layouts.app')

@section('content')
<div class="main-container row">
    <div class="top-feed-container">
        <div class="news-feed-options">
            @auth
            <a href="{{ route('news.index', ['sort' => 'followed']) }}"
                class="{{ request('sort') === 'followed' ? 'active' : '' }}" data-sort="followed">Feed</a>
            @endauth
            <a href="{{ route('news.index', ['sort' => 'top']) }}"
                class="{{ request('sort', 'top') === 'top' ? 'active' : '' }}" data-sort="top">Top News</a>
            <a href="{{ route('news.index', ['sort' => 'recent']) }}"
                class="{{ request('sort') === 'recent' ? 'active' : '' }}" data-sort="recent">Recent News</a>
        </div>

        <div class="article-container">
            @include('partials.articles')
        </div>
    </div>

    <div class="articles-right-side">
        <div class="popular-topics">
            <h3 class="sans-serif">Most Popular Topics</h3>
            <ol>
                @foreach($popularTags as $topic)
                @if($topic->accepted === 1)
                <li>
                    <a href="{{ route('news.tag', $topic->name) }}">{{ $topic->name }} <p>{{ $topic->news_articles_count}} articles</p></a>
                </li>
                @endif
                @endforeach
            </ol>
            @if (auth()->check())
            Propose Topic
            <div class="propose-topic-form">
                <form action="/createTag" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Propose a new topic">
                    <button type="submit">Submit</button>
                </form>
            </div>
            @endif
        </div>
        @auth
        <div class="write-article">
            <a href="{{ route('news.post.write') }}">
                <button>Write Article</button>
            </a>
        </div>
        <div id="followMessageContainer"></div>
        @endauth
    </div>
</div>
@endsection