-- Script d'initialisation de la base de données locale
CREATE DATABASE IF NOT EXISTS vos_achats_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vos_achats_db;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    stock INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Insérer quelques catégories de test
INSERT INTO categories (nom, description) VALUES 
('Électronique', 'Appareils électroniques et gadgets'),
('Vêtements', 'Mode et accessoires'),
('Maison', 'Articles pour la maison et décoration');

-- Insérer quelques produits de test
INSERT INTO produits (nom, description, prix, category_id, stock) VALUES 
('Smartphone', 'Téléphone intelligent dernière génération', 599.99, 1, 10),
('T-shirt', 'T-shirt en coton bio', 29.99, 2, 25),
('Lampe de bureau', 'Lampe LED ajustable', 49.99, 3, 15);