<?php
// cors.php - À inclure en PREMIER dans chaque endpoint

// Liste des origines autorisées
$allowed_origins = [
    'https://gpower-projet-frontend.vercel.app',
    'http://localhost:3000',     // Pour développement local
    'http://192.168.1.80:3000',  // Pour développement local réseau
];

// Récupérer l'origine de la requête
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Vérifier si l'origine est autorisée
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Par défaut, autoriser la production
    header("Access-Control-Allow-Origin: https://gpower-projet-frontend.vercel.app");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 86400"); // Cache preflight 24h
header("Content-Type: application/json; charset=UTF-8");

// Gérer les requêtes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}
?>