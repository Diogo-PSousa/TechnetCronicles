<div id="FullResults">
    <div id="All" class="tabcontent">
        @include('partials.results_all')
    </div>

    <div id="Users" class="tabcontent" style="display:none;">
        @include('partials.results_users', ['accounts' => $accounts])
    </div>

    <div id="News" class="tabcontent" style="display:none;">
        @include('partials.results_news', ['news' => $news])
    </div>

    <div id="Comments" class="tabcontent" style="display:none;">
        @include('partials.results_comments', ['comments' => $comments])
    </div>

    @if (count($accounts) == 0 && count($news) == 0 && count($comments) == 0)
    <p>No matches or bad search.</p>
    @endif
</div>