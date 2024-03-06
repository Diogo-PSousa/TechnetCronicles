@foreach($articles as $article)
<div class="article-box">
    @include('partials.article_vote')
    <div class="article">
        @include('partials.article_tags')
        <div class="article-header">
            <p class="posted-by">Posted by:</p>
            @if($article->creator !== null)
            <a href="/user/{{ $article->creator->user_id }}" class="username">{{ $article->creator->username }}</a>
            @else
            <p class="username">Deleted user</p>
            @endif
            <h3 class="article-title"><a href="{{ route('news.post', ['article' => $article->newsarticle_id]) }}">{{
                    $article->title }}</a></h3>
        </div>
        <a href="{{ route('news.post', ['article' => $article->newsarticle_id]) }}">
            <img src="{{ $article->getPostImage() }}" alt="news image">
            <p class="article-body">
                {{ Str::limit($article->body, 65) }}
            </p>
        </a>
        <div class="article-info">
            <a href="{{ route('news.post', ['article' => $article->newsarticle_id]) }}#comments-section">

                <div class="article-info-comments">
                    <i class="fa-regular fa-comments"></i>
                    <p>{{ $article->comments()->count() }}</p>
                </div>
            </a>
            @auth
            <div class="article-info-bookmark">
                <form class="bookmark-main-page" action="/toggle-favorite/{{ $article->newsarticle_id }}" method="POST"
                    id="favoriteForm">
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
            </div>

            <div class="article-info-report" onclick="toggleReportForm('reportForm{{ $article->newsarticle_id }}')">
                <i class="fa-regular fa-circle-xmark">
                </i>
                <p>Report</p>
            </div>
            @endauth
        </div>
        <div id="reportForm{{ $article->newsarticle_id }}" style="display: none;">
            <form action="{{ route('report.article', $article->newsarticle_id) }}" method="post" class="report-form">
                @csrf
                <textarea name="body" placeholder="Enter report reason" required></textarea>
                <button type="submit">Submit Report</button>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    function toggleReportForm(reportFormId) {
        var reportForm = document.getElementById(reportFormId);
        if (reportForm) {
            reportForm.style.display = reportForm.style.display === "none" ? "block" : "none";
        }
    }

</script>