create database Recettes;

use Recettes;

drop table if exists Utilise;
drop table if exists Utilise_R;
drop table if exists Contient;
drop table if exists Contient_R;
drop table if exists Noter;
drop table if exists Ustensiles;
drop table if exists Ingredient;
drop table if exists Etape;
drop table if exists Recette;

CREATE TABLE Ustensiles(
   Id_Ustensiles INT AUTO_INCREMENT,
   nom VARCHAR(50) ,
   PRIMARY KEY(Id_Ustensiles)
);

CREATE TABLE Ingredient(
   Id_Ingredient INT AUTO_INCREMENT,
   prix VARCHAR(50) ,
   image VARCHAR(50) ,
   nom VARCHAR(50) ,
   PRIMARY KEY(Id_Ingredient)
);

CREATE TABLE Recette(
   Id_Recette INT AUTO_INCREMENT,
   nom VARCHAR(50) ,
   duree INT,
   categorie VARCHAR(50) ,
   image VARCHAR(250) ,
   groupe INT ,
   PRIMARY KEY(Id_Recette)
);

CREATE TABLE Etape(
   Id_Recette INT,
   numero INT,
   titre VARCHAR(50) ,
   contenu TEXT,
   PRIMARY KEY(Id_Recette, numero),
   FOREIGN KEY(Id_Recette) REFERENCES Recette(Id_Recette)
);

CREATE TABLE Contient(
   Id_Ingredient INT,
   Id_Recette INT,
   numero INT,
   quantite INT,
   unite VARCHAR(50) ,
   PRIMARY KEY(Id_Ingredient, Id_Recette, numero),
   FOREIGN KEY(Id_Ingredient) REFERENCES Ingredient(Id_Ingredient),
   FOREIGN KEY(Id_Recette, numero) REFERENCES Etape(Id_Recette, numero)
);

CREATE TABLE Utilise(
   Id_Ustensiles INT,
   Id_Recette INT,
   numero INT,
   quantite INT,
   PRIMARY KEY(Id_Ustensiles, Id_Recette, numero),
   FOREIGN KEY(Id_Ustensiles) REFERENCES Ustensiles(Id_Ustensiles),
   FOREIGN KEY(Id_Recette, numero) REFERENCES Etape(Id_Recette, numero)
);

CREATE TABLE Noter(
   Id_Recette INT,
   login VARCHAR(50) ,
   note INT,
   specialite BOOLEAN,
   favori BOOLEAN,
   PRIMARY KEY(Id_Recette, login),
   FOREIGN KEY(Id_Recette) REFERENCES Recette(Id_Recette)
);

CREATE TABLE Contient_R(
   Id_Ingredient INT,
   Id_Recette INT,
   quantite INT,
   unite VARCHAR(50) ,
   PRIMARY KEY(Id_Ingredient, Id_Recette),
   FOREIGN KEY(Id_Ingredient) REFERENCES Ingredient(Id_Ingredient),
   FOREIGN KEY(Id_Recette) REFERENCES Recette(Id_Recette)
);

CREATE TABLE Utilise_R(
   Id_Ustensiles INT,
   Id_Recette INT,
   quantite INT,
   PRIMARY KEY(Id_Ustensiles, Id_Recette),
   FOREIGN KEY(Id_Ustensiles) REFERENCES Ustensiles(Id_Ustensiles),
   FOREIGN KEY(Id_Recette) REFERENCES Recette(Id_Recette)
);

-- =============================================
-- JEU DE DONNÉES - Base Recettes
-- =============================================

-- ---------------------------------------------
-- Ustensiles
-- ---------------------------------------------
INSERT INTO Ustensiles (nom) VALUES
    ('Poêle'),
    ('Casserole'),
    ('Four'),
    ('Couteau de chef'),
    ('Planche à découper'),
    ('Saladier'),
    ('Fouet'),
    ('Mixeur'),
    ('Spatule'),
    ('Plat à gratin');

