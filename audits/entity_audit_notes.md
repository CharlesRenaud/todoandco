# Symfony Project - Entity Audit Notes

Ce document résume toutes les recommandations pour sécuriser et préparer les entités `User` et `Task` pour votre projet Symfony 3.1.

---

## 1️⃣ Task Entity

| Champ | Modification recommandée | Commentaire |
|-------|-------------------------|------------|
| `createdAt` | Aucun changement nécessaire | Initialisation correcte dans le constructeur |
| `title` | Vérifier les contraintes de validation | Déjà avec `@Assert\NotBlank` |
| `content` | Vérifier les contraintes de validation | Déjà avec `@Assert\NotBlank` |
| `isDone` | Aucun changement nécessaire | Méthode `toggle()` ok |

- Ajouter éventuellement des méthodes `setIsDone(bool $flag)` et `getIsDone(): bool` pour standardiser l'accès.
- Assurer que la table soit correctement créée dans la base de données via un script ou migrations.

---

## 2️⃣ User Entity

| Champ | Modification recommandée | Commentaire |
|-------|-------------------------|------------|
| `username` | Vérifier l'unicité et la longueur | Déjà défini unique et longueur max 25 |
| `password` | Ne jamais stocker en clair | Encodage via `security.password_encoder` obligatoire avant `persist()` |
| `email` | Vérifier l'unicité et format | Déjà validé avec `@UniqueEntity` et `@Assert\Email` |

- Implémenter des vérifications dans le controller pour ne réencoder le mot de passe que si un nouveau mot de passe est fourni.
- Penser à ajouter des rôles supplémentaires si nécessaire (ex: ROLE_ADMIN).

---

## 3️⃣ Sécurité et bonnes pratiques

1. Assurer que toutes les entités sensibles (comme User) soient correctement validées avant persistance.
2. Les entités doivent correspondre aux tables existantes dans la base (`user`, `task`).
3. Éviter toute modification directe des propriétés sensibles en dehors des setters.
4. Pour les scripts de création de tables ou données initiales, s'assurer que Doctrine est configuré correctement.

---

*Fin des notes.*

