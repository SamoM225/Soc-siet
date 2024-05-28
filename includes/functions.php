<?php
include_once 'classes.php';
include_once 'db_inc.php';

/*
function fetchPostsFromDatabase($conn)
{
    $posts = array();
    $sql = "SELECT p.post_id, p.account_id, u.account_name, u.pfp, p.description, p.img, u.account_enabled
            FROM posts p
            INNER JOIN accounts u ON p.account_id = u.account_id
            ORDER BY p.post_id DESC";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $posts[] = $row;
        }
    }

    return $posts;
}
*/
function renderComment($comment, $date)
{
    echo '<div style="font-size: 10px; color: gray">
                        <img src="' . $comment['pfp'] . '" class="rounded-circle" style="width: 40px; margin-right: 4px"/>
                        <a href="#"><b>' . $comment['account_name'] . '</b><br></a> <span style="font-size: 16px">' . $comment['comment_text'] . '</span>
                        <p style="opacity: 0.6">Posted at ' . $date . '</p>        
                    </div>';
}

function renderPost($post)
{
    $comment = new Post();
    $user = new Account();
    $comments = $comment->readComment($post['post_id']);


    if ($post['account_enabled'] === 1) {
        if($user->isFriend($_SESSION['user_id'], $post['account_id']) || $_SESSION['user_id'] === $post['account_id']){
        echo '<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div style="font-size: 14px; color: gray">
                        <img src="' . $post['pfp'] . '" class="rounded-circle" style="width: 40px; margin-right: 4px"/>
                        <a href="#"><b>' . $post['account_name'] . '</b></a> shared a post        
                    </div>
                    <br/>
                    <div><span>' . $post['description'] . '</span></div>';
        if (file_exists($post['img'])) {
            echo '<img src="' . $post['img'] . '" style="width: 100%;"/><br/><br/>';
        }

        echo '<hr/>
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#commentsCollapse' . $post['post_id'] . '" aria-expanded="false" aria-controls="commentsCollapse' . $post['post_id'] . '">
                    <i class="bi bi-chat-square"></i>
                    <span>Comment</span>
                </button>
                
                </div>
                
                
                <div class="collapse" id="commentsCollapse' . $post['post_id'] . '">
                        
                    <div class="card card-body">
                    ';
        foreach ($comments as $comment) {
            $commentTimestamp = strtotime($comment['comment_date']);
            $timeDifference = time() - $commentTimestamp;
            $difference = '';
            if ($timeDifference < 60) {
                $difference = 'Just now';
            } elseif ($timeDifference < 3600) {
                $difference = floor($timeDifference / 60) . ' minutes ago';
            } elseif ($timeDifference < 86400) {
                $difference = floor($timeDifference / 3600) . ' hours ago';
            } else {
                $difference = floor($timeDifference / 86400) . ' days ago';
            }
            renderComment($comment, $difference);
        }
        
        echo '
                        </div>
                        <form id="ajax-form" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="POST" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" name="comment" placeholder="Write your comment here" rows="1"></textarea>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                        <input type="hidden" name="postId" value="' . $post['post_id'] . '">
                    </form>
                    
                        </div>
                    
                </div> 
            </div>
        </div><br>';
    
    }
}
}

function renderPpl($guy)
{
    echo '<div class="row"><!-- Start -->
    <div class="col-md-2" style="text-align: right">';
    
    if (!empty($guy['pfp'])) {
        echo '<img src="' . $guy['pfp'] . '" class="rounded-circle" alt="" style="width: 44px; margin-top: 10px" />';
    } else {
        echo '<img src="../assets/images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px" />'; // Default icon
    }

    echo '</div>
    <div class="col-md-10">
        <div style="margin-top: 10px">
            <a href="#">' . $guy['account_name'] . '</a><br />
            <form id="ajax-form" method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
                <input type="hidden" name="friend_id" value="' . $guy['account_id'] . '">
                <button type="submit" name="request_friend" class="fc-btn fc-btn-default" id="requestfr">Request Friend</button>
            </form>
        </div>
    </div>
</div>';
}
function renderFriendRequest($friend) {
    if($friend['account_id'] != $_SESSION['user_id']){
    echo '<div class="row">
              <div class="col-md-2" style="text-align: right">
                  <img src="../assets/images/profile-default.png" class="rounded-circle" style="width: 44px; margin-top: 10px" />
              </div>
              <div class="col-md-10">
                  <div style="margin-top: 10px">
                      <a href="#">' . $friend['account_name'] . '</a><br />
                      <form class="ajax-form" method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
                          <input type="hidden" name="add_friend_id" value="' . $friend['account_id'] . '">
                          <button type="submit" name="accept_friend" class="fc-btn fc-btn-default">Confirm</button>
                      </form>
                      <form class="ajax-form" method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
                          <input type="hidden" name="delete_friend" value="' . $friend['account_id'] . '">
                          <button type="submit" class="fc-btn fc-btn-delete">Delete</button>
                      </form>
                  </div>
              </div>
          </div>';
}else{
    echo 'Žiadny priatelovia ';
}
}

