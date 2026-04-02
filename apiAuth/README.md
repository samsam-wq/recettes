# Lien

https://lafrontt.alwaysdata.net/

# Authentication API

Cette API permet d’authentifier un utilisateur avec son login et son mot de passe et de vérifier la validité d’un token JWT.

Protocole : HTTP  
Format : JSON  
Authentification : login / password  
Type de token : JWT (HS256)  
Expiration : 1 heure

## Endpoints

### POST /

Authentifie un utilisateur et retourne un token JWT.

Headers :  
Content-Type: application/json

Body :  
{
  "login": "string",
  "password": "string"
}

Paramètres :  
- login (string, requis) : identifiant de l’utilisateur  
- password (string, requis) : mot de passe de l’utilisateur

Réponses :

200 OK  
Authentification réussie :  
{
  "status_code": 200,
  "status_message": "[R401 REST AUTH] : Authentification OK",
  "data": "JWT_TOKEN"
}

400 Bad Request  
Données manquantes ou invalides :  
{
  "status_code": 400,
  "status_message": "Login et/ou mot de passe absent",
  "data": null
}

401 Unauthorized  
Identifiants invalides :  
{
  "status_code": 401,
  "status_message": "Login et/ou mot de passe erroné",
  "data": null
}

500 Internal Server Error  
Erreur serveur :  
{
  "status_code": 500,
  "status_message": "Erreur serveur",
  "data": null
}

### GET /

Vérifie la validité d’un token JWT. Le token doit être envoyé dans l’header Authorization : Bearer <TOKEN>.

Réponses :

200 OK  
Token valide :  
{
  "status_code": 200,
  "status_message": "[R401 REST AUTH] : Token Valide",
  "data": null
}

401 Unauthorized  
Token invalide ou expiré :  
{
  "status_code": 401,
  "status_message": "[R401 REST AUTH] : Unauthorized",
  "data": null
}

400 Bad Request  
Token absent ou header mal formé :  
{
  "status_code": 400,
  "status_message": "Syntaxe de la requête non conforme",
  "data": null
}

## JWT

Le token contient le payload suivant :  
{
  "login": "user_login",
  "role": "user_role",
  "exp": 1700000000
}

Champs :  
- login : identifiant utilisateur authentifié  
- role : rôle de l’utilisateur  
- exp : timestamp d’expiration (UNIX)

Sécurité :  
- Algorithme HS256  
- Clé de signature dérivée d’un secret côté serveur  
- Validité : 1 heure  

## Exemple d’appel

Authentification :  
curl -X POST http://localhost/ \
  -H "Content-Type: application/json" \
  -d '{"login":"admin","password":"1234"}'

Réponse :  
{
  "status_code": 200,
  "status_message": "[R401 REST AUTH] : Authentification OK",
  "data": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}

Vérification du token :  
curl -X GET http://localhost/ \
  -H "Authorization: Bearer JWT_TOKEN"

Réponse si valide :  
{
  "status_code": 200,
  "status_message": "[R401 REST AUTH] : Token Valide",
  "data": null
}

Réponse si invalide :  
{
  "status_code": 401,
  "status_message": "[R401 REST AUTH] : Unauthorized",
  "data": null
}