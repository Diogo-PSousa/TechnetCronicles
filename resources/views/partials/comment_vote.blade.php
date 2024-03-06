<div class="reputation">
    <div class="vote-button {{ $comment->userVoteType == 'Upvote' ? 'on' : '' }}"
        data-comment-id="{{ $comment->comment_id }}" data-vote-type="Upvote"
        onclick="handleCommentVoteClick(this, '{{ $comment->comment_id }}', 'Upvote')">
        <svg width="25" height="25" viewBox="0 0 36 36">
            <path d="M2 26h32L18 10z" fill="currentColor" stroke="currentColor" stroke-linejoin="round"
                stroke-width="4"></path>
        </svg>
    </div>
    <p id="comment-reputation-value-{{ $comment->comment_id }}" class="reputation-value">{{ $comment->reputation }}</p>
    <div class="vote-button downvote {{ $comment->userVoteType == 'Downvote' ? 'on' : '' }}"
        data-comment-id="{{ $comment->comment_id }}" data-vote-type="Downvote"
        onclick="handleCommentVoteClick(this, '{{ $comment->comment_id }}', 'Downvote')">
        <svg width="25" height="25" viewBox="0 0 36 36">
            <path d="M2 10h32L18 26 2 10z" fill="currentColor" stroke="currentColor" stroke-linejoin="round"
                stroke-width="4"></path>
        </svg>
    </div>
</div>