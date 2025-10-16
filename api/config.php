
<?php



// config.php
header("Access-Control-Allow-Origin: https://votre-app.vercel.app"); // Remplacez par votre URL Vercel
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Gérer les requêtes OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration de la base de données InfinityFree
define('DB_HOST', 'sql105.infinityfree.com'); // Trouvez ceci dans votre panel InfinityFree
define('DB_NAME', 'if0_40174223_XXX'); // Format: epiz + votre numéro + nom
define('DB_USER', 'if0_40174223'); // Même format
define('DB_PASS', 'jQrTSnjte9');

// Connexion à la base de données
function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de connexion à la base de données']);
        exit();
    }
}







/*


// backend/api/config.php

// Headers pour autoriser React à communiquer avec l'API
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Configuration de la base de données
$host = 'localhost';
$dbname = 'vos_achats_db';  // Le nom que vous avez choisi
$username = 'root';         // Votre utilisateur MySQL
$password = '';             // Votre mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
    exit;
}

*/

?>
