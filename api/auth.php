<?php
// auth.php
// CORS EN PREMIER !
require_once 'cors.php';
require_once 'config.php';

$pdo = getDBConnection();

// Créer la table admin si elle n'existe pas
$pdo->exec("CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Créer un admin par défaut si aucun n'existe
$stmt = $pdo->query("SELECT COUNT(*) as count FROM admin");
if ($stmt->fetch()['count'] == 0) {
    $pdo->prepare("INSERT INTO admin (username, password_hash) VALUES (?, ?)")
        ->execute(['admin', 'admin123']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && $password === $admin['password_hash']) {
        echo json_encode(["success" => true, "message" => "Connexion réussie"]);
    } else {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>