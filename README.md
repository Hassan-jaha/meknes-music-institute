# 🏛️ Conservatoire Municipal de Musique de Meknès

Bienvenue sur le dépôt officiel du portail numérique du **Conservatoire Municipal de Musique de Meknès**. Cette plateforme moderne offre une gestion complète des actualités, des annonces et d'une galerie multimédia, avec un support multilingue intégral.

## 🌟 Fonctionnalités

- 🌍 **Multilingue** : Support complet du Français, Arabe (RTL), Anglais et Amazigh (Tifinagh).
- 📰 **Gestion de Contenu (CMS)** : Espace d'administration sécurisé pour gérer les actualités, les annonces épinglées et la galerie.
- 📸 **Optimisation d'Images** : Conversion automatique en format **WebP** et redimensionnement intelligent (1200x800) pour une performance maximale.
- 🎬 **Intégration YouTube** : Lecteur d'ambiance intégré via l'API IFrame YouTube avec gestion intelligente du volume.
- 📱 **Design Responsive** : Interface premium et fluide adaptée à tous les écrans (Mobile, Tablette, Desktop).

## 🛠️ Technologies Utilisées

- **Backend** : PHP 8.1+ (Architecture modulaire)
- **Base de Données** : MySQL (Hébergé sur Railway)
- **Frontend** : Vanilla JS, CSS3 (Modern UI/UX), Lucide Icons (SVG)
- **Infrastructure** : Railway App (Déploiement continu), Volumes persistants pour le stockage.

## ⚙️ Configuration & Installation

Le projet utilise des **Variables d'Environnement** pour sécuriser les accès à la base de données.

### Variables d'Environnement (Railway)
Assurez-vous que les variables suivantes sont configurées dans votre tableau de bord Railway :
- `MYSQLHOST` : L'hôte de votre base de données.
- `MYSQLDATABASE` : Le nom de la base de données.
- `MYSQLUSER` : Votre nom d'utilisateur.
- `MYSQLPASSWORD` : Votre mot de passe.
- `MYSQLPORT` : Le port (par défaut 3306).

### Installation Locale
1. Clonez le dépôt.
2. Importez `database.sql` dans votre serveur local (XAMPP/WAMP).
3. Configurez vos identifiants dans `config/database.php` ou via un fichier `.env`.

### Persistance des Images (Important)
Pour conserver les images téléchargées lors des déploiements sur Railway, un **Volume** doit être monté sur le chemin :
`/app/public/uploads`

## 🔐 Sécurité

- **Connexions Sécurisées** : Utilisation de PDO avec requêtes préparées.
- **Protection XSS** : Échappement systématique des sorties utilisateurs.
- **Zéro Secret** : Aucune donnée d'identification n'est stockée en clair dans le code source.

## 📜 Licence

© 2026 Conservatoire Municipal de Musique de Meknès - Tous droits réservés.
