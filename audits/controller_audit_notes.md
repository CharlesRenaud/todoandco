# Symfony Project - Controller Audit Notes

Ce document résume toutes les modifications à apporter pour sécuriser et moderniser les controllers de votre projet Symfony 3.1.

---

## 1️⃣ Modifications générales pour tous les formulaires

- Toujours vérifier que le formulaire a été soumis avant de valider :

```php
if ($form->isSubmitted() && $form->isValid()) { ... }
```

- Remplacer tous les `if ($form->isValid())` dans `TaskController` et `UserController`.

---

## 2️⃣ TaskController

| Route | Modification recommandée | Commentaire |
|-------|-------------------------|------------|
| `/tasks/create` | Ajouter `isSubmitted()` dans la condition | Sécurité formulaire |
| `/tasks/{id}/edit` | Ajouter `isSubmitted()` dans la condition | Sécurité formulaire |
| `/tasks/{id}/toggle` | Restreindre l’accès aux utilisateurs autorisés (ex : `ROLE_USER`) et utiliser POST plutôt que GET | Empêche toute personne de marquer une tâche comme faite via URL |
| `/tasks/{id}/delete` | Restreindre l’accès aux utilisateurs autorisés et utiliser POST/DELETE plutôt que GET | Sécuriser la suppression |

---

## 3️⃣ UserController

| Route | Modification recommandée | Commentaire |
|-------|-------------------------|------------|
| `/users/create` | Ajouter `isSubmitted()` et vérification `ROLE_ADMIN` | Empêche un utilisateur non autorisé de créer un compte |
| `/users/{id}/edit` | Ajouter `isSubmitted()` et vérification `ROLE_ADMIN` | Empêche un utilisateur non autorisé de modifier un compte |
| Encodage du mot de passe | Ne réencoder que si le champ mot de passe est rempli | Évite de corrompre le mot de passe existant |

---

## 4️⃣ Sécurité globale

1. Ajouter des contrôles de rôle pour toutes les actions sensibles :

```php
$this->denyAccessUnlessGranted('ROLE_ADMIN');
```

2. Pour les actions `delete` et `toggle`, utiliser **méthode POST** plutôt que GET pour éviter la manipulation via URL.

---

## 5️⃣ Modernisation / maintenance

1. Ignorer ou signaler les warnings de dépréciation Twig (`InitRuntimeInterface`).
2. Les bundles `SensioDistributionBundle` et `SensioGeneratorBundle` peuvent rester désactivés pour éviter les conflits de console.
3. Les formulaires et entités sont corrects, pas besoin de changements structurels immédiats.

---

*Fin des notes.*

