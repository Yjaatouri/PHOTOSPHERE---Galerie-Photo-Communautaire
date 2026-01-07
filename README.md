📸 PhotoSphere — Galerie Photo Communautaire

PhotoSphere est une plateforme web moderne et robuste dédiée aux photographes souhaitant partager, organiser et valoriser leurs créations dans un environnement structuré et intuitif.
Développée pour la startup PixelCraft Digital, l’application met en avant une gestion avancée des rôles utilisateurs ainsi qu’un contrôle rigoureux du cycle de vie des médias.

🚀 Contexte du Projet

De nombreux photographes — amateurs comme professionnels — recherchent une solution leur permettant de présenter leurs portfolios sans subir la complexité et les contraintes des réseaux sociaux classiques.
PhotoSphere répond à ce besoin en proposant une plateforme spécialisée, performante et sécurisée.

Objectifs techniques

Architecture orientée objet : hiérarchie claire des utilisateurs basée sur l’héritage.

Sécurité renforcée : hachage des mots de passe avec bcrypt et validation stricte des fichiers uploadés.

Performance optimisée : requêtes SQL efficaces et mise en cache des données fréquemment consultées.

👥 Gestion des Rôles Utilisateurs (RBAC)

Le système repose sur un contrôle d’accès basé sur les rôles, offrant quatre niveaux distincts :

Rôle	Description	Fonctionnalités principales
BasicUser	Photographe amateur	Limite de 10 photos par mois, albums publics uniquement
ProUser	Photographe professionnel	Upload illimité, albums privés, statistiques avancées
Moderator	Modérateur	Modération des commentaires, suspension de comptes
Admin	Administrateur	Gestion complète des utilisateurs et du système
🖼️ Gestion des Photos et des Albums
Cycle de vie des photos

Chaque photo suit un workflow précis garantissant la qualité et la cohérence du contenu :

Brouillon — visible uniquement par son propriétaire après l’upload.

Publié — accessible publiquement ou en privé selon les paramètres définis.

Archivé — retiré de l’affichage public tout en restant stocké dans l’espace personnel.

Règles métier

Validation des fichiers : formats JPEG, PNG ou GIF, taille maximale de 10 Mo.

Albums : minimum 1 photo, maximum 100 photos par album.

Interactions : un utilisateur ne peut pas commenter ses propres photos.

📊 Conception UML
Diagramme de cas d’utilisation

Illustration des actions disponibles pour chaque acteur : upload de photos, likes, commentaires et modération.

Diagramme de classes

L’architecture repose sur une classe abstraite User, étendue par les différents types d’utilisateurs, et sur une gestion complète des photos et de leurs métadonnées.

💻 Stack Technique

Langage : PHP 8+ (Programmation Orientée Objet)

Base de données : MySQL avec contraintes d’intégrité référentielle

Sécurité : Bcrypt, validation MIME réelle, protection contre les injections SQL

Outils : Git, Trello , drawIO