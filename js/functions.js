function likePost(postId) {
    var likeButton = document.getElementById("likeButton");
    if (likeButton) {
        likeButton.disabled = true;
    }

    var xhr = new XMLHttpRequest();

    var formData = new FormData();
    formData.append("postId", postId);

    xhr.open("POST", "like_post.php", true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var likeCountSpan = document.getElementById("likeCount");
                if (likeCountSpan) {
                    var currentLikes = parseInt(likeCountSpan.innerText);
                    likeCountSpan.innerText = currentLikes + 1;
                }
            } else {
                alert("An error occurred while processing the request.");
            }
            if (likeButton) {
                likeButton.disabled = false;
            }
        }
    };
    xhr.send(formData);
}