-- ---------------------------------------------
-- Ingrédients
-- ---------------------------------------------
INSERT INTO Ingredient (prix, image, nom) VALUES
    ('1.20', 'oignon.jpg', 'Oignon'),
    ('2.50', 'poulet.jpg', 'Blanc de poulet'),
    ('0.80', 'ail.jpg', 'Ail'),
    ('1.50', 'tomate.jpg', 'Tomate'),
    ('3.00', 'pate_feuilletee.jpg', 'Pâte feuilletée'),
    ('4.50', 'gruyere.jpg', 'Gruyère râpé'),
    ('1.00', 'oeuf.jpg', 'Œuf'),
    ('0.90', 'farine.jpg', 'Farine'),
    ('1.20', 'beurre.jpg', 'Beurre'),
    ('0.60', 'lait.jpg', 'Lait'),
    ('2.80', 'lardons.jpg', 'Lardons'),
    ('0.50', 'sel.jpg', 'Sel'),
    ('0.50', 'poivre.jpg', 'Poivre'),
    ('1.80', 'pates.jpg', 'Pâtes'),
    ('3.50', 'saumon.jpg', 'Pavé de saumon'),
    ('1.10', 'citron.jpg', 'Citron'),
    ('2.00', 'creme.jpg', 'Crème fraîche'),
    ('0.70', 'carotte.jpg', 'Carotte'),
    ('1.30', 'courgette.jpg', 'Courgette'),
    ('2.20', 'champignon.jpg', 'Champignons de Paris');

-- ---------------------------------------------
-- Recettes
-- ---------------------------------------------
INSERT INTO Recette (nom, duree, categorie, image, groupe) VALUES
    ('Poulet rôti aux herbes',     60,  'Plat', 'https://www.apero-bordeaux.fr/wp-content/uploads/2024/02/20240216_65cfa1ce1fa54.jpg.webp',     1),
    ('Quiche lorraine',            50,  'Entree',         'quiche.jpg',          1),
    ('Pâtes carbonara',            20,  'Plat', 'carbonara.jpg',       1),
    ('Saumon citron-crème',        25,  'Plat', 'saumon.jpg',          1),
    ('Ratatouille',                45,  'Plat', 'ratatouille.jpg',     1),
    ('Omelette aux champignons',   15,  'Plat', 'omelette.jpg',        1);

-- ---------------------------------------------
-- Etapes
-- ---------------------------------------------

-- Poulet rôti (Id_Recette = 1)
INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES
    (1, 1, 'Préparation', 'Préchauffer le four à 200°C. Éplucher et écraser l''ail.'),
    (1, 2, 'Assaisonnement', 'Badigeonner le poulet de beurre, saler, poivrer et frotter avec l''ail.'),
    (1, 3, 'Cuisson', 'Enfourner le poulet 50 minutes en l''arrosant régulièrement avec son jus.');

-- Quiche lorraine (Id_Recette = 2)
INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES
    (2, 1, 'Foncer le moule', 'Étaler la pâte feuilletée dans un moule à tarte et piquer le fond à la fourchette.'),
    (2, 2, 'Préparer l''appareil', 'Battre les œufs avec la crème fraîche, le sel et le poivre. Ajouter les lardons et le gruyère.'),
    (2, 3, 'Cuisson', 'Verser l''appareil sur le fond de tarte. Enfourner 35 minutes à 180°C.');

-- Pâtes carbonara (Id_Recette = 3)
INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES
    (3, 1, 'Cuisson des pâtes', 'Faire cuire les pâtes al dente dans une grande casserole d''eau bouillante salée.'),
    (3, 2, 'Sauce', 'Faire revenir les lardons à la poêle. Dans un saladier, battre les œufs avec le gruyère.'),
    (3, 3, 'Assemblage', 'Hors du feu, mélanger les pâtes égouttées avec les lardons et la sauce œuf-fromage. Poivrer.');

-- Saumon citron-crème (Id_Recette = 4)
INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES
    (4, 1, 'Préparation', 'Presser le citron. Saler et poivrer les pavés de saumon.'),
    (4, 2, 'Cuisson du saumon', 'Faire dorer les pavés 4 minutes de chaque côté dans une poêle avec un peu de beurre.'),
    (4, 3, 'Sauce', 'Déglacer avec le jus de citron, ajouter la crème fraîche et laisser réduire 2 minutes.');

