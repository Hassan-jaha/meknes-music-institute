# 🏛️ Institut de Musique de Meknès - Documentation Technique

Ce projet est une plateforme de gestion multilingue (Français, Arabe, Anglais, Amazigh) pour l'Institut de Musique de Meknès.

## 📋 Prérequis
- Serveur PHP (version 8.0 ou supérieure)
- Serveur MySQL/MariaDB
- Extension PHP PDO active
- Extension PHP GD (pour le redimensionnement des images)

## 🛠️ Installation

### 1. Configuration des fichiers
Copiez l'intégralité du dossier du projet dans le répertoire de votre serveur web (`www` ou `htdocs`).

### 2. Configuration de la Base de Données
1. Créez une base de données nommée `institut_musique` via phpMyAdmin ou en ligne de commande.
2. Importez le fichier `database.sql` fourni à la racine du projet.
3. Modifiez le fichier `config/database.php` pour ajuster vos identifiants de connexion :
   ```php
   $host = 'localhost';
   $db   = 'institut_musique';
   $user = 'votre_utilisateur';
   $pass = 'votre_mot_de_passe';
   ```

### 3. Droits d'accès
Assurez-vous que le dossier `public/uploads/` dispose des droits d'écriture pour permettre l'ajout d'images via l'espace administration.

## 🔐 Administration
- **URL** : `http://votre-site.com/admin/login.php`
- **Identifiant par défaut** : `admin`
- **Mot de passe** : `admin123`

## 🚀 Performances & Sécurité
- **Cache** : Les ressources statiques disposent d'instructions de mise en cache navigateur.
- **Images** : Redimensionnement automatique à l'upload pour optimiser le poids des pages.
- **Sécurité** : 
  - Mots de passe hashés avec Bcrypt.
  - Protection contre les injections SQL (Requêtes préparées PDO).
  - Protection XSS (Échappement des sorties).

---
© 2026 Institut de Musique de Meknès - Tous droits réservés.
