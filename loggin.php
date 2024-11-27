<?php

class Database {
    private $host = "localhost";
    private $dbusername = "root";
    private $dbpassword = "";
    private $dbname = "auth";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->dbusername, $this->dbpassword, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

class Auth {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM login WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return true;
        } else {
            return false;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();
    $auth = new Auth($db);

    if ($auth->authenticate($username, $password)) {
        header("Location: Home.php");
        exit();
    } else {
        header("Location: failed.html");
        exit();
    }

    $db->closeConnection();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
      <div class="container">
        <form id="loginForm" action="loggin.php" method="post">
           
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" id="username" name="username" placeholder="Username" required class="form-control">
                <i class='bx bxs-user'></i>
            </div>
            <div class="input-box">
                <input type="password" id="password" name="password" placeholder="Password" class="form-control"required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button type="submit" class="botona">Login</button>
            <button type="button" class="botona" onclick="window.location.href = 'signin.html';" id="bobo2">Sign In</button>
        </form>
    </div>
</body>
</html>
