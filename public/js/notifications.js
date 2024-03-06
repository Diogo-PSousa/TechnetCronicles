
const pusher = new Pusher(pusherAppKey, {
    cluster: pusherCluster,
    encrypted: true
});

function notification(message){
    const notification = document.getElementById('notification');
    const closeButton = document.getElementById('closeButton');
    const notificationText = document.getElementById('notificationText');
    notificationText.textContent = message;
    notification.classList.add('show');

    closeButton.addEventListener('click', function() {
        notification.classList.remove('show');
    });

    setTimeout(function() {
        notification.classList.remove('show');
    }, 5000);
}

const channel = pusher.subscribe('technet-chronicles');

channel.bind('comment-on-post', function(data) {
    if(currentUser.user_id){
        if (data.authorId == currentUser.user_id) {
            notification(data.message);
        }
    }
});

channel.bind('vote-on-content', function(data) {
    if(currentUser.user_id){
        if (data.authorId == currentUser.user_id) {
            notification(data.message);
        }
    }
});
