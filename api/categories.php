<?php
require_once 'config.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getCategories($pdo);
        break;
    
    case 'POST':
        addCategory($pdo);
        break;
    
    case 'PUT':
        updateCategory($pdo);
        break;
    
    case 'DELETE':
        deleteCategory($pdo);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
        break;
}

function getCategories($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY nom");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categories);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function addCategory($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $nom = $input['nom'] ?? '';
        $description = $input['description'] ?? '';
        $couleur = $input['couleur'] ?? '#25D366';
        
        $stmt = $pdo->prepare("INSERT INTO categories (nom, description, couleur) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $description, $couleur]);
        
        $newId = $pdo->lastInsertId();
        echo json_encode(["success" => true, "message" => "Catégorie ajoutée", "id" => $newId]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function updateCategory($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $id = $input['id'] ?? 0;
        $nom = $input['nom'] ?? '';
        $description = $input['description'] ?? '';
        $couleur = $input['couleur'] ?? '#25D366';
        
        $stmt = $pdo->prepare("UPDATE categories SET nom = ?, description = ?, couleur = ? WHERE id = ?");
        $stmt->execute([$nom, $description, $couleur, $id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Catégorie modifiée avec succès"]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Catégorie non trouvée"]);
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function deleteCategory($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        
        // Vérifier s'il y a des produits dans cette catégorie
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM produits WHERE category_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Impossible de supprimer : catégorie contient des produits"]);
            return;
        }
        
        // Supprimer la catégorie
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Catégorie supprimée"]);
        } else {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Catégorie non trouvée"]);
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}
?>