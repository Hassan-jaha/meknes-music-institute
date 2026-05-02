# Guide de Persistance des Images sur Railway

Sur Railway, par défaut, tous les fichiers créés après le déploiement (comme les photos que vous téléchargez via l'Espace Admin) sont **éphémères**. Ils disparaissent à chaque nouvelle mise à jour du code.

Pour rendre vos images permanentes, vous devez configurer un **Volume Monté**.

## Étapes à suivre sur le Dashboard Railway :

1.  **Créer un Volume** :
    *   Allez dans votre projet Railway.
    *   Cliquez sur **"Create"** (en haut à droite) et choisissez **"Volume"**.
    *   Nommez-le `uploads_volume`.
    *   Donnez-lui une taille (ex: 1GB ou 5GB, c'est suffisant pour des images).

2.  **Connecter le Volume à votre Service** :
    *   Allez dans les paramètres (Settings) de votre service PHP (celui qui contient le code du site).
    *   Cherchez la section **"Volumes"**.
    *   Cliquez sur **"Mount Volume"**.
    *   Sélectionnez le volume `uploads_volume` que vous venez de créer.
    *   **IMPORTANT** : Dans le champ **"Mount Path"**, saisissez exactement :
        `/app/public/uploads`

3.  **Redéployer** :
    *   Railway va redéployer votre service. Maintenant, tout ce qui sera enregistré dans `public/uploads` sera conservé sur ce disque dur virtuel permanent, même si vous mettez à jour le code.

## Pourquoi c'est important ?
*   **Sécurité** : Vos images ne seront jamais supprimées par erreur.
*   **Performance** : Les images sont servies directement depuis un stockage dédié.
*   **Fiabilité** : Même si le serveur redémarre, vos annonces et galeries resteront illustrées.

---
*Note: J'ai déjà mis à jour le code PHP pour qu'il crée automatiquement le dossier `uploads` s'il manque et qu'il affiche un motif de secours (Placeholder) si une image est introuvable.*
