document.addEventListener('DOMContentLoaded', function () {
    // Select all links inside the news-feed-options class
    var links = document.querySelectorAll('.news-feed-options a');

    links.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Remove 'active' class from all links
            document.querySelectorAll('.news-feed-options a').forEach(function (el) {
                el.classList.remove('active');
            });

            // Add 'active' class to clicked link
            this.classList.add('active');

            var url = this.getAttribute('href');

            // Perform AJAX request using the Fetch API
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(function (response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(function (data) {
                    // Update the articles container with the new content
                    document.querySelector('.article-container').innerHTML = data;
                })
                .catch(function () {
                    alert('An error occurred while fetching data.');
                });
        });
    });
});

function submitFollowTagForm(event) {
    event.preventDefault();
    event.stopPropagation();
    const followButton = event.currentTarget;
    const tagName = followButton.getAttribute('data-tagname');
    const isFollowing = followButton.classList.contains('unfollow-text');
    const allButtons = document.querySelectorAll(`button[data-tagname="${tagName}"]`);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = isFollowing ? '/tag/unfollow/' + tagName : '/tag/follow/' + tagName;

    fetch(url, {
        method: 'POST', // Use POST for compatibility with Laravel's method spoofing
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            '_token': csrfToken,
            '_method': isFollowing ? 'DELETE' : 'POST', // Spoof DELETE method
            'tagName': tagName, // Send the tag name in the body
        }),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                displayMessage(data.error, 'error');
            } else if (data.info) {
                displayMessage(data.info, 'info');
            } else {
                allButtons.forEach(button => {
                    if (isFollowing) {
                        button.textContent = 'Follow';
                        button.classList.remove('unfollow-text');
                        button.classList.add('follow-text');
                    } else {
                        button.textContent = 'Following';
                        button.classList.remove('follow-text');
                        button.classList.add('unfollow-text');
                    }
                });
                displayMessage(data.success, 'success');
            }
            followButton.blur();
        })
        .catch(error => {
            console.error('Error:', error);
            displayMessage('An error occurred. Please try again.', 'error');
        });
}

function displayMessage(message, type) {
    const messageContainer = document.getElementById('followMessageContainer');

    messageContainer.className = 'followMessageContainer';
    messageContainer.textContent = message;

    if (type) {
        messageContainer.classList.add(type);
    }
    messageContainer.classList.add('active');
    messageContainer.classList.remove('fade-out');
    setTimeout(() => {
        messageContainer.classList.add('fade-out');
        setTimeout(() => {
            messageContainer.classList.remove('active');
        }, 4000);
    }, 2000);
}

// Track current page
let currentPage = 1;
let busy = false; // flag to prevent multiple simultaneous loads

// Function to check if the user scrolled to the bottom
function nearBottomOfPage() {
    return window.innerHeight + window.scrollY >= document.body.offsetHeight - 100;
}

// Function to load more data
function loadMoreData(page) {
    busy = true;
    fetch(`?page=${page}`, {
        headers: {
            "X-Requested-With": "XMLHttpRequest" // Important for Laravel to recognize AJAX request
        }
    })
        .then(response => response.text())
        .then(data => {
            const articleContainer = document.querySelector(".article-container");
            if (articleContainer) {
                articleContainer.insertAdjacentHTML('beforeend', data);
            } else {
                // Log an error or handle the situation if no element is found.
                console.error("No .article-container element found");
            }
            busy = false;
        })
        .catch(err => {
            console.log(err);
            busy = false;
        });
}


// Scroll event listener
window.onscroll = () => {
    if (nearBottomOfPage() && !busy) {
        currentPage++;
        loadMoreData(currentPage);
    }
};
