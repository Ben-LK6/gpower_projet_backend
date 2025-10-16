<?php
require_once 'config.php';

// Headers CORS IMPORTANTS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Gérer la pré-requête OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "JSON invalide"]);
        exit;
    }
    
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    error_log("Tentative de connexion: $username");
    
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // VÉRIFICATION SIMPLIFIÉE (mot de passe en clair)
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