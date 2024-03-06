@extends('layouts.app')

@section('content')
<div class="container">
    <div class="breadcrumbs">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-solid fa-house"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->username }}</li>
            </ol>
        </nav>
    </div>
    <div class="profile-container">
        <div id="messageContainer"></div>
        <div class="profile-left">
            <div class="user-info">
                <span class="reputationProfile">Reputation: {{ $user->reputation }}</span>
                <img src="{{ $user->profile_image ? $user->getProfileImage() : asset('img/pessoa.png') }}"
                    alt="Profile Image" id="profileImage">
                @if(auth()->check() && (auth()->user()->role == 2 || auth()->user()->username == $user->username))
                <div class="edit-container">
                    <label for="fileInput" class="edit-button">&#9998;</label>
                    <input id="fileInput" type="file" name="file" style="display: none;"
                        onchange="submitProfileImageForm({{ $user->user_id }})">
                </div>
                @endif
                <div class="edit-container">
                    <h1 id="username" contenteditable="false">{{ $user->username }}</h1>
                    @if(auth()->check() && (auth()->user()->role == 2 || (auth()->user()->role == 1 &&
                    auth()->user()->user_id == $user->user_id)))
                    <button onclick="toggleEditName()" class="edit-button">&#9998;</button>
                    @endif
                </div>
                <p> About </p>
                <div class="edit-container">
                    <bio id="bio" contenteditable="false">{{ $user->bio }}</bio>
                    @if(auth()->check() && (auth()->user()->role == 2 || (auth()->user()->role == 1 &&
                    auth()->user()->user_id == $user->user_id)))
                    <button onclick="toggleEditBio()" class="edit-button">&#9998;</button>
                    @endif
                </div>
                <input type="hidden" id="userId" value="{{ $user->user_id }}">
            </div>
            <div id="followUserMessageContainer"></div>
            @if (Auth::check() && Auth::user()->username !== $user->username)
            @php
            $isFollowing = Auth::user()->following->contains($user);
            @endphp
            <form id="followForm" method="POST">
                @csrf
                @if ($isFollowing)
                @method('DELETE')
                @endif
                <input type="hidden" name="username" value="{{ $user->username }}">
                <button type="button" data-username="{{ $user->username }}"
                    class="{{ $isFollowing? 'unfollow-text' : 'follow-text' }}" onclick="submitFollowForm()">
                    {{ $isFollowing ? 'Following' : 'Follow' }}
                </button>
            </form>
            @endif
            @if (Auth::check() && Auth::user()->username != $user->username)
            <button onclick="toggleUserReportForm()">Report User</button>


            <div id="userReportForm" style="display: none;">
                <form action="{{ route('report.user', $user->user_id) }}" method="post" class="report-form">
                    @csrf
                    <textarea name="body" placeholder="Enter report reason" required></textarea>
                    <button type="submit">Submit Report</button>
                </form>
            </div>
            @endif
            <div id="deleteOwnAccountContainer"></div>
            @if (Auth::check() && Auth::user()->username == $user->username)
            <form action="/user/{{$user->user_id}}/delete" method="post">
                @csrf
                @method('DELETE')
                <button type="submit"
                    onclick="return confirm('Are you sure you want to delete yourself? This action cannot be undone. Confirm own deletion?');">Delete
                    Account</button>
            </form>
            @endif
        </div>

        <div class="profile-right">
            <div class="profile-stats">
                <ul>
                    <li><button class="tab-button" onclick="openTab(event, 'Articles')">Articles </button></li>
                    <li><button class="tab-button" onclick="openTab(event, 'Comments')">Comments </button></li>
                    <li><button class="tab-button" onclick="openTab(event, 'Bookmarked')">Bookmarked </button></li>
                    <li><button class="tab-button" id="topic-following" onclick="openTab(event, 'Topics')">
                            <p> {{$user->following_tags_count}} </p>
                            <p>Followed Topics</p>
                        </button></li>
                    <li><button class="tab-button" onclick="openTab(event, 'Following')">
                            <p> {{$user->following_count}} </p>
                            <p>Following</p>
                        </button></li>
                    <li><button class="tab-button" onclick="openTab(event, 'Followers')">
                            <p> {{$user->followers_count}} </p>
                            <p>Followers</p>
                        </button></li>
                </ul>
            </div>


            <div id="Articles" class="tab-content">
                <h2>Articles</h2>
                @forelse($user->newsArticles as $article)
                <a href="{{ route('news.post', ['article' => $article->newsarticle_id]) }}" class="articles">
                    <img src="{{ $article->getPostImage() }}">
                    <div>
                        <h3>{{ $article->title }}</h3>
                        <h4>{{ Str::limit($article->body, 50) }}
                            @if(strlen($article->body) > 50)
                            ...
                            @endif
                        </h4>
                    </div>
                </a>
                @empty
                <p>No articles to display.</p>
                @endforelse
            </div>
            <div id="Comments" class="tab-content">
                <h2>Comments</h2>
                @forelse($user->comments as $comment)
                <a href="{{ route('news.post', ['article' => $comment->newsarticle->newsarticle_id]) }}"
                    class="articles">
                    <h3>{{ $comment->body }}</h3>
                </a>
                @empty
                <p>No comments to display.</p>
                @endforelse
            </div>
            <div id="Bookmarked" class="tab-content">
                <h2>Bookmarked</h2>
                @forelse($user->favorites as $article)
                <a href="{{ route('news.post', ['article' => $comment->newsArticle->newsarticle_id]) }}#comments-section" class="articles">
                    <img src="{{ $article->getPostImage() }}">
                    <div>
                        <h3>{{ $article->title }}</h3>
                        <h4>{{ Str::limit($article->body, 50) }}
                            @if(strlen($article->body) > 50)
                            ...
                            @endif
                        </h4>
                    </div>
                </a>
                @empty
                <p>No bookmarks to display.</p>
                @endforelse
            </div>
            <div id="Topics" class="tab-content">
                <h2>Followed Topics</h2>
                @forelse($user->followingTags as $tag)
                <a href="{{ route('news.tag', $tag->name) }}">
                    <div class="topic-tag">
                        {{ $tag->name }}
                        @include('partials.follow_tag_button')
                    </div>
                </a>
                @empty
                <p>No topics to display.</p>
                @endforelse
            </div>

            <div id="Followers" class="tab-content">
                <h2>Followers</h2>
                @forelse($user->followers as $follower)
                <a href="/user/{{ $follower->user_id }}">
                    <div id="Follower" class="user-box">
                        <div class="user-box-left">
                            <img src="{{ asset('img/pessoa.png') }}" alt="Default Image">
                            <p>{{ $follower->username }}</p>
                        </div>
                        <p class="user-box-bio">{{ $follower->bio }}</p>
                    </div>
                </a>
                @empty
                <p>No followers to display.</p>
                @endforelse
            </div>
            <div id="Following" class="tab-content">
                <h2>Following</h2>
                @forelse($user->following as $following)
                <a href="/user/{{ $following->user_id }}">
                    <div id="Following" class="user-box">
                        <div class="user-box-left">
                            <img src="{{ asset('img/pessoa.png') }}" alt="Default Image">
                            <p>{{ $following->username }}</p>
                        </div>
                        <p class="user-box-bio">{{ $following->bio }}</p>
                    </div>
                </a>
                @empty
                <p>No following to display.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    var csrfToken = '{{ csrf_token() }}';
</script>

<script src="{{ asset('js/profile.js') }}"></script>
@endsection