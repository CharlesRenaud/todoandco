# Symfony Project - Form Audit Notes

Ce document résume toutes les recommandations pour sécuriser et moderniser les formulaires `TaskType` et `UserType` dans votre projet Symfony 3.1.

---

## 1️⃣ TaskType Form

| Champ | Modification recommandée | Commentaire |
|-------|-------------------------|------------|
| `title` | Déjà défini, OK | Validation dans l'entité (`NotBlank`) |
| `content` | Déjà défini, OK | Validation dans l'entité (`NotBlank`) |
| `author` | Décommenter et lier à l'utilisateur authentifié | Ne pas permettre à l'utilisateur de choisir un autre author via le formulaire, utiliser `$task->setAuthor($this->getUser())` dans le controller |

- Toujours vérifier que le formulaire a été soumis avant de valider dans le controller :
```php
if ($form->isSubmitted() && $form->isValid()) { ... }
```

---

## 2️⃣ UserType Form

| Champ | Modification recommandée | Commentaire |
|-------|-------------------------|------------|
| `username` | Déjà défini, OK | `NotBlank` et `unique` dans l'entité |
| `password` | Déjà défini avec `RepeatedType`, OK | S'assurer de l'encodage dans le controller avant persist |
| `email` | Déjà défini, OK | Validation `NotBlank` et `Email` dans l'entité |

- Ajouter une logique dans le controller pour ne réencoder le mot de passe que si un nouveau mot de passe est fourni.
- Toujours vérifier `isSubmitted()` avant `isValid()` dans le controller.

---

## 3️⃣ Sécurité et bonnes pratiques

1. Ne jamais exposer l’ID ou données sensibles dans le formulaire.
2. Associer les entités et utilisateurs correctement dans le controller, pas via formulaire (ex: author de Task).
3. Assurer que tous les champs obligatoires sont validés via annotations dans l'entité.
4. Préparer le projet pour la migration vers Symfony plus récent en utilisant des types de champs explicites (`TextType`, `EmailType`, etc.).

---

*Fin des notes.*

