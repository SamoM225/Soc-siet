<?php

class Account
{
	private $id;
	private $name;

	private $authenticated;
	public function __construct()
	{
		$this->id = NULL;
		$this->name = NULL;
		$this->authenticated = FALSE;
	}
	public function __destruct()
	{

	}

	public function getId(): ?int
	{
		return $this->id;
	}
	public function getName(): ?string
	{
		return $this->name;
	}

	public function isAuthenticated(): bool
	{
		return $this->authenticated;
	}

	public function addAccount(string $name, string $passwd): int
	{
		global $pdo;

		$name = trim($name);
		$passwd = trim($passwd);

		if (!is_null($this->getIdFromName($name))) {
			throw new Exception('User name not available');
		}


		$query = 'INSERT INTO social_network.accounts (account_name, account_passwd, account_enabled, role) VALUES (:name, :passwd, 1, "user")';
		$hash = password_hash($passwd, PASSWORD_DEFAULT);
		$values = array(':name' => $name, ':passwd' => $hash);

		try {
			$res = $pdo->prepare($query);
			$res->execute($values);
		} catch (PDOException $e) {
			throw new Exception('Database query error');
		}

		return $pdo->lastInsertId();
	}

	public function deleteAccount(int $id)
	{
		global $pdo;

		if (!$this->isIdValid($id)) {
			throw new Exception('Invalid account ID');
		}

		$query = 'UPDATE accounts SET account_enabled = 0 WHERE (account_id = :id)';

		try {
			$sql = $pdo->prepare($query);
			$sql->bindValue(':id', $id, PDO::PARAM_INT);
			$sql->execute();
			return ($sql->rowCount() > 0) ? True : False;
		} catch (PDOException $e) {
			throw new Exception('Database query error');
		}
	}

	public function editAccount(int $id, string $name, string $passwd, bool $enabled)
	{
		global $pdo;

		$name = trim($name);
		$passwd = trim($passwd);

		if (!$this->isIdValid($id)) {
			throw new Exception('Invalid account ID');
		}

		$idFromName = $this->getIdFromName($name);
		if (!is_null($idFromName) && ($idFromName != $id)) {
			throw new Exception('User name already used');
		}
		$query = 'UPDATE social_network.accounts SET account_name = :name, account_passwd = :passwd, account_enabled = :enabled WHERE account_id = :id';
		$hash = password_hash($passwd, PASSWORD_DEFAULT);
		$intEnabled = $enabled ? 1 : 0;
		$values = array(':name' => $name, ':passwd' => $hash, ':enabled' => $intEnabled, ':id' => $id);
		try {
			$res = $pdo->prepare($query);
			$res->execute($values);
		} catch (PDOException $e) {
			throw new Exception('Database query error');
		}
	}



	public function isIdValid(int $id): bool
	{
		$valid = TRUE;
		if (($id < 1) || ($id > 1000000)) {
			$valid = FALSE;
		}
		return $valid;
	}



