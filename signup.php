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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $email = $_POST['E-mail'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Vérification des mots de passe
    if ($password !== $confirmpassword) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Connexion à la base de données
    $db = new Database();
    $conn = $db->getConnection();

    // Préparer la requête SQL
    $stmt = $conn->prepare("INSERT INTO login (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);




    // Exécuter la requête et vérifier le résultat
    if ($stmt->execute()) {
        echo "Inscription réussie !";
        header("Location: succes.html");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
    $db->closeConnection();
}
?>
