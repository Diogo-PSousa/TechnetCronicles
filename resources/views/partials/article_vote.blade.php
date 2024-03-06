<div class="reputation">
    <div class="vote-button {{ $article->userVoteType == 'Upvote' ? 'on' : '' }}"
        data-article-id="{{ $article->newsarticle_id }}" data-vote-type="Upvote"
        onclick="handleVoteClick(this, '{{ $article->newsarticle_id }}', 'Upvote')">
        <svg width="36" height="30">
            <path d="M2 26h32L18 10z" fill="currentColor" stroke="currentColor" stroke-linejoin="round"
                stroke-width="4"></path>
        </svg>
    </div>
    <p id="reputation-value-{{ $article->newsarticle_id }}" class="reputation-value">{{ $article->reputation }}</p>
    <div class="vote-button downvote {{ $article->userVoteType == 'Downvote' ? 'on' : '' }}"
        data-article-id="{{ $article->newsarticle_id }}" data-vote-type="Downvote"
        onclick="handleVoteClick(this, '{{ $article->newsarticle_id }}', 'Downvote')">
        <svg width="36" height="36">
            <path d="M2 10h32L18 26 2 10z" fill="currentColor" stroke="currentColor" stroke-linejoin="round"
                stroke-width="4"></path>
        </svg>
    </div>
</div>