<?php
// config.php
// NE PAS METTRE DE HEADERS ICI ! C'est le rôle de cors.php

define('DB_HOST', 'sql105.infinityfree.com');
define('DB_NAME', 'if0_40174223_gpower');
define('DB_USER', 'if0_40174223');
define('DB_PASS', 'jQrTSnjte9');

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
?>