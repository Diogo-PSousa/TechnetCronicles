function openTab(evt, tabName) {

    var i, tabcontent, tablinks;


    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }


    tablinks = document.getElementsByClassName("tab-button");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }


    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}


document.getElementById('Articles').style.display = "block";

function submitProfileImageForm(userId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const fileInput = document.getElementById('fileInput');
    const profileImage = document.getElementById('profileImage'); // Get the profile image element
    const formData = new FormData();

    if (fileInput.files.length > 0) {
        formData.append('file', fileInput.files[0]);
        formData.append('type', 'profile');
        formData.append('id', userId);

        fetch('/file/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            // Assuming the server returns the path of the uploaded image
            if (data.filePath) {
                profileImage.src = data.filePath; // Update the image src
            }

            const messageContainer = document.getElementById('messageContainer');
            messageContainer.innerHTML = '<p class="success-message">Profile image updated successfully.</p>';
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.innerHTML = '<p class="error-message">' + error.message + '</p>';
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
        });
    }
}



function toggleEditName() {
    const usernameElement = document.getElementById('username');
    const editButton = document.querySelector('#username + .edit-button');
    const userId = document.getElementById('userId').value; 

    if (usernameElement.contentEditable === 'false') {
        usernameElement.contentEditable = 'true';
        editButton.innerHTML = '&#10003;';
    } else {
        usernameElement.contentEditable = 'false';
        editButton.innerHTML = '&#9998;'; 
        const newUsername = usernameElement.innerText.trim();
        updateUsername(newUsername, userId); 
    }
}

function toggleEditBio() {
    const bioElement = document.getElementById('bio');
    const editButton = document.querySelector('#bio + .edit-button');
    const userId = document.getElementById('userId').value; 

    if (bioElement.contentEditable === 'false') {
        bioElement.contentEditable = 'true';
        editButton.innerHTML = '&#10003;'; 
    } else {
        bioElement.contentEditable = 'false';
        editButton.innerHTML = '&#9998;';
        const newBio = bioElement.innerText.trim();
        updateBio(newBio, userId); 
    }
}

function updateUsername(newUsername, userId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/user/updateUsername', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            username: newUsername,
            user_id: userId
        })
    })
        .then(data => {
            document.getElementById('messageContainer').innerHTML = `<p class="success-message">${"Sucess Updating Username"}</p>`;
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
        })
        .catch(error => {
            document.getElementById('messageContainer').innerHTML = `<p class="error-message">Error updating username: ${"Error Updating Username"}</p>`;
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
        });
}



function updateBio(newBio, userId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/user/updateBio', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            bio: newBio,
            user_id: userId
        })
    })
        .then(data => {
            document.getElementById('messageContainer').innerHTML = `<p class="success">${"Sucess Updating Bio!"}</p>`;
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
        })
        .catch(error => {
            document.getElementById('messageContainer').innerHTML = `<p class="error">Error updating bio: ${"Error Updating Bio"}</p>`;
            setTimeout(() => {
                messageContainer.firstChild.classList.add('fade-out');
                setTimeout(() => {
                    messageContainer.innerHTML = '';
                }, 2000);
            }, 2000);
        });
}

function submitFollowForm() {
    const form = document.getElementById('followForm');
    const followButton = form.querySelector('button');
    const username = followButton.getAttribute('data-username');
    const isFollowing = followButton.textContent.trim() === 'Following';
    const method = isFollowing ? 'DELETE' : 'POST';
    const url = method === 'POST' ? '/follow/' + username : '/unfollow/' + username;


    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            '_token': csrfToken, // Assuming you have the CSRF token in a variable
            '_method': method, // Method spoofing for Laravel
            'username': username, // Sending the username in the body now
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
                // Update the button label
                if (isFollowing) {
                    followButton.textContent = 'Follow';
                    followButton.classList.remove('unfollow-text');
                    followButton.classList.add('follow-text');
                } else {
                    followButton.textContent = 'Following';
                    followButton.classList.remove('follow-text');
                    followButton.classList.add('unfollow-text');
                }
                displayMessage(data.success, 'success');
            }
            followButton.blur();
        })
        .catch(error => {
            console.error('Error:', error);
            displayMessage('An error occurred. Please try again.', 'error');
        });
}

function toggleUserReportForm() {
    var userReportForm = document.getElementById('userReportForm');
    userReportForm.style.display = userReportForm.style.display === "none" ? "block" : "none";
}

function displayMessage(message, type) {
    const messageContainer = document.getElementById('followUserMessageContainer');
    messageContainer.className = 'followUserMessageContainer';
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


