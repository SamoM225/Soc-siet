<?php
function connectToDatabase($host, $dbname, $username, $password) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function fetchPostsFromDatabase($conn) {
    $posts = array();
    $sql = "SELECT p.post_id, p.user_id, u.username, p.description, p.image_url, p.likes
            FROM posts p
            INNER JOIN users u ON p.user_id = u.user_id";
    $result = $conn->query($sql);
    
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $post = new Post($conn, $row['post_id'], $row['user_id'], $row['username'], $row['description'], $row['image_url'], $row['likes']);
            $posts[] = $post;
        }
    }

    return $posts;
}



class Post {
    private $postId;
    private $userId;
    private $userName;
    private $postDescription;
    private $imageUrl;
    private $likesCount;
    private $conn; 

    public function __construct($conn, $postId, $userId, $userName, $postDescription, $imageUrl, $likesCount) {
        $this->conn = $conn;
        $this->postId = $postId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->postDescription = $postDescription;
        $this->imageUrl = $imageUrl;
        $this->likesCount = $likesCount;
    }

    public function renderPost() {
        echo '<div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div style="font-size: 14px; color: gray">
                        <img src="images/profile-default.png" class="rounded-circle" style="width: 40px; margin-right: 4px"/>
                        <a href="#"><b>' . $this->userName . '</b></a> shared a <a href="#">link</a> in the group <a href="#">Lorem ipsum dolor sit</a>        
                    </div>
                    <br/>';
if (file_exists($this->imageUrl)) {
    echo '<img src="' . $this->imageUrl . '" style="width: 100%;"/><br/><br/>';
}

echo '<a href="#">' . $this->postDescription . '</a><br/>
                    <span>' . $this->postDescription . '</span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div style="text-align: right">
                            <span style="margin-right: 24px">' . $this->likesCount . ' likes</span>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row" style="margin-center: 0px;">
                    <div class="col-md-3">
                    <form id="likeForm" method="POST" action="like_post.php">
                    <input type="hidden" name="postId" value="' . $this->postId . '">
                    <button type="button" class="fc-btn fc-btn-white" onclick="likePost(' . $this->postId . ')">
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
    </div>
    <br>';
    }

    public function likePost($postId) {
        $sql = "UPDATE posts SET likes = likes + 1 WHERE post_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$postId]);
        $this->likesCount++;
    }
    
}

?>
