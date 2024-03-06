addEventListeners();

const textarea = document.getElementById('editor-comment');
const submitButton = document.getElementById('submit-btn');
const cancelButton = document.getElementById('cancel-btn');
const commentForm = document.getElementById('commentForm');
const sortComments = document.getElementById('CommentsortDateBtn');

function showButtons() {
    if(submitButton){
        submitButton.style.display = 'flex';
    }
    if(cancelButton){
        cancelButton.style.display = 'flex';
    }
}

function hideButtons() {
    if(submitButton){
        submitButton.style.display = 'none';
    }
    if(cancelButton){
        cancelButton.style.display = 'none';
    }
}

if(textarea){
    textarea.addEventListener('input', function() {
        if (textarea.value.trim() !== '') {
            submitButton.type = 'submit';
            submitButton.style.backgroundColor='#083d6c';
        } else {
            submitButton.type = 'button';
            submitButton.style.backgroundColor='#7b7b7b';
        }
    });
    textarea.addEventListener('click', function() {
        showButtons();
    });
}

if(cancelButton){
    cancelButton.addEventListener('click', function() {
        resetCommentTextarea();
        hideButtons();
    });
    cancelButton.addEventListener('click', function() {
        resetCommentTextarea();
        hideButtons();
    });
}

if(commentForm){
    commentForm.addEventListener('submit', function() {
        event.preventDefault();
        
        const formData = new FormData(document.getElementById('commentForm'));
        const articleId = document.querySelector('input[name="article_id"]').value;
        formData.append('article_id', articleId);

        fetch('/comments/add', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.status === 401) {
                throw new Error('User not authenticated'); // Throw an error for unauthenticated user
            }
            return response.json();
        })
        .then(data => {
            // Handle the response data, maybe update the comments section
            resetCommentTextarea();
            updateComments(data, articleId);
        })
        .catch(error => {
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.innerHTML = `<p class="error-message">${error.message}</p>`;
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
            resetCommentTextarea();
        });
    });
}


function handleDeleteComment(comment) {
    event.preventDefault();
    const commentId = comment.querySelector('input[name="comment_id"]').value;
    const articleId = document.querySelector('input[name="article_id"]').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/comments/delete/${commentId}`, {
        method: 'DELETE', // Use the DELETE method for comment deletion
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        // Handle the response data, maybe update the comments section
        updateComments(data, articleId);
    })
    .catch(error => {
        console.error('Error deleting comment:', error);
        // Handle errors or display error messages to the user
    });
}    

function handleEditComment(comment) {
    const commentId = comment.querySelector('input[name="comment_id"]').value;
    const commentText = comment.querySelector('.comment-text');
    const editBtn = comment.querySelector('.editComment');
    const deleteBtn = comment.querySelector('.deleteComment');
    const voteBtns = comment.querySelector('.reputation');
    const saveBtn = comment.querySelector('.save-comment-btn');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const originalText = commentText.textContent;

    // Hide edit button and show save button
    editBtn.style.display = 'none';
    deleteBtn.style.display = 'none';
    voteBtns.style.display = 'none';
    saveBtn.style.display = 'inline-block';

    // Create a textarea for editing
    const textarea = document.createElement('textarea');
    textarea.classList.add('edit-comment-textarea');
    textarea.value = originalText;

    // Replace comment text with textarea
    comment.replaceChild(textarea, commentText);

    // Save button click event handler
    saveBtn.addEventListener('click', function() {
        const newText = textarea.value;
        fetch(`/comments/update/${commentId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ newText: newText }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(updatedComment => {
            console.log('Updated comment:', updatedComment);
            // Implement logic to update the UI with the updated comment if needed
        })
        .catch(error => {
            console.error('Error updating comment:', error);
            // Handle errors or display error messages to the user
        });

        // Simulate comment update for demonstration purposes
        comment.replaceChild(commentText, textarea);
        commentText.textContent = newText;
        editBtn.style.display = 'inline-block';
        deleteBtn.style.display = 'inline-block';
        voteBtns.style.display = 'flex';
        saveBtn.style.display = 'none';
    });
}

let ascending = true;

if(sortComments){
    sortComments.addEventListener('click', function() {
        const sort_iconUp = document.querySelector('#sort_iconUp');
        const sort_iconDown = document.querySelector('#sort_iconDown');
        sortOrder = 'asc';
        if (ascending) {
            sort_iconUp.style.display= 'block';
            sort_iconDown.style.display= 'none';
        } else {
            sort_iconUp.style.display= 'none';
            sort_iconDown.style.display= 'block';
            sortOrder = 'desc';
        }
        const articleId = document.querySelector('input[name="article_id"]').value;
        fetch(`/comments/sortByDate/${sortOrder}`,{
            method: 'GET', // Use the DELETE method for comment deletion
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.length > 0) {
                updateComments(data,articleId);
                ascending = !ascending;
            } else {
                throw new Error('Empty or invalid data received.');
            }
        })
        .catch(error => {
            console.error('Error fetching/sorting comments:', error);
            // Handle errors or display error messages to the user
            response.text().then(text => {
                console.error('Response text:', text);
            });
        });
        
    });
}


function resetCommentTextarea() {
    editor_comment=document.getElementById('editor-comment');
    if(editor_comment){
        editor_comment.value = ''; // Clear the textarea value
    }
    hideButtons();
}

function updateComments(data,articleId) {
    document.querySelector('.comments-section').innerHTML = '';

    data.forEach(comment => {
        if (comment.article_id == articleId) {
            console.log(comment.comment_id);
            fetch(`/comments/partials/${comment.comment_id}`) 
            .then(response => response.json())
            .then(Commentdata => {
                // Append the HTML content of the partial view for each comment
                document.querySelector('.comments-section').insertAdjacentHTML('beforeend', Commentdata.html);
                addEventListeners();
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }    
    });
}

function addEventListeners() {
    const comments = document.querySelectorAll('.comment');
    
    comments.forEach(comment => {
        const editBtn = comment.querySelector('.editComment');
        if (editBtn) {
        editBtn.addEventListener('click', () => handleEditComment(comment));
        }
    });
    
    comments.forEach(comment => {
        const deleteBtn = comment.querySelector('.deleteComment');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => handleDeleteComment(comment));
        }
    });
}