<?php
function fetchPostsFromDatabase($conn)
{
    $posts = array();
    $sql = "SELECT p.post_id, p.account_id, u.account_name, u.pfp, p.description, p.img, u.account_enabled
            FROM posts p
            INNER JOIN accounts u ON p.account_id = u.account_id";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = $row;
        }
    }

    return $posts;
}

function renderPost($post)
{
    if ($post['account_enabled']===1){
    echo '<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div style="font-size: 14px; color: gray">
                        <img src="' . $post['pfp'] . '" class="rounded-circle" style="width: 40px; margin-right: 4px"/>
                        <a href="#"><b>' . $post['account_name'] . '</b></a> shared a <a href="#">link</a> in the group <a href="#">Lorem ipsum dolor sit</a>        
                    </div>
                    <br/>
                    <div><span>' . $post['description'] . '</span></div>';
    if (file_exists($post['img'])) {
        echo '<img src="' . $post['img'] . '" style="width: 100%;"/><br/><br/>';
    }

    echo '<a href="#"></a><br/>
          
                </div>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
                <hr/>
                <div class="row" style="margin-center: 0px;">
                    <div class="col-md-3">
                    <form id="likeForm" method="POST" action="like_post.php">
                    <input type="hidden" name="postId" value="' . $post['post_id'] . '">
                    <button type="button" class="fc-btn fc-btn-white" onclick="likePost(' . $post['post_id'] . ')">
                        <div class="fc-icon">
                            <label>Like</label>
                        </div>                                    
                    </button>
                </form>                 
                </div>
                    <div class="col-md-3">
                        <button class="fc-btn fc-btn-white">
                            <div class="fc-icon fc-icon-comentar">
                                <label>Comment</label>
                            </div>                                    
                        </button>
                    </div>
                </div>
                <br/>
            </div> 
        </div>
    </div>';
    echo '
    <form action="" method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="comment" placeholder="Write your comment here" rows="1"></textarea>
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
        </div>
        <input type="hidden" name="postId" value="' . $post['post_id'] . '">
    </form>
';



}

}