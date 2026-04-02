create database Recettes;

use Recettes;

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
   image VARCHAR(50) ,
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