-- Ratatouille (Id_Recette = 5)
INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES
    (5, 1, 'Découpe', 'Couper les courgettes, tomates et oignons en rondelles. Émincer l''ail.'),
    (5, 2, 'Cuisson des légumes', 'Faire revenir les oignons et l''ail à la poêle, puis ajouter les courgettes et tomates.'),
    (5, 3, 'Mijotage', 'Saler, poivrer et laisser mijoter à feu doux 30 minutes en remuant régulièrement.');

-- Omelette aux champignons (Id_Recette = 6)
INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES
    (6, 1, 'Préparation', 'Nettoyer et émincer les champignons. Battre les œufs avec sel et poivre.'),
    (6, 2, 'Cuisson champignons', 'Faire sauter les champignons au beurre dans une poêle jusqu''à évaporation de l''eau.'),
    (6, 3, 'Cuisson omelette', 'Verser les œufs sur les champignons. Cuire à feu moyen en repliant l''omelette sur elle-même.');

-- ---------------------------------------------
-- Contient (ingrédients par étape)
-- ---------------------------------------------

-- Poulet rôti
INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES
    (3,  1, 1, 4,   'gousses'),   -- Ail - étape 1
    (9,  1, 2, 30,  'g'),         -- Beurre - étape 2
    (12, 1, 2, 1,   'pincée'),    -- Sel - étape 2
    (13, 1, 2, 1,   'pincée'),    -- Poivre - étape 2
    (2,  1, 3, 1,   'pièce');     -- Blanc de poulet - étape 3

-- Quiche lorraine
INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES
    (5,  2, 1, 1,   'pièce'),     -- Pâte feuilletée - étape 1
    (7,  2, 2, 3,   'pièces'),    -- Œufs - étape 2
    (17, 2, 2, 200, 'ml'),        -- Crème fraîche - étape 2
    (11, 2, 2, 150, 'g'),         -- Lardons - étape 2
    (6,  2, 2, 100, 'g');         -- Gruyère - étape 2

-- Pâtes carbonara
INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES
    (14, 3, 1, 400, 'g'),         -- Pâtes - étape 1
    (12, 3, 1, 1,   'pincée'),    -- Sel - étape 1
    (11, 3, 2, 200, 'g'),         -- Lardons - étape 2
    (7,  3, 2, 3,   'pièces'),    -- Œufs - étape 2
    (6,  3, 2, 80,  'g'),         -- Gruyère - étape 2
    (13, 3, 3, 1,   'pincée');    -- Poivre - étape 3

-- Saumon citron-crème
INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES
    (16, 4, 1, 1,   'pièce'),     -- Citron - étape 1
    (12, 4, 1, 1,   'pincée'),    -- Sel - étape 1
    (15, 4, 2, 2,   'pièces'),    -- Saumon - étape 2
    (9,  4, 2, 20,  'g'),         -- Beurre - étape 2
    (17, 4, 3, 150, 'ml');        -- Crème fraîche - étape 3

-- Ratatouille
INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES
    (19, 5, 1, 2,   'pièces'),    -- Courgette - étape 1
    (4,  5, 1, 3,   'pièces'),    -- Tomate - étape 1
    (1,  5, 1, 2,   'pièces'),    -- Oignon - étape 1
    (3,  5, 1, 2,   'gousses'),   -- Ail - étape 1
    (12, 5, 3, 1,   'pincée'),    -- Sel - étape 3
    (13, 5, 3, 1,   'pincée');    -- Poivre - étape 3

-- Omelette aux champignons
INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES
    (20, 6, 1, 200, 'g'),         -- Champignons - étape 1
    (7,  6, 1, 4,   'pièces'),    -- Œufs - étape 1
    (9,  6, 2, 20,  'g'),         -- Beurre - étape 2
    (12, 6, 3, 1,   'pincée'),    -- Sel - étape 3
    (13, 6, 3, 1,   'pincée');    -- Poivre - étape 3

