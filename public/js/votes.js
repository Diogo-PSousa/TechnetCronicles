function handleVoteClick(element, articleId, voteType) {
    if (!isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    sendVote(articleId, voteType);
}


function sendVote(articleId, voteType) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/news/' + articleId + '/vote/' + voteType, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateReputation(data.reputation, articleId, data.currentVoteStatus);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateReputation(newReputation, articleId, currentVoteStatus) {
    const reputationValueElement = document.getElementById(`reputation-value-${articleId}`);
    reputationValueElement.textContent = newReputation;

    const upvoteButton = document.querySelector(`[data-article-id="${articleId}"][data-vote-type="Upvote"]`);
    const downvoteButton = document.querySelector(`[data-article-id="${articleId}"][data-vote-type="Downvote"]`);

    if (currentVoteStatus === 'Upvote') {
        upvoteButton.classList.add('on');
        downvoteButton.classList.remove('on');
    } else if (currentVoteStatus === 'Downvote') {
        upvoteButton.classList.remove('on');
        downvoteButton.classList.add('on');
    } else {
        upvoteButton.classList.remove('on');
        downvoteButton.classList.remove('on');
    }
}

function handleCommentVoteClick(element, commentId, voteType) {
    if (!isAuthenticated) {
        window.location.href = '/login';
        return;
    }
    sendCommentVote(commentId, voteType);
}

function sendCommentVote(commentId, voteType) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/comment/' + commentId + '/vote/' + voteType, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCommentReputation(data.reputation, commentId, data.currentVoteStatus);
            }
        })
        .catch(error => console.error('Error:', error));
}

function updateCommentReputation(newReputation, commentId, currentVoteStatus) {
    const reputationValueElement = document.getElementById(`comment-reputation-value-${commentId}`);
    reputationValueElement.textContent = newReputation;

    const upvoteButton = document.querySelector(`[data-comment-id="${commentId}"][data-vote-type="Upvote"]`);
    const downvoteButton = document.querySelector(`[data-comment-id="${commentId}"][data-vote-type="Downvote"]`);

    if (currentVoteStatus === 'Upvote') {
        upvoteButton.classList.add('on');
        downvoteButton.classList.remove('on');
    } else if (currentVoteStatus === 'Downvote') {
        upvoteButton.classList.remove('on');
        downvoteButton.classList.add('on');
    } else {
        upvoteButton.classList.remove('on');
        downvoteButton.classList.remove('on');
    }
}
