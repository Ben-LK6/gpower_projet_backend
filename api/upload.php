<?php
// upload.php
// CORS EN PREMIER !
require_once 'cors.php';
require_once 'config.php';

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
        
        // Chemin relatif au fichier actuel
        $uploadDir = __DIR__ . '/uploads/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (!is_writable($uploadDir)) {
            throw new Exception('Dossier uploads non accessible en écriture');
        }
        
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            if (!file_exists($filePath)) {
                throw new Exception('Fichier créé mais introuvable');
            }
            
            // URL accessible
            $imageUrl = "https://gpower.ct.ws/uploads/" . $fileName;
            
            echo json_encode([
                "success" => true,
                "message" => "Image uploadée avec succès",
                "imageUrl" => $imageUrl
            ]);
        } else {
            $error = error_get_last();
            throw new Exception('Erreur upload: ' . ($error['message'] ?? 'Inconnue'));
        }
        
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
}
?>