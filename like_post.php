<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST["postId"];
    $servername = "localhost";
    $username = "root";
    $password = "heslo";
    $dbname = "social_network";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE posts SET likes = likes + 1 WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        echo "Post liked successfully!";
    } else {
        echo "Error: Unable to like the post.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request!";
}
?>
