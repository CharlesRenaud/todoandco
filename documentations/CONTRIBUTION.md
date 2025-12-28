# Guide de Contribution - Projet ToDo & Co

Bienvenue ! Ce document explique comment contribuer au projet ToDo & Co. Que vous soyez développeur junior ou senior, suivez ces étapes pour assurer une qualité de code optimale.

## Table des Matières

1. [Avant de commencer](#avant-de-commencer)
2. [Setup du projet](#setup-du-projet)
3. [Workflow de contribution](#workflow-de-contribution)
4. [Normes de code](#normes-de-code)
5. [Tests](#tests)
6. [Pull Request](#pull-request)
7. [Code Review](#code-review)
8. [Déploiement](#déploiement)
9. [FAQ](#faq)

---

## Avant de commencer

### Prérequis

- **PHP 7.2+** (idéalement 7.4)
- **MySQL 5.7+** ou **MariaDB 10.2+**
- **Composer** (pour gérer les dépendances PHP)
- **Git** (contrôle de version)
- **Docker** (optionnel mais recommandé)

### Accès au projet

Le projet est hébergé sur GitHub : https://github.com/[username]/projet8-TodoList

**Branches principales** :
- `main` → Code en production
- `develop` → Code en développement (branche de base)
- `feature/*` → Nouvelles fonctionnalités
- `bugfix/*` → Corrections de bugs
- `refactor/*` → Refactorisation de code

---

## Setup du projet

### 1. Cloner le repository

```bash
git clone https://github.com/[username]/projet8-TodoList.git
cd projet8-TodoList
```

### 2. Installer les dépendances

```bash
# Installer les paquets Composer
composer install
```

### 3. Configurer l'environnement

```bash
# Créer le fichier de configuration local
cp app/config/parameters.yml.dist app/config/parameters.yml

# Éditer avec vos paramètres locaux
# - database_host: localhost
# - database_name: todolist_dev
# - database_user: root
# - database_password: (votre mot de passe MySQL)
```

### 4. Créer la base de données

```bash
# Créer la base de données
php bin/console doctrine:database:create

# Créer les tables (migrations)
php bin/console doctrine:schema:update --force

# Charger les données de test (optionnel)
php bin/console doctrine:fixtures:load --no-interaction
```

### 5. Lancer le serveur local

```bash
# Avec Symfony
php bin/console server:run

# L'application sera accessible sur http://localhost:8000
```

### 6. Vérifier que tout fonctionne

```bash
# Lancer les tests
php bin/phpunit.phar

# Résultat attendu : "OK (13 tests, 23 assertions)"
```

---

## Workflow de contribution

### Étape 1: Créer une issue sur GitHub

**Avant de coder**, créez une issue décrivant :
- **Titre** : court et descriptif
- **Description** : détails du bug ou de la fonctionnalité
- **Labels** : `bug`, `feature`, `documentation`, etc.
- **Assignés** : qui travaille dessus
- **Estimation** : story points ou jours

**Exemple** :
```
Titre: Ajouter validation email pour les utilisateurs

Description:
- Actuellement, n'importe quel format email est accepté
- Doit valider le format email selon RFC 5322
- Afficher un message d'erreur clair si invalide

Tâches:
- [ ] Ajouter validation dans UserType.php
- [ ] Ajouter test unitaire
- [ ] Tester manuellement

Story Points: 3
```

### Étape 2: Créer une branche de feature

```bash
# Créer une branche depuis 'develop'
git checkout develop
git pull origin develop
git checkout -b feature/mon-fonctionnalite

# Exemples de noms de branche
git checkout -b feature/email-validation
git checkout -b bugfix/task-author-null
git checkout -b refactor/security-config
```

**Convention de nommage** :
- `feature/[description]` → Nouvelle fonctionnalité
- `bugfix/[description]` → Correction de bug
- `refactor/[description]` → Refactorisation
- `hotfix/[description]` → Correction urgente en prod
- `docs/[description]` → Documentation

### Étape 3: Développer et tester

Pendant que vous développez :

```bash
# Avant chaque commit, vérifier que les tests passent
php bin/phpunit.phar

# Vérifier la qualité du code (optionnel mais recommandé)
# ... avec un outil de linting (PHP_CodeSniffer, PHPStan, etc.)
```

### Étape 4: Committer les changements

**Messages de commit clairs et concis** :

```bash
# ❌ Mauvais
git commit -m "fix"
git commit -m "update code"

# ✅ Bon
git commit -m "feat: ajouter validation email UserType"
git commit -m "fix: corriger null author en TaskController"
git commit -m "test: ajouter test pour deleteTaskAction"
git commit -m "docs: documenter le flux d'authentification"
```

**Format recommandé** :
```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types** :
- `feat` : nouvelle fonctionnalité
- `fix` : correction de bug
- `test` : ajout/modification de tests
- `docs` : documentation
- `refactor` : refactorisation
- `style` : formatage de code
- `chore` : maintenance

**Exemple complet** :
```bash
git commit -m "feat(user): ajouter validation email unique

- Valider l'email lors de la création
- Afficher erreur si email déjà utilisé
- Ajouter test unitaire

Closes #42"
```

### Étape 5: Pusher et créer une Pull Request

```bash
# Pousser la branche sur GitHub
git push origin feature/mon-fonctionnalite
```

Allez sur GitHub et créez une **Pull Request (PR)** :

**Template de PR** :
```markdown
## Description
Brève description des changements

## Type de changement
- [ ] Nouvelle fonctionnalité
- [ ] Correction de bug
- [ ] Refactorisation
- [ ] Documentation

## Changements
- Point 1
- Point 2

## Tests
- [ ] Tests unitaires ajoutés/modifiés
- [ ] Tests passants (13/13)
- [ ] Couverture > 70%

## Screenshots
(Si applicable)

## Checklist avant review
- [ ] Pas de console.log / var_dump
- [ ] Pas de code mort
- [ ] Tous les tests passent
- [ ] Code formaté selon les normes
- [ ] Documentation mise à jour

Closes #<issue-number>
```

**Exemple de PR** :
```
Titre: feat(auth): ajouter rôle administrateur

Description:
Ajoute la possibilité de choisir un rôle (ROLE_USER ou ROLE_ADMIN) lors 
de la création d'un utilisateur.

Type: Nouvelle fonctionnalité
Ferme: #15

Tests:
- ✅ testCreateUserWithRole()
- ✅ testEditUserRole()
- ✅ 13/13 tests passants

Changements principaux:
- src/AppBundle/Form/UserType.php : champ 'roles'
- src/AppBundle/Controller/UserController.php : gestion des rôles
- app/config/security.yml : nouvelle configuration
```

---

## Normes de code

### PHP & Symfony

#### Structure du code

```php
<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;

/**
 * Gère les actions sur les tâches
 */
class TaskController extends Controller
{
    /**
     * Affiche la liste des tâches
     *
     * @return Response
     */
    public function listAction()
    {
        // Code...
    }
}
```

#### Indentation et formatage

- **Indentation** : 4 espaces (pas de tabs)
- **Largeur de ligne** : maximum 120 caractères
- **Noms de variables** : camelCase
- **Noms de classes** : PascalCase
- **Constantes** : SNAKE_CASE

```php
// ❌ Mauvais
class task extends Controller {
    public function listAction(){
        $tasks = $this->getDoctrine()->getRepository('AppBundle:Task')->findAll();
        return $this->render('AppBundle:Task:list.html.twig',array('tasks'=>$tasks));
    }
}

// ✅ Bon
class TaskController extends Controller
{
    public function listAction()
    {
        $tasks = $this->getDoctrine()
            ->getRepository('AppBundle:Task')
            ->findAll();

        return $this->render(
            'AppBundle:Task:list.html.twig',
            ['tasks' => $tasks]
        );
    }
}
```

#### Docblocks

```php
/**
 * Ajoute une nouvelle tâche
 *
 * @param Request $request La requête HTTP
 * @param Task $task La tâche à créer
 * 
 * @return Response
 * 
 * @throws InvalidArgumentException Si la tâche est invalide
 */
public function createAction(Request $request, Task $task)
{
    // ...
}
```

#### Règles importantes

1. **Toujours initialiser les variables** :
```php
// ❌ Mauvais
public function getUser()
{
    $user = $this->getDoctrine()->getRepository()->find(1);
    return $user;
}

// ✅ Bon
public function getUserById(int $id): ?User
{
    return $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);
}
```

2. **Pas de code "mort"** :
```php
// ❌ Mauvais
public function editAction()
{
    // $oldCode = "ancien code commenté";
    // $task->setTitle("vieux titre");
    $task->setTitle("nouveau titre");
}

// ✅ Bon
public function editAction()
{
    $task->setTitle("nouveau titre");
}
```

3. **Utiliser les types** :
```php
// ❌ Mauvais
public function find($id)
{
    // $id pourrait être string, int, null...
}

// ✅ Bon
public function find(int $id): ?Task
{
    // Type strict : int en entrée, Task ou null en sortie
}
```

4. **Pas de magic strings** :
```php
// ❌ Mauvais
if ($task->getStatus() === "done") {
    // ...
}

// ✅ Bon
const STATUS_DONE = 'done';

if ($task->getStatus() === self::STATUS_DONE) {
    // ...
}
```

### Twig Templates

```twig
{# ❌ Mauvais #}
<div>{{ task.title }}</div>
<div>{{ user.email }}</div>

{# ✅ Bon #}
<div>{{ task.title|escape }}</div>
<div>{{ user.email|escape }}</div>

{# Toujours utiliser |escape ou |e pour sécuriser #}
```

### HTML & CSS

- Indentation : 2 espaces
- Classes CSS : kebab-case (`btn-primary`, `task-list`)
- IDs : éviter si possible, utiliser des classes

---

## Tests

### Types de tests

#### 1. Tests unitaires

Testent une classe isolée :

```php
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCanBeCreated()
    {
        $user = new User();
        $user->setUsername('john');
        
        $this->assertEquals('john', $user->getUsername());
    }
}
```

#### 2. Tests fonctionnels

Testent un contrôleur complet :

```php
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskCreation()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/tasks/create');
        
        $form = $crawler->selectButton('Créer')->form();
        $form['task[title]'] = 'Ma première tâche';
        
        $client->submit($form);
        
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
```

### Exécuter les tests

```bash
# Tous les tests
php bin/phpunit.phar

# Un fichier de test
php bin/phpunit.phar tests/AppBundle/Controller/TaskControllerTest.php

# Une méthode de test
php bin/phpunit.phar --filter testTaskCreation

# Avec rapport de couverture
php bin/phpunit.phar --coverage-html coverage/
```

### Couverture minimale

- **Objectif global** : > 70%
- **Contrôleurs** : > 80%
- **Formulaires** : > 70%
- **Entités** : > 60%

### Checklist avant commit

```
- [ ] Tous les tests passent
- [ ] Couverture >= 70%
- [ ] Pas de PHP warnings/errors
- [ ] Pas de tests skip/todo
- [ ] Tests documentés avec des noms clairs
```

---

## Pull Request

### Avant de soumettre une PR

1. **Mettre à jour depuis `develop`** :
```bash
git fetch origin
git rebase origin/develop
```

2. **Vérifier que tout passe** :
```bash
# Tests
php bin/phpunit.phar

# Linting (optionnel)
# php vendor/bin/phpstan analyse src/
```

3. **Commit avec un bon message** :
```bash
git commit -m "feat(task): ajouter auteur à la création"
```

4. **Pousser** :
```bash
git push origin feature/mon-fonctionnalite
```

### Créer la PR sur GitHub

**Titre** : `feat(scope): description courte`

**Description** (utiliser le template) :
- Qu'est-ce que c'est ?
- Pourquoi ?
- Comment tester ?
- Changements clés

**Example** :
```markdown
## Description
Ajoute l'assignation automatique de l'auteur lors de la création d'une tâche.

## Motivation
Actuellement, les tâches peuvent être créées sans auteur, ce qui cause des erreurs.
Cette PR assure que l'utilisateur connecté est automatiquement assigné comme auteur.

## Changements
- TaskController::createAction() : assignation automatique
- TaskType : champ author supprimé du formulaire
- Migrations BD : contrainte author_id NOT NULL

## Tests
- ✅ testTaskAuthorIsAutomaticallyAssigned
- ✅ testTaskAuthorCannotBeModified
- Tous les tests passent (13/13)

## Comment tester
1. Créer une nouvelle tâche
2. Vérifier que l'auteur est assigné automatiquement
3. Essayer de modifier l'auteur (doit être impossible)

Closes #12
```

---

## Code Review

### En tant que contributeur

1. **Répondre aux commentaires rapidement**
2. **Faire les changements demandés sans ajouter de nouveau code**
3. **Commiter les changements** :
```bash
git add .
git commit -m "review: adresser les commentaires de la PR #42"
git push
```

### En tant que reviewer

**Critères de review** :
- ✅ Code lisible et bien structuré
- ✅ Tests complets (> 70% couverture)
- ✅ Pas d'erreurs de sécurité
- ✅ Pas de duplication de code
- ✅ Documentation à jour
- ✅ Commits avec bons messages

**Commentaires constructifs** :
```
❌ Mauvais
"pourquoi tu as fait ça ?"

✅ Bon
"Cette ligne pourrait causer une NPE si $task est null. 
Considérer une vérification : if ($task !== null)"
```

**Approuver** : si tout est bon, cliquer sur "Approve"

---

## Déploiement

### Environnements

- **dev** : branche `develop`, serveur local
- **staging** : branche `develop`, pour tests avant prod
- **prod** : branche `main`, production réelle

### Processus de déploiement

1. **Développement sur `develop`** :
```bash
git checkout develop
git pull origin develop
git checkout -b feature/xxx
# ... développer ...
git push origin feature/xxx
# ... créer PR ...
```

2. **Merge sur `develop`** (après review) :
```bash
# GitHub UI : Merge PR
git checkout develop
git pull origin develop
```

3. **Release** (passage en prod) :
```bash
# Créer une release PR de develop vers main
git checkout main
git pull origin main
git merge origin/develop
git push origin main
git tag v1.2.0
git push origin v1.2.0
```

4. **Post-déploiement** :
```bash
# Sur le serveur de prod
git pull
composer install --no-dev
php bin/console cache:clear --env=prod
php bin/console doctrine:migrations:migrate
```

---

## FAQ

### Q: Par où je commence ?

**R:** 
1. Cloner le repo localement
2. Créer une branche `feature/xxx`
3. Implémenter votre changement
4. Lancer les tests
5. Créer une PR

### Q: Comment je sais quoi faire ?

**R:** Regardez les issues sur GitHub ! Il y a des labels :
- `good-first-issue` : pour les juniors
- `help-wanted` : cherche de l'aide
- `bug` : correction de bug

### Q: Je dois modifier la structure BD ?

**R:** 
1. Modifier l'entité (ex: `User.php`)
2. Créer une migration : `php bin/console doctrine:migrations:diff`
3. Vérifier la migration générée
4. Tester avec `php bin/console doctrine:migrations:migrate`

### Q: Comment ajouter une dépendance ?

**R:**
```bash
composer require symfony/some-package
# Commiter le changement dans composer.lock
git add composer.json composer.lock
git commit -m "chore: ajouter nouvelle dépendance"
```

### Q: Les tests passent en local mais échouent en CI ?

**R:** Cause commune : config d'environnement différente
1. Vérifier `app/config/config_test.yml`
2. Vérifier les variables d'environnement
3. Vérifier que la base de test existe

### Q: Comment je revert un commit ?

**R:**
```bash
# Dernier commit
git revert HEAD

# Commit spécifique
git revert <commit-hash>

# Ou tout simplement reset
git reset --soft HEAD~1  # Garde les changements
git reset --hard HEAD~1  # Supprime les changements
```

### Q: Je dois rebase sur develop ?

**R:**
```bash
git fetch origin
git rebase origin/develop

# Si conflits
git status  # Voir les conflits
# ... résoudre les conflits ...
git add .
git rebase --continue
git push origin feature/xxx -f  # -f car l'historique change
```

### Q: Comment je documente mon code ?

**R:** Utilisez les docblocks PHPDoc :
```php
/**
 * Crée une nouvelle tâche pour l'utilisateur courant
 *
 * @param Request $request Requête HTTP contenant les données
 * @param Task $task L'entité Task à créer
 * @return Response La réponse rendue
 * @throws \Exception Si la sauvegarde échoue
 */
public function createAction(Request $request, Task $task): Response
{
    // ...
}
```

---

## Ressources

- [Guide Symfony](https://symfony.com/doc/3.1/)
- [Best Practices Symfony](https://symfony.com/doc/3.1/best_practices.html)
- [PHP-FIG PSR-2](https://www.php-fig.org/psr/psr-2/)
- [Git Flow](https://danielkummer.github.io/git-flow-cheatsheet/)
- [OWASP Security](https://owasp.org/)

**Merci de contribuer à ToDo & Co ! 🚀**
