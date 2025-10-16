<?php
require_once 'config.php';

// Headers CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Gérer la pré-requête OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            getProduct($pdo, $_GET['id']);
        } else {
            getProducts($pdo);
        }
        break;
    
    case 'POST':
        addProduct($pdo);
        break;
    
    case 'PUT':
        updateProduct($pdo);
        break;
    
    case 'DELETE':
        deleteProduct($pdo);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(["error" => "Méthode non autorisée"]);
        break;
}

function getProducts($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT p.*, c.nom as categorie_nom, c.couleur as categorie_couleur 
            FROM produits p 
            LEFT JOIN categories c ON p.category_id = c.id 
            ORDER BY p.date_creation DESC
        ");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function getProduct($pdo, $id) {
    try {
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom as categorie_nom, c.couleur as categorie_couleur 
            FROM produits p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Produit non trouvé"]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function addProduct($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $nom = $input['nom'] ?? '';
        $description = $input['description'] ?? '';
        $prix = $input['prix'] ?? 0;
        $image = $input['image'] ?? '';
        $category_id = $input['category_id'] ?? 5; // Default to "Autre"
        
        $stmt = $pdo->prepare("INSERT INTO produits (nom, description, prix, image, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $prix, $image, $category_id]);
        
        $newId = $pdo->lastInsertId();
        echo json_encode(["success" => true, "message" => "Produit ajouté", "id" => $newId]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function updateProduct($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        
        $nom = $input['nom'] ?? '';
        $description = $input['description'] ?? '';
        $prix = $input['prix'] ?? 0;
        $image = $input['image'] ?? '';
        $category_id = $input['category_id'] ?? 5;
        
        $stmt = $pdo->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, image = ?, category_id = ? WHERE id = ?");
        $stmt->execute([$nom, $description, $prix, $image, $category_id, $id]);
        
        echo json_encode(["success" => true, "message" => "Produit modifié"]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}

function deleteProduct($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;
        
        $stmt = $pdo->prepare("DELETE FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(["success" => true, "message" => "Produit supprimé"]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Erreur : " . $e->getMessage()]);
    }
}
?>