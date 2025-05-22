CREATE DATABASE evenements_db;
USE evenements_db;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255)
);

CREATE TABLE organisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    telephone VARCHAR(20),
    adresse TEXT,
    date_inscription DATE,
    etat INT DEFAULT 1 -- 1=En attente, 2=Accepté, 0=Refusé
);

CREATE TABLE participant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    telephone VARCHAR(20),
    date_inscription DATE,
    mot_de_passe VARCHAR(255),
    etat INT DEFAULT 1
);

CREATE TABLE categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    icone VARCHAR(100)
);

CREATE TABLE evenement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    date_event DATETIME,
    lieu VARCHAR(255),
    id_organisateur INT,
    id_categorie INT,
    etat INT DEFAULT 1, -- 1=En attente, 2=Accepté, 0=Refusé, 3=Terminé
    FOREIGN KEY (id_organisateur) REFERENCES organisateur(id),
    FOREIGN KEY (id_categorie) REFERENCES categorie(id)
);

CREATE TABLE participation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_participant INT,
    id_evenement INT,
    date_inscription DATE,
    FOREIGN KEY (id_participant) REFERENCES participant(id),
    FOREIGN KEY (id_evenement) REFERENCES evenement(id)
);

-- Données de test
INSERT INTO admin (nom, prenom, email, mot_de_passe) VALUES ('Admin', 'Test', 'admin@test.com', '$2y$10$YOUR_HASHED_PASSWORD');
INSERT INTO categorie (nom, icone) VALUES ('Concert', 'fa-music'), ('Sport', 'fa-futbol'), ('Conférence', 'fa-chalkboard-teacher');