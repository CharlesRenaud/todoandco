# Audit et Plan d'Actions - Projet ToDoList

## 1. Contexte du projet

- Projet ancien : Symfony 3.1 (~9 ans)
- Certaines commandes Doctrine/Symfony actuelles ne fonctionnent pas (ex: option `connection` conflictuelle)
- Contournement actuel : désactivation temporaire des bundles `SensioDistributionBundle` et `SensioGeneratorBundle` et utilisation d'un script PHP maison pour créer les entités (`User`, `Task`)

---

## 2. Points principaux identifiés

### 2.1 Dépendances et sécurité

- Symfony 3.1 et SwiftMailer utilisés sont vulnérables
- Risques : RCE, contournement sécurité, autres failles critiques
- Codacy confirme les CVE sur ces versions
- **Action :** mettre à jour Symfony (≥3.4, idéal 4.x) et SwiftMailer

### 2.2 Entrées non validées / non échappées

- Controllers / Forms : certaines valeurs entrantes (ex: email, username, contenu) doivent être validées
- Twig : certaines variables affichées sans `|escape`
- Codacy identifie `$_SERVER` non validé et `echo` dans `config.php` et `app_dev.php`
- **Action :**
  - Valider et filtrer toutes les entrées utilisateur
  - Utiliser `|escape` dans Twig pour toutes les sorties
  - Supprimer l’usage direct de `$_SERVER` non filtré

### 2.3 Fichiers legacy

- `app_dev.php`, `config.php` utilisent `require`, `echo`, `header`, etc.
- Pas directement exploitables mais Codacy signale comme HIGH
- **Action :**
  - Sécuriser l’accès à `app_dev.php` via serveur (htaccess / vhost)
  - Supprimer ou isoler les scripts legacy inutilisés

---

## 3. Audit manuel

### 3.1 Controllers

#### Problèmes identifiés

- Utilisation de `isValid()` : Symfony 3 recommande `isSubmitted() && isValid()`
- Pas de gestion des exceptions pour Doctrine (`flush()`)
- Password encoder utilisé directement sans vérification de champ vide
- Actions `loginCheck` et `logoutCheck` : commentées mais présentes (OK, standard Symfony)

#### Actions recommandées

- Mettre à jour les validations Form
- Ajouter try/catch pour Doctrine flush
- Vérifier mot de passe avant encodage
- Passer à `isSubmitted() && isValid()` pour tous les formulaires
- Sécuriser toutes les routes sensibles (roles, accès)

### 3.2 Forms

#### Problèmes identifiés

- TaskType : pas d’association avec l’utilisateur connecté
- UserType : pas de vérification de force mot de passe

#### Actions recommandées

- Lier Task à l’utilisateur connecté
- Ajouter validation mot de passe fort (Regex ou contraintes Symfony)
- Ajouter validation email unique côté formulaire

### 3.3 Entities

#### Problèmes identifiés

- User : password non haché avant persist (déjà corrigé côté controller)
- Task : pas de relation User, pas de validation author

#### Actions recommandées

- Créer relation `Task -> User`
- Ajouter getter/setter author
- Valider toutes les propriétés avec annotations Symfony Validator

### 3.4 Twig

#### Problèmes identifiés

- Templates utilisent variables sans `|escape`
- Certains boutons submit dans form avec `GET` au lieu de `POST`
- Actions `toggle` et `delete` exposées via GET (failles CSRF possibles)

#### Actions recommandées

- Ajouter `|escape` pour toutes les variables affichées
- Passer tous les formulaires sensibles à POST + CSRF protection
- Séparer affichage / actions sensibles

---

## 4. Actions priorisées pour rendre le projet fonctionnel et sécurisé

1. **Mise à jour du framework**
   - Symfony ≥ 3.4 (idéal 4.x)
   - SwiftMailer à une version sécurisée
   - Vérifier compatibilité PHP (PHP 7.2+ recommandé)

2. **Validation et sécurisation**
   - Forms : `isSubmitted() && isValid()`
   - Entities : validations et relations (Task -> User)
   - Password : encoder seulement si champ rempli
   - Sanitize toutes les entrées utilisateur

3. **Twig et interface**
   - Ajouter `|escape` pour toutes les variables
   - Convertir toutes les actions sensibles en formulaire POST avec CSRF
   - Revoir affichage des messages flash

4. **Doctrine / Base de données**
   - Supprimer le script maison une fois Doctrine fonctionnel
   - Corriger commandes doctrine schema/update si conflit résolu
   - Ajouter index sur email / username pour performance et sécurité

5. **Sécurisation des fichiers legacy**
   - Restreindre accès à `app_dev.php`
   - Nettoyer ou isoler scripts legacy (`config.php`, `app_dev.php`)
   - Supprimer bundles désactivés si plus nécessaire

6. **Audit général**
   - Passer en revue les logs, exceptions et erreurs
   - Vérifier que toutes les routes sensibles sont sécurisées par rôle

---

## 5. Conclusion

- La majorité des problèmes viennent de l’ancienneté du projet
- Codacy et audit manuel se rejoignent sur :
  - Dépendances vulnérables
  - Entrées/sorties non validées
  - Fichiers legacy
- Priorité :
  1. Mettre à jour Symfony et dépendances
  2. Sécuriser entrées, sorties et actions sensibles
  3. Corriger controllers, forms, entities et Twig selon recommandations

> Une fois ces corrections appliquées, le projet sera fonctionnel, sécurisé et plus facile à maintenir.
