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

		if (!$this->isNameValid($name)) {
			throw new Exception('Invalid user name');
		}
		if (!$this->isPasswdValid($passwd)) {
			throw new Exception('Invalid password');
		}

		if (!is_null($this->getIdFromName($name))) {
			throw new Exception('User name not available');
		}

		if ($name == 'root') {
			$query = 'INSERT INTO social_network.accounts (account_name, account_passwd, account_enabled, role) VALUES (:name, :passwd, 1, "admin")';
			$hash = password_hash($passwd, PASSWORD_DEFAULT);
			$values = array(':name' => $name, ':passwd' => $hash);
		} else {
			$query = 'INSERT INTO social_network.accounts (account_name, account_passwd, account_enabled) VALUES (:name, :passwd, 1)';
			$hash = password_hash($passwd, PASSWORD_DEFAULT);
			$values = array(':name' => $name, ':passwd' => $hash);
		}


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

		$query = 'DELETE FROM social_network.accounts WHERE (account_id = :id)';

		$values = array(':id' => $id);

		try {
			$res = $pdo->prepare($query);
			$res->execute($values);
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

		if (!$this->isNameValid($name)) {
			throw new Exception('Invalid user name');
		}

		if (!$this->isPasswdValid($passwd)) {
			throw new Exception('Invalid password');
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

	public function isNameValid(string $name): bool
	{
		$valid = TRUE;

		$len = mb_strlen($name);

		if (($len < 4) || ($len > 16)) {
			$valid = FALSE;
		}


		return $valid;
	}

	public function isPasswdValid(string $passwd): bool
	{
		$valid = TRUE;
		$len = mb_strlen($passwd);

		if (($len < 8) || ($len > 16)) {
			$valid = FALSE;
		}

		return $valid;
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
		session_unset();
		session_destroy();
		header('Location: login.php');
	}



	public function getIdFromName(string $name): ?int
	{
		global $pdo;

		if (!$this->isNameValid($name)) {
			throw new Exception('Invalid user name');
		}

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
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch();

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
}

class Post
{
	private $id;
	private $conn;
	private $postManager;
	public function __construct($conn)
	{
		$this->conn = $conn;
	}

	public function createPost($userid, $description, $img)
	{
		try {
			$sql = 'INSERT INTO posts (account_id, description, img) VALUES (?, ?, ?)';
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(1, $userid, PDO::PARAM_INT);
			$stmt->bindValue(2, $description, PDO::PARAM_STR);
			$stmt->bindValue(3, $img, PDO::PARAM_STR);
			$result = $stmt->execute();
			return $result;
		} catch (Exception $e) {
			echo '<p> Error!' . $e . '</p>';
		}
	}
	public function updatePost($id, $description, $img)
	{
		try {
			$sql = 'UPDATE posts SET description = :description, image = :img WHERE id = :id';
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', $id);
			$stmt->bindValue(':description', $description);
			$stmt->bindValue(':img', $img);
			$stmt->execute();
		} catch (Exception $e) {
			echo '<p> Error!' . $e . '</p>';
		}
	}
	public function deletePost($id)
	{
		$sql = 'SELECT * FROM posts WHERE post_id=:id';
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		$post = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($post) {
			$sql = 'DELETE FROM posts WHERE post_id=:id';
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			return true;
		} else {
			return false;
		}
	}
	public function uploadPicture($file)
	{
		$targetDir = "uploads/";
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
		$info = array();
		if (is_numeric($identifier)) {
			$query = 'SELECT posts.*, accounts.account_name FROM posts JOIN accounts ON posts.account_id = accounts.account_id WHERE posts.account_id = :id';
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':id', $identifier, PDO::PARAM_INT);
		} else {
			$query = 'SELECT posts.*, accounts.account_name FROM posts JOIN accounts ON posts.account_id = accounts.account_id WHERE accounts.account_name = :name';
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':name', $identifier, PDO::PARAM_STR);
		}
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$info[] = $row;
		}

		return $info;
	}

	public function uploadPfp($file)
	{
		$targetDir = "pfp/";
		$fileName = uniqid() . '_' . basename($file['name']);
		$targetFilePath = $targetDir . $fileName;

		if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
			try {
				$query = 'UPDATE accounts SET pfp = :filepath WHERE account_id = :id';
				$stmt = $this->conn->prepare($query);
				$stmt->bindParam(':filepath', $targetFilePath, PDO::PARAM_STR);
				$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
				$stmt->execute() || die($this->conn->errorInfo()[2]);
				$rowCount = $stmt->rowCount();

				if ($rowCount > 0) {
					return $targetFilePath;
				}
			} catch (PDOException $e) {
				echo "Error: " . $e->getMessage(); // Debug output
				return false;
			}
		} else {
			echo "Failed to move uploaded file."; // Debug output
			return false;
		}
	}

}

?>