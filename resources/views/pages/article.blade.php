@extends('layouts.app')
@section('content')
<div class="article-page">
    <input type="hidden" name="article_id" value="{{$article->newsarticle_id}}">
    <header>
        @include('partials.article_vote')
        <div class="title_user_date">
            @if($article->creator !== null)
            <a href="/user/{{ $article->creator->user_id }}" class="username">{{
                $article->creator->username}}</a>
            @else
            <p class="username">Deleted user</p>
            @endif
            @if($article->date_time->diffInHours(now()) < 24) {{ $article->date_time->format('H:i') }}
                @else
                {{ $article->date_time->format('d/m/Y H:i') }}
                @endif
            <div class="article-info-bookmark">
                <h1 class="article-title">{{$article->title}}</h1>
                @auth
                    <form class="bookmark-main-page" action="/toggle-favorite/{{ $article->newsarticle_id }}" method="POST" id="favoriteForm">
                        @csrf
                        @method('POST')
                        <button type="submit" id="toggleFavoriteBtn">
                            @if($article->favorites)
                                <i class="fa-solid fa-bookmark"></i> 
                            @else
                                <i class="fa-regular fa-bookmark"></i>
                            @endif
                        </button>
                    </form>
                @endauth
            </div>
        </div>
        @if($article->tags->isNotEmpty())
            <div class="topic">
                Topic:
                @include('partials.article_tags')
            </div>
        @endif
    </header>
    <div class="news-article-body">
        <img src="{{ $article->getPostImage() }}">
        <br>
        {!!$article->body!!}
        @auth
        @if ( ($article->creator !==null && Auth::user()->user_id === $article->creator->user_id) ||
        auth()->user()->role == 2)

        <div class="article_page_buttons">
            <section id="edit">
                <a href="{{ route('news.post.edit', ['article' => $article->newsarticle_id]) }}"><button>Edit</button></a>
            </section>
            @if (($article->comments->count() === 0 && $article->articleVote->count()===1) || auth()->user()->role == 2)
                <section id="delete">
                    <form method="POST"
                        action="{{ route('news.post.delete', ['article' => $article->newsarticle_id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </section>
            @endif
        </div>
        @endif
        @endauth
        <div class="comments" id="comments-section">
            <div class="commentHeader">
                <div>
                    <i class="fa-solid fa-comment"></i>
                    {{ $article->comments()->count() }}
                </div>
                    <div id="CommentsortDateBtn">
                        Sort by Date
                        <i class="fa-solid fa-chevron-down" id="sort_iconUp" style="display: none;"></i>
                        <i class="fa-solid fa-chevron-up" id="sort_iconDown" style="display: none;"></i>
                    </div>
            </div>
            @if(Auth::check())
            <form id="commentForm" method="POST">
                @csrf
                <section class="write-comment">
                    <input type="hidden" name="article_id" value="{{ $article->newsarticle_id }}">
                    <input type="hidden" name="author_id" value="{{ $article->creator->user_id }}">
                    <textarea id="editor-comment" name="comment" placeholder="Enter a comment here"></textarea>
                    <button id="submit-btn" style="display: none;" type="button">Submit</button>
                    <button id="cancel-btn" style="display: none;" type="button">Cancel</button>
                </section>
            </form>
            @endif
            <div id="messageContainer"></div>
            <div class="comments-section">
                @foreach($comments as $comment)
                @include('partials.comment', ['comment' => $comment, 'offset' => 0])
                @endforeach
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src={{ url('js/comment.js') }} defer></script>
@endsection