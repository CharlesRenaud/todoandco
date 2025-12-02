# Audit et plan d'action pour le projet TodoList

Ce document regroupe toutes les actions à effectuer pour rendre le projet **fonctionnel, sécurisé et maintenable**.

---

## 1. Entities

### Problèmes identifiés
- User : pas de vérification de la validité du mot de passe ou de gestion de rôles avancée.
- Task : pas de relation avec l'utilisateur (`author`), ce qui empêche de gérer l'appartenance des tâches.

### Actions à faire
1. Ajouter un champ `author` dans `Task` et le relier à `User` via ManyToOne.
2. Ajouter une validation pour le mot de passe de l'utilisateur (minimum 8 caractères, force du mot de passe).
3. Ajouter des rôles flexibles dans l'entité `User`.
4. Ajouter des méthodes utilitaires dans `User` et `Task` si nécessaire pour faciliter le code des controllers.

---

## 2. Forms

### Problèmes identifiés
- TaskType : pas de lien avec l'utilisateur connecté.
- UserType : mot de passe en clair dans le formulaire, pas de confirmation côté serveur.

### Actions à faire
1. Dans `TaskType`, ne pas exposer `author` dans le formulaire; le récupérer depuis l'utilisateur authentifié.
2. Dans `UserType`, utiliser `RepeatedType` pour mot de passe et vérifier côté serveur avant persistance.
3. Ajouter des contraintes supplémentaires côté formulaire pour renforcer la sécurité.

---

## 3. Controllers

### Problèmes identifiés
- Les actions `loginCheck` et `logoutCheck` sont inutiles mais présentes.
- Les controllers manipulent le mot de passe en clair pour `User`.
- Pas de contrôle d'accès : n'importe qui peut modifier/supprimer les tâches ou utilisateurs.
- Les formulaires sont traités sans vérifier `isSubmitted()`.

### Actions à faire
1. Supprimer ou documenter que `loginCheck` et `logoutCheck` sont gérés par Symfony et ne doivent pas être touchés.
2. Dans `UserController`, encoder le mot de passe uniquement si un nouveau mot de passe est fourni.
3. Ajouter des contrôles d'accès via `@IsGranted` ou `security.yaml` pour protéger les actions CRUD.
4. Vérifier `if ($form->isSubmitted() && $form->isValid())` pour tous les formulaires.
5. Ajouter des protections CSRF si ce n’est pas déjà fait (Symfony le gère par défaut).
6. Mettre en place une gestion des exceptions pour les entités non trouvées (`NotFoundHttpException`).
7. Dans `TaskController`, lier les nouvelles tâches à l'utilisateur authentifié.

---

## 4. Twig Templates

### Problèmes identifiés
- Les templates listent toutes les tâches et utilisateurs sans vérification des permissions.
- Les formulaires utilisent des actions hardcodées, pas `{{ path() }}` systématiquement.
- Plusieurs warnings sur les assets manquants et la gestion des images.
- Code Bootstrap classique, mais certains formulaires ont des boutons doublons.

### Actions à faire
1. Ajouter des vérifications de permissions (`if is_granted('ROLE_ADMIN')`) pour certaines actions.
2. Corriger tous les liens et actions de formulaires pour utiliser `{{ path() }}`.
3. Vérifier que tous les assets sont présents et correctement référencés.
4. Améliorer l’affichage des messages flash.
5. Supprimer les doublons de templates (`create.html.twig` pour task et user).
6. Mettre à jour les icônes Bootstrap/Glyphicon si nécessaire.

---

## 5. Sécurité générale

1. Mettre à jour Symfony et ses composants si possible, sinon documenter les limitations liées à la version 3.1.
2. Configurer correctement le `security.yaml` pour:
   - Encoder les mots de passe
   - Gérer les rôles
   - Restreindre l’accès aux routes sensibles
3. Supprimer tous les bundles obsolètes ou désactivés (SensioGeneratorBundle, DistributionBundle).
4. S'assurer que toutes les entrées utilisateurs sont validées et filtrées.
5. Ajouter des logs pour les actions sensibles.

---

## 6. Bases de données

1. Ajouter `author_id` à `Task` et créer la relation.
2. Générer les migrations via `doctrine:migrations` ou script PHP maison.
3. S’assurer que toutes les tables existent avant de lancer l’application.

---

## 7. Ordre recommandé d’implémentation

1. Nettoyer le projet (supprimer les bundles désactivés et scripts obsolètes).
2. Mettre à jour les entités (User et Task) avec validations et relations.
3. Mettre à jour les formulaires (TaskType et UserType) selon les entités.
4. Corriger les controllers (vérifications `isSubmitted()`, encodage mot de passe, contrôle d’accès).
5. Corriger et harmoniser les templates Twig.
6. Ajouter les migrations et créer la base de données.
7. Tester toutes les actions CRUD avec différents rôles.
8. Ajouter des tests unitaires et fonctionnels (si possible, PHPUnit).

---

**Fin du document d’audit et plan d’action.**