-- ---------------------------------------------
-- Contient_R (ingrédients globaux par recette)
-- ---------------------------------------------
INSERT INTO Contient_R (Id_Ingredient, Id_Recette, quantite, unite) VALUES
    -- Poulet rôti
    (2,  1, 1,   'pièce'),
    (3,  1, 4,   'gousses'),
    (9,  1, 30,  'g'),
    (12, 1, 1,   'pincée'),
    (13, 1, 1,   'pincée'),
    -- Quiche lorraine
    (5,  2, 1,   'pièce'),
    (7,  2, 3,   'pièces'),
    (17, 2, 200, 'ml'),
    (11, 2, 150, 'g'),
    (6,  2, 100, 'g'),
    -- Pâtes carbonara
    (14, 3, 400, 'g'),
    (11, 3, 200, 'g'),
    (7,  3, 3,   'pièces'),
    (6,  3, 80,  'g'),
    (12, 3, 1,   'pincée'),
    (13, 3, 1,   'pincée'),
    -- Saumon citron-crème
    (15, 4, 2,   'pièces'),
    (16, 4, 1,   'pièce'),
    (17, 4, 150, 'ml'),
    (9,  4, 20,  'g'),
    (12, 4, 1,   'pincée'),
    -- Ratatouille
    (19, 5, 2,   'pièces'),
    (4,  5, 3,   'pièces'),
    (1,  5, 2,   'pièces'),
    (3,  5, 2,   'gousses'),
    (12, 5, 1,   'pincée'),
    (13, 5, 1,   'pincée'),
    -- Omelette aux champignons
    (20, 6, 200, 'g'),
    (7,  6, 4,   'pièces'),
    (9,  6, 20,  'g'),
    (12, 6, 1,   'pincée'),
    (13, 6, 1,   'pincée');

-- ---------------------------------------------
-- Utilise (ustensiles par étape)
-- ---------------------------------------------
INSERT INTO Utilise (Id_Ustensiles, Id_Recette, numero, quantite) VALUES
    -- Poulet rôti
    (3,  1, 1, 1),   -- Four - étape 1
    (3,  1, 3, 1),   -- Four - étape 3
    -- Quiche lorraine
    (7,  2, 2, 1),   -- Fouet - étape 2
    (3,  2, 3, 1),   -- Four - étape 3
    -- Pâtes carbonara
    (2,  3, 1, 1),   -- Casserole - étape 1
    (1,  3, 2, 1),   -- Poêle - étape 2
    (6,  3, 2, 1),   -- Saladier - étape 2
    -- Saumon citron-crème
    (1,  4, 2, 1),   -- Poêle - étape 2
    -- Ratatouille
    (4,  5, 1, 1),   -- Couteau de chef - étape 1
    (5,  5, 1, 1),   -- Planche à découper - étape 1
    (1,  5, 2, 1),   -- Poêle - étape 2
    -- Omelette aux champignons
    (1,  6, 2, 1),   -- Poêle - étape 2
    (1,  6, 3, 1);   -- Poêle - étape 3

-- ---------------------------------------------
-- Utilise_R (ustensiles globaux par recette)
-- ---------------------------------------------
INSERT INTO Utilise_R (Id_Ustensiles, Id_Recette, quantite) VALUES
    -- Poulet rôti
    (3,  1, 1),   -- Four
    -- Quiche lorraine
    (7,  2, 1),   -- Fouet
    (3,  2, 1),   -- Four
    -- Pâtes carbonara
    (2,  3, 1),   -- Casserole
    (1,  3, 1),   -- Poêle
    (6,  3, 1),   -- Saladier
    -- Saumon citron-crème
    (1,  4, 1),   -- Poêle
    -- Ratatouille
    (4,  5, 1),   -- Couteau de chef
    (5,  5, 1),   -- Planche à découper
    (1,  5, 1),   -- Poêle
    -- Omelette aux champignons
    (1,  6, 1);   -- Poêle

-- ---------------------------------------------
-- Noter
-- ---------------------------------------------
INSERT INTO Noter (Id_Recette, login, note, specialite, favori) VALUES
    (1, 'Anna',   5, true,  true),
    (1, 'Sam',     4, false, false),
    (2, 'Anna',   4, false, true),
    (2, 'Sam',     5, true,  true),
    (3, 'Anna',   4, false, true),
    (4, 'Sam',     3, false, false),
    (5, 'Anna',   5, true,  true),
    (5, 'Sam',     4, false, false),
    (6, 'Anna',   4, false, true);

commit;