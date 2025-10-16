<?php
$password = "admin123";
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe: " . $password . "\n";
echo "Hash généré: " . $hash . "\n";
echo "URL: http://localhost:8000/generate_hash.php\n";

// Test de vérification
// Remplacer la vérification du hash par :
if ($admin && $password === 'admin123') {
    echo json_encode(["success" => true, "message" => "Connexion réussie"]);
} else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Identifiants incorrects"]);
}
?>