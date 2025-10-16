<?php
// backend/api/upload.php
require_once 'config.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Aucun fichier uploadé');
        }

        $file = $_FILES['image'];
        
        // Vérifier le type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception('Type non autorisé');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            throw new Exception('Fichier trop volumineux (max 2MB)');
        }

        // CRÉATION DU DOSSIER SI IL N'EXISTE PAS
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // VÉRIFIER QUE LE DOSSIER EST ACCESSIBLE EN ÉCRITURE
        if (!is_writable($uploadDir)) {
            throw new Exception('Dossier uploads non accessible en écriture');
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;

        // DEBUG - Log pour voir ce qui se passe
        error_log("Tentative d'upload: " . $filePath);
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // VÉRIFIER QUE LE FICHIER A BIEN ÉTÉ CRÉÉ
            if (!file_exists($filePath)) {
                throw new Exception('Fichier créé mais introuvable');
            }
            
            $imageUrl = "http://localhost:8000/uploads/" . $fileName;
            
            echo json_encode([
                "success" => true,
                "message" => "Image uploadée avec succès",
                "imageUrl" => $imageUrl,
                "debug" => "Fichier: " . $fileName
            ]);
        } else {
            // ERREUR DÉTAILLÉE
            $error = error_get_last();
            throw new Exception('Erreur move_uploaded_file: ' . ($error['message'] ?? 'Inconnue'));
        }

    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage(),
            "debug" => "Dossier: " . (is_dir($uploadDir) ? 'existe' : 'n existe pas') . 
                      ", Writable: " . (is_writable($uploadDir) ? 'oui' : 'non')
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>