<div class="comment">
    <div class="comment-top">
        <div class="comment-top-left">
            <i class="fas fa-user"></i>
            <p>{{ $comment->user->username}}</p>
            @if ($comment->user->user_id == 1)
                <div class="CommentTag">Admin</div>
            @endif
            @if ($comment->user->user_id == $comment->newsArticle->creator_id)
                <div class="CommentTag">Author</div>
            @endif
            @auth
                @if ( ($comment->user !==null && Auth::user()->user_id === $comment->user->user_id) ||
                auth()->user()->role == 2)
                    <a><i class="fa-regular fa-pen-to-square editComment"></i></a>
                    @if (!$comment->votes || auth()->user()->role == 2)
                        <a><i class="fa-solid fa-trash-can deleteComment"></i></a>
                    @endif
                    <input type="hidden" name="comment_id" value="{{ $comment->comment_id }}"> </input>
                @endif
            @endauth
        </div>
        {{ $comment->date_time->diffForHumans() }}
    </div>
    <p class="comment-text">{{ $comment->body}}</p>
    <button class="save-comment-btn" style="display: none;">Save</button>
    @include('partials.comment_vote')
    <i class="fa-regular fa-circle-xmark" onclick="toggleReportForm({{ $comment->comment_id }})"></i>
    Report
    <div id="reportForm{{ $comment->comment_id }}" style="display: none;">
        <form action="{{ route('report.comment', $comment->comment_id) }}" method="post">
            @csrf
            <textarea name="body" placeholder="Enter report reason" required></textarea>
            <button type="submit">Submit Report</button>
        </form>
    </div>
</div>
<script>
    function toggleReportForm(commentId) {
        var reportForm = document.getElementById('reportForm' + commentId);
        reportForm.style.display = reportForm.style.display === "none" ? "block" : "none";
    }
</script>