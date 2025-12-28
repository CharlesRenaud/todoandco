# 📋 ToDo & Co - Application de Gestion de Tâches

![Symfony](https://img.shields.io/badge/Symfony-3.1-black?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-7.2+-777BB4?style=flat-square)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-00758F?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Tests](https://img.shields.io/badge/Tests-13/13_✓-28a745?style=flat-square)
![Coverage](https://img.shields.io/badge/Coverage-82%25-28a745?style=flat-square)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/d031eafded0d4529a88bc1013189f259)](https://app.codacy.com/gh/CharlesRenaud/todoandco/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

## 🎯 À Propos du Projet

**ToDo & Co** est une application web permettant de gérer ses tâches quotidiennes. Initialement développée comme un MVP (Minimum Viable Product), elle a été améliorée avec :

- ✅ **Corrections d'anomalies** : Attachement des tâches aux utilisateurs, gestion des rôles
- ✅ **Nouvelles fonctionnalités** : Système d'autorisation granulaires
- ✅ **Tests automatisés** : 13 tests PHPUnit avec 82% de couverture
- ✅ **Documentation technique** : Guide complet pour les développeurs

### 🏢 Contexte

ToDo & Co est une startup qui a levé des fonds pour améliorer sa plateforme. Cette version du code représente une refactorisation majeure avec mise en place de bonnes pratiques de développement, tests et documentation.

---

## 🚀 Démarrage Rapide

### Prérequis

- **PHP** 7.2 ou supérieur (recommandé 7.4)
- **MySQL** 5.7+ ou **MariaDB** 10.2+
- **Composer** (gestionnaire de dépendances PHP)
- **Git** (pour le contrôle de version)

### Installation

#### 1. Cloner le repository

```bash
git clone https://github.com/[username]/projet8-TodoList.git
cd projet8-TodoList
```

#### 2. Installer les dépendances

```bash
composer install
```

#### 3. Configurer la base de données

```bash
# Copier le fichier de configuration
cp app/config/parameters.yml.dist app/config/parameters.yml
```

Éditer `app/config/parameters.yml` et configurer :
```yaml
parameters:
    database_host: localhost
    database_name: todolist_dev
    database_user: root
    database_password: votre_mot_de_passe
```

#### 4. Créer la base de données

```bash
# Créer la base de données
php bin/console doctrine:database:create

# Créer les tables
php bin/console doctrine:schema:update --force
```

#### 5. Lancer l'application

```bash
# Démarrer le serveur Symfony
php bin/console server:run

# Accéder à http://localhost:8000
```

---

## 📋 Fonctionnalités Implémentées

### ✅ Corrections d'Anomalies

#### 1. Tâches Attachées aux Utilisateurs
- Les tâches sont automatiquement attachées à l'utilisateur connecté lors de leur création
- L'auteur d'une tâche ne peut pas être modifié après création
- Contrainte base de données : `author_id NOT NULL`

#### 2. Rôles des Utilisateurs
- Lors de la création d'un utilisateur : choix entre **ROLE_USER** et **ROLE_ADMIN**
- Lors de la modification : possibilité de changer le rôle
- Rôles stockés en JSON dans la base de données

### ✅ Nouvelles Fonctionnalités

#### 1. Autorisations d'Accès
- **Gestion des utilisateurs** : Accessible uniquement aux administrateurs (ROLE_ADMIN)
- Redirection automatique vers login pour les utilisateurs non autorisés

#### 2. Suppression de Tâches
- Les utilisateurs ne peuvent supprimer que leurs propres tâches
- Les administrateurs peuvent supprimer n'importe quelle tâche
- Les tâches créées par l'utilisateur "anonyme" ne peuvent être supprimées que par un administrateur

---

## 🧪 Tests Automatisés

### Exécuter les Tests

```bash
# Tous les tests
php bin/phpunit.phar

# Un fichier de test spécifique
php bin/phpunit.phar tests/AppBundle/Controller/TaskControllerTest.php

# Avec rapport détaillé
php bin/phpunit.phar --testdox
```

### Résultats

| Suite de Tests | Tests | Assertions | Statut |
|---|---|---|---|
| **TaskControllerTest** | 4 | 8 | ✅ PASSÉ |
| **UserControllerTest** | 2 | 4 | ✅ PASSÉ |
| **AuthorizationTest** | 7 | 11 | ✅ PASSÉ |
| **TOTAL** | **13** | **23** | **✅ 100%** |

### Couverture de Code

- **Couverture globale** : 82% (objectif: >70%) ✅
- **Contrôleurs** : 85%
- **Formulaires** : 75%
- **Entités** : 70%

📊 Voir le [Rapport Complet de Couverture](coverage/index.pdf)

---

## 📁 Structure du Projet

```
projet8-TodoList/
├── app/                          # Configuration Symfony
│   ├── config/                   # Fichiers de configuration
│   │   ├── security.yml          # Configuration sécurité
│   │   ├── services.yml          # Services DI
│   │   └── parameters.yml        # Paramètres (BD, etc.)
│   ├── Resources/views/          # Layouts principaux
│   └── AppKernel.php             # Kernel Symfony
│
├── src/AppBundle/                # Code applicatif
│   ├── Controller/               # Contrôleurs (TaskController, UserController, etc.)
│   ├── Entity/                   # Entités Doctrine (Task, User)
│   ├── Form/                     # Formulaires (TaskType, UserType)
│   └── Repository/               # Requêtes BD
│
├── tests/                        # Tests PHPUnit
│   └── AppBundle/Controller/     # Tests des contrôleurs
│       ├── TaskControllerTest.php
│       ├── UserControllerTest.php
│       └── AuthorizationTest.php (nouveau)
│
├── web/                          # Fichiers publics
│   ├── app.php                   # Point d'entrée production
│   ├── app_dev.php              # Point d'entrée développement
│   └── css/, js/, img/          # Assets statiques
│
├── var/                          # Fichiers générés
│   ├── cache/                    # Cache Symfony
│   ├── logs/                     # Logs applicatifs
│   └── sessions/                 # Sessions utilisateur
│
├── coverage/                     # Rapports de couverture
│   ├── index.html               # Rapport complet
│   └── tests-details.html       # Détails des tests
│
├── documentations/               # 📚 Documentation
│   ├── CHANGELOG.md             # Liste des changements
│   ├── CONTRIBUTION.md          # Guide de contribution
│   ├── DOCUMENTATION_AUTHENTIFICATION.md  # Guide d'authentification
│   ├── COMPLETION_REPORT.md     # Rapport final du projet
│   ├── RAPPORT_COUVERTURE_TESTS.md # Rapport de couverture
│   ├── RESUME_IMPLEMENTATION.md # Résumé implémentation
│   ├── TESTING_GUIDE.md         # Guide des tests
│   └── POINTS_1_2_4_COMPLETES.md # Récapitulatif livrables
│
├── composer.json                # Dépendances
└── phpunit.xml.dist             # Configuration PHPUnit
```

---

## 🔐 Authentification

L'application utilise le **Symfony Security Component** pour gérer l'authentification.

### Fichiers Clés

- **Configuration** : `app/config/security.yml`
- **Entité Utilisateur** : `src/AppBundle/Entity/User.php`
- **Contrôleur** : `src/AppBundle/Controller/SecurityController.php`
- **Formulaire** : `src/AppBundle/Form/UserType.php`

### Rôles

| Rôle | Description | Accès |
|---|---|---|
| **ROLE_USER** | Utilisateur normal | Créer/éditer/supprimer ses tâches |
| **ROLE_ADMIN** | Administrateur | Tout + gestion des utilisateurs |

👉 **Voir la documentation complète** : [documentations/DOCUMENTATION_AUTHENTIFICATION.md](documentations/DOCUMENTATION_AUTHENTIFICATION.md)

---

## 👥 Gestion des Utilisateurs

### Créer un Utilisateur

1. Aller sur `/users/create` (admin seulement)
2. Remplir le formulaire
3. Choisir le rôle : **Utilisateur** ou **Administrateur**
4. Soumettre

### Modifier un Utilisateur

1. Aller sur `/users`
2. Cliquer sur l'utilisateur
3. Modifier les informations (y compris le rôle)
4. Sauvegarder

### Utilisateur "Anonyme"

- Les tâches créées avant l'implémentation sont rattachées à un utilisateur "anonyme"
- Seuls les administrateurs peuvent supprimer ces tâches

---

## 📝 Gestion des Tâches

### Créer une Tâche

1. Aller sur `/tasks/create`
2. Remplir le titre et le contenu
3. Soumettre (l'auteur est assigné automatiquement)

### Modifier une Tâche

1. Aller sur `/tasks`
2. Cliquer sur la tâche à modifier
3. Modifier le titre et le contenu
4. **Note** : L'auteur ne peut pas être changé

### Supprimer une Tâche

- **Créateur** : Peut supprimer sa tâche
- **Administrateur** : Peut supprimer n'importe quelle tâche
- **Utilisateur anonyme** : Seul un admin peut supprimer ces tâches

### Marquer comme Complétée

- Cliquer sur le bouton "Basculer" pour marquer une tâche comme complétée/incomplète

---

## 📚 Documentation

### 👉 Commencez par [documentations/INDEX.md](documentations/INDEX.md)
Un guide centralisé pour trouver exactement le document que vous cherchez.

### Pour les Développeurs

- **[documentations/CONTRIBUTION.md](documentations/CONTRIBUTION.md)** : Guide de contribution
  - Setup du projet
  - Workflow Git
  - Normes de code
  - Process de PR et code review

- **[documentations/DOCUMENTATION_AUTHENTIFICATION.md](documentations/DOCUMENTATION_AUTHENTIFICATION.md)** : Guide d'authentification
  - Architecture détaillée
  - Fichiers clés expliqués
  - Questions/réponses pratiques
  - Checklist pour développeurs juniors

- **[documentations/TESTING_GUIDE.md](documentations/TESTING_GUIDE.md)** : Guide des tests
  - Comment exécuter les tests
  - Détail de chaque test
  - Comment ajouter de nouveaux tests

### Rapports

- **[coverage/index.html](coverage/index.pdf)** : Rapport de couverture complet
- **[coverage/tests-details.html](coverage/tests-details.pdf)** : Détails des tests
- **[coverage/README.md](coverage/README.md)** : Guide d'accès aux rapports

---

## 🛠️ Outils et Technologies

### Framework & Langage

- **Symfony 3.1** : Framework PHP web
- **PHP 7.2+** : Langage de programmation
- **Doctrine ORM** : Gestion de la base de données

### Testing

- **PHPUnit 5.7.27** : Framework de tests
- **Symfony WebTestCase** : Tests fonctionnels

### Base de Données

- **MySQL 5.7+** ou **MariaDB 10.2+**
- **Doctrine** : Mapping objet-relationnel

### Gestion des Dépendances

- **Composer** : Gestionnaire de paquets PHP

---

## 🔒 Sécurité

### Mots de Passe

- Hachés en **BCrypt** (jamais en clair)
- Champ obligatoire lors de la création
- Peut être changé lors de la modification

### Authentification

- Basée sur **Symfony Security**
- Sessions sécurisées
- Protection CSRF sur les formulaires

### Autorisations

- **Contrôle d'accès** : Défini dans `app/config/security.yml`
- **Vérifications côté contrôleur** : `$this->denyAccessUnlessGranted('ROLE_ADMIN')`
- **Vérifications côté template** : `{% if is_granted('ROLE_ADMIN') %}`

---

## 📊 Processus de Développement

### Workflow Git

1. **Créer une branche** : `git checkout -b feature/ma-fonctionnalite`
2. **Développer** : Implémenter la fonctionnalité
3. **Tester** : `php bin/phpunit.phar`
4. **Committer** : `git commit -m "feat: description"`
5. **Pusher** : `git push origin feature/ma-fonctionnalite`
6. **Pull Request** : Créer une PR sur GitHub
7. **Code Review** : Attendre la validation
8. **Merge** : Fusionner dans `develop`

### Normes de Code

- **PSR-2** : Standards PHP
- **Symfony Best Practices** : Recommandations Symfony
- **Indentation** : 4 espaces
- **Docblocks** : PHPDoc obligatoires

---

## 🐛 Signaler des Bugs

1. Créer une **issue** sur GitHub
2. Décrire le bug précisément
3. Fournir les étapes pour le reproduire
4. Ajouter des screenshots si possible

---

## 🎓 Ressources Supplémentaires

### Documentation Officielle

- [Symfony Documentation](https://symfony.com/doc/3.1/)
- [Doctrine ORM](https://www.doctrine-project.org/)
- [PHPUnit](https://phpunit.de/documentation.html)
- [Git Documentation](https://git-scm.com/doc)

### Guides Internes

<<<<<<< HEAD
- [Guide d'Authentification](documentations/DOCUMENTATION_AUTHENTIFICATION.md)
- [Guide de Contribution](documentations/CONTRIBUTION.md)
- [Guide des Tests](documentations/TESTING_GUIDE.md)
- [Rapport de Couverture](coverage/index.html)
=======
- [Guide d'Authentification](DOCUMENTATION_AUTHENTIFICATION.md)
- [Guide de Contribution](CONTRIBUTION.md)
- [Guide des Tests](TESTING_GUIDE.md)
- [Rapport de Couverture](coverage/index.pdf)
>>>>>>> aa6d08db2589e05d93d320d9d96091a67bddc12a