	public function logout()
	{
		$_SESSION = array();

		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params["path"],
				$params["domain"],
				$params["secure"],
				$params["httponly"]
			);
		}
		session_destroy();
		header('Location: ../public/login.php');
		exit;
	}




	public function getIdFromName(string $name): ?int
	{
		global $pdo;



		$id = NULL;

		$query = 'SELECT account_id FROM social_network.accounts WHERE (account_name = :name)';
		$values = array(':name' => $name);

		try {
			$res = $pdo->prepare($query);
			$res->execute($values);
		} catch (PDOException $e) {
			throw new Exception('Database query error');
		}

		$row = $res->fetch(PDO::FETCH_ASSOC);

		if (is_array($row)) {
			$id = intval($row['account_id'], 10);
		}

		return $id;
	}
	public function renameUser($username, $oldUsername)
	{
		global $pdo;
		try {
			$sql = 'UPDATE accounts SET account_name = :username WHERE account_name = :oldUsername';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':username', $username, PDO::PARAM_STR);
			$sql->bindValue(':oldUsername', $oldUsername, PDO::PARAM_STR);
			$sql->execute();
			return true;
		} catch (PDOException $e) {
			throw new Exception('Error renaming user: ' . $e->getMessage());
		}
	}
	public function randomPeople($accountid)
	{
		global $pdo;
		try {
			$sql = 'SELECT a.account_name, a.pfp, a.account_id 
					FROM accounts a 
					LEFT JOIN friend_list f ON (a.account_id = f.account_id AND f.friend_id = :accountid) 
						OR (a.account_id = f.friend_id AND f.account_id = :accountid)
					WHERE a.account_id != :accountid AND f.account_id IS NULL
					ORDER BY RAND() LIMIT 3';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
			$sql->execute();
			$result = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		} catch (PDOException $e) {
			throw new Exception('Chyba vyberania náhodnych profilov: ' . $e->getMessage());
		}
	}

	public function requestFriend($accountid, $friendid, $status)
	{
		global $pdo;
		try {
			$sql = 'SELECT COUNT(*) FROM friend_list 
						 WHERE (account_id = :accountid AND friend_id = :friendid) /*Protekcia proti duplicite*/
							OR (account_id = :friendid AND friend_id = :accountid)'; //Ak A_ID = 1 a F_ID = 2, tak F_ID nemôže už požiadať A_ID 
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
			$sql->bindValue(':friendid', $friendid, PDO::PARAM_INT);
			$sql->execute();
			$count = $sql->fetchColumn();

			if ($count > 0) {
				return false;
			} else {
				$sql = 'INSERT INTO friend_list (account_id, friend_id, status) VALUES (:accountid, :friendid, :status)';
				$sql = $pdo->prepare($sql);
				$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
				$sql->bindValue(':friendid', $friendid, PDO::PARAM_INT);
				$sql->bindValue(':status', $status, PDO::PARAM_STR);
				$sql->execute();
				//výmena accountid-friendid pre vytvorenie 2 záznamov namiesto jedného
				//pre vyberanie priateľov, budú obidve strany mať nastavený záznam
				//jednoduchšie manažovanie friendlistu v PHP
				$sql = 'INSERT INTO friend_list (friend_id, account_id, status) VALUES (:accountid, :friendid, "Pending")';
				$sql = $pdo->prepare($sql);
				$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
				$sql->bindValue(':friendid', $friendid, PDO::PARAM_INT);
				$sql->execute();

				return true;
			}
		} catch (PDOException $e) {
			throw new Exception('Chyba requestFriend: ' . $e->getMessage());
		}
	}
	public function friendRequests($accountid)
	{
		global $pdo;
		try {
			$sql = 'SELECT f.account_id, a.account_name, f.friend_id 
				FROM friend_list f 
				JOIN accounts a ON f.account_id = a.account_id
				WHERE f.account_id != :accountid AND f.friend_id = :accountid AND f.status = "Request"';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
			$sql->execute();
			$result = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		} catch (PDOException $e) {
			throw new Exception('Chyba vyberanie friend requests: ' . $e->getMessage());
		}
	}
	public function addFriend($accountid, $friendid)
	{
		global $pdo;
		try {
			$sql = 'UPDATE friend_list SET status = "Accepted" 
                WHERE (account_id = :accountid AND friend_id = :friendid)
                OR (friend_id = :accountid AND account_id = :friendid)';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
			$sql->bindValue(':friendid', $friendid, PDO::PARAM_INT);
			$sql->execute();

			return true;
		} catch (PDOException $e) {
			throw new Exception('Error in addFriend: ' . $e->getMessage());
		}
	}

	public function friendList($accountid)
	{
		global $pdo;
		try {
			$sql = 'SELECT 
                    a.account_name AS friend_name,
                    a.pfp AS friend_pfp,
                    f.friend_id AS friend_id
                FROM friend_list f 
                LEFT JOIN accounts a ON f.friend_id = a.account_id
                WHERE f.account_id = :accountid AND f.status = "Accepted"';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
			$sql->execute();
			$result = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		} catch (PDOException $e) {
			throw new Exception('Error in friendList: ' . $e->getMessage());
		}
	}

	public function isFriend($accountid, $friendid)
	{
		global $pdo;
		try {
			$sql = 'SELECT * FROM friend_list WHERE (account_id = :accountid AND status = "Accepted" AND friend_id = :friendid)';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_INT);
			$sql->bindValue(':friendid', $friendid, PDO::PARAM_INT);
			$sql->execute();
			$count = $sql->fetchColumn();
			if ($count > 0) {
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			throw new Exception('Chyba v isFriend: ' . $e->getMessage());
		}
	}
	public function giveAdminRights($userid)
	{
		global $pdo;
		try {
			$sql = 'SELECT COUNT(*) FROM accounts WHERE account_id = :userid';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':userid', $userid, PDO::PARAM_INT);
			$sql->execute();
			$count = $sql->fetchColumn();
			if ($count > 0) {
				$sql = 'UPDATE accounts SET role = "admin" WHERE account_id = :userid';
				$sql = $pdo->prepare($sql);
				$sql->bindValue(':userid', $userid, PDO::PARAM_INT);
				$sql->execute();
				echo 'admin rights given';
			} else {
				echo 'Neexistuje user';
			}
		} catch (PDOException $e) {
			throw new Exception('Chyba v giveAdminRights: ' . $e->getMessage());
		}
	}
	public function removeAdminRights($userid){
		global $pdo;
		try {
			$sql = 'UPDATE accounts SET role = "user" WHERE account_id = :userid';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':userid', $userid, PDO::PARAM_INT);
			$sql->execute();
			echo 'admin rights removed';
		} catch (PDOException $e) {
			throw new Exception('Chyba v removeAdminRights: ' . $e->getMessage());
		}
	}
	public function activateUser($userid)
	{
		global $pdo;
		try {
			$sql = 'UPDATE accounts SET account_enabled = 1 WHERE account_id = :userid';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':userid', $userid, PDO::PARAM_INT);
			$sql->execute();
			echo 'user activated';
		} catch (PDOException $e) {
			throw new Exception('Chyba v activateUser: ' . $e->getMessage());
		}
	}
	public function checkAndEditPassword($accountid, $old_passwd, $new_passwd)
	{
		global $pdo;
		try {
			$sql = 'SELECT account_passwd FROM accounts WHERE account_id = :accountid';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':accountid', $accountid, PDO::PARAM_STR);
			$sql->execute();
			$result = $sql->fetch();
			if ($result != NULL && password_verify($old_passwd, $result['account_passwd'])) {
				$password = password_hash($new_passwd, PASSWORD_DEFAULT);
				$sql = 'UPDATE accounts SET account_passwd = :password WHERE account_id = :userid';
				$sql = $pdo->prepare($sql);
				$sql->bindValue(':password', $password, PDO::PARAM_STR);
				$sql->bindValue(':userid', $_SESSION['user_id'], PDO::PARAM_INT);
				$sql->execute();
				return true;
			} else {
				return false;
			}
		} catch (PDOException $e) {
			throw new Exception('Chyba v checkAndEditPassword: ' . $e->getMessage());
		}
	}
	public function getUserInfo($identifier)
	{
		global $pdo;
		$info = array();
		if (is_numeric($identifier)) {
			$query = 'SELECT account_id, account_name, account_reg_time, role, pfp FROM accounts WHERE account_id = :id';
			$sql = $pdo->prepare($query);
			$sql->bindParam(':id', $identifier, PDO::PARAM_INT);
		} else {
			$query = 'SELECT account_id, account_name, account_reg_time, role, pfp FROM accounts WHERE account_name = :name';
			$sql = $pdo->prepare($query);
			$sql->bindParam(':name', $identifier, PDO::PARAM_STR);
		}
		$sql->execute();
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$info[] = $row;
		}
		return $info;
	}
	public function removeComment($comment){
		global $pdo;
		try{
			$sql = 'DELETE FROM comments WHERE comment_id = :comment';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':comment', $comment, PDO::PARAM_INT);
			$sql->execute();
			return true;
		}catch(PDOException $e){
			throw new Exception('Chyba v deleteUser: ' . $e->getMessage());
		}
	}
}



class Login
{
	private $conn;
	public function __construct($conn)
	{
		$this->conn = $conn;
	}
	public function login($username, $password)
	{
		$sql = "SELECT * FROM accounts WHERE account_name=:username";
		$sql = $this->conn->prepare($sql);
		$sql->bindValue(':username', $username, PDO::PARAM_STR);
		$sql->execute();
		$result = $sql->fetch();

		if ($result) {
			if (password_verify($password, $result['account_passwd'])) {
				$_SESSION['user_id'] = $result['account_id'];
				$_SESSION['username'] = $result['account_name'];
				$_SESSION['role'] = $result['role'];
				$_SESSION['pfp'] = $result['pfp'];
				header('Location: index.php');
				return true;
			} else {
				return false;
			}
		} else {
			ini_set('display_errors', 1);
			echo '<p> Neprešla verifikacia</p>';
			return false;
		}
	}
	public function verifyCredentials($username, $password)
	{
		$sql = 'SELECT account_name, account_passwd FROM accounts WHERE account_name = :username';
		$sql = $this->conn->prepare($sql);
		$sql->bindValue(':username', $username, PDO::PARAM_STR);
		$sql->execute();
		$result = $sql->fetch();

		if ($result && password_verify($password, $result['account_passwd'])) {
			return true;
		} else {
			return false;
		}
	}


	public function verifyAccount($username)
	{
		$sql = 'SELECT account_enabled FROM accounts WHERE account_name = :username';
		$sql = $this->conn->prepare($sql);
		$sql->bindValue(':username', $username, PDO::PARAM_STR);
		$sql->execute();
		$result = $sql->fetch();
		return $result['account_enabled'];
	}
}
class Post
{
	public function __construct()
	{

	}

	public function createPost($userid, $description, $img)
	{
		global $pdo;
		try {
			$sql = 'INSERT INTO posts (account_id, description, img) VALUES (?, ?, ?)';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(1, $userid, PDO::PARAM_INT);
			$sql->bindValue(2, $description, PDO::PARAM_STR);
			$sql->bindValue(3, $img, PDO::PARAM_STR);
			$result = $sql->execute();
			return $result;
		} catch (Exception $e) {
			echo '<p> Error!' . $e . '</p>';
		}
	}
	public function updatePost($id, $description, $img)
	{
		global $pdo;
		try {
			$sql = 'UPDATE posts SET description = :description, image = :img WHERE id = :id';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':id', $id);
			$sql->bindValue(':description', $description);
			$sql->bindValue(':img', $img);
			$sql->execute();
		} catch (Exception $e) {
			echo '<p> Error!' . $e . '</p>';
		}
	}
	public function deletePost($id)
	{
		global $pdo;
		$sql = 'SELECT * FROM posts WHERE post_id=:id';
		$sql = $pdo->prepare($sql);
		$sql->bindValue(':id', $id, PDO::PARAM_INT);
		$sql->execute();

		$post = $sql->fetch(PDO::FETCH_ASSOC);

		if ($post) {
			$sql = 'DELETE FROM posts WHERE post_id=:id';
			$sql = $pdo->prepare($sql);
			$sql->bindValue(':id', $id, PDO::PARAM_INT);
			$sql->execute();
			return true;
		} else {
			return false;
		}
	}
	public function uploadPicture($file)
	{
		$targetDir = "../assets/uploads/";
		$fileName = uniqid() . '_' . basename($file['name']);

		$targetFilePath = $targetDir . $fileName;

		if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
			return $targetFilePath;
		} else {
			return false;
		}
	}
	public function getUserPosts($identifier)
	{
		global $pdo;
		$info = array();
		if (is_numeric($identifier)) {
			$query = 'SELECT posts.*, accounts.account_id, accounts.account_name FROM posts JOIN accounts ON posts.account_id = accounts.account_id WHERE posts.account_id = :id';
			$sql = $pdo->prepare($query);
			$sql->bindParam(':id', $identifier, PDO::PARAM_INT);
		} else {
			$query = 'SELECT posts.*, accounts.account_name FROM posts JOIN accounts ON posts.account_id = accounts.account_id WHERE accounts.account_name = :name';
			$sql = $pdo->prepare($query);
			$sql->bindParam(':name', $identifier, PDO::PARAM_STR);
		}
		$sql->execute();
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			$info[] = $row;
		}

		return $info;
	}

	public function uploadPfp($file)
	{
		global $pdo;
		$targetDir = "../assets/pfp/";
		$fileName = uniqid() . '_' . basename($file['name']);
		$targetFilePath = $targetDir . $fileName;

		if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
			try {
				$query = 'UPDATE accounts SET pfp = :filepath WHERE account_id = :id';
				$sql = $pdo->prepare($query);
				$sql->bindParam(':filepath', $targetFilePath, PDO::PARAM_STR);
				$sql->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
				$sql->execute() || die($pdo->errorInfo()[2]);
				$rowCount = $sql->rowCount();

				if ($rowCount > 0) {
					$_SESSION['pfp'] = $targetFilePath;
					return $targetFilePath;
				}
			} catch (PDOException $e) {
				echo "Error: " . $e->getMessage();
				return false;
			}
		} else {
			echo "Failed to move uploaded file.";
			return false;
		}
	}

	public function uploadComment($postid, $comment, $userid)
	{
		global $pdo;
		$sql = 'INSERT INTO comments (post_id, comment_text, account_id) VALUES (:postid, :comment, :userid)';
		try {
			$sql = $pdo->prepare($sql);
			$sql->bindParam(':postid', $postid, PDO::PARAM_INT);
			$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
			$sql->execute();
			return 1;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
	public function readComment($postid)
	{
		global $pdo;
		try {
			$sql = 'SELECT a.account_name, c.comment_text, c.comment_date, a.pfp
			FROM comments c
			JOIN accounts a ON c.account_id = a.account_id
			WHERE post_id = :postid';
			$sql = $pdo->prepare($sql);
			$sql->bindParam(':postid', $postid, PDO::PARAM_INT);
			$sql->execute();
			$result = $sql->fetchAll();
			return $result;
		} catch (PDOException $e) {
			echo 'Error: ' . $e->getMessage();
		}
	}
	public function fetchPostsFromDatabase()
	{
		global $pdo;
		$posts = array();
		$sql = "SELECT p.post_id, p.account_id, u.account_name, u.pfp, p.description, p.img, u.account_enabled
            FROM posts p
            INNER JOIN accounts u ON p.account_id = u.account_id
            ORDER BY p.post_id DESC";
		$sql = $pdo->prepare($sql);
		$sql->execute();
		if ($sql->rowCount() > 0) {
			while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
				$posts[] = $row;
			}
		}

		return $posts;
	}



}
