# 📚 Documentation - Index

Bienvenue dans le dossier de documentation du projet ToDo & Co. Voici tous les documents disponibles :

---

## 📖 Guides pour Développeurs

### 1. [CONTRIBUTION.md](CONTRIBUTION.md)
**Guide de contribution au projet**
- Setup du projet complet
- Workflow Git et branches
- Normes de code (PHP, Twig, CSS)
- Processus de Pull Request
- Code review et déploiement
- FAQ avec 9 réponses pratiques

👉 **Lisez ceci si vous** : contribuez au code, soumettez une PR, ou avez une question sur le processus

---

### 2. [DOCUMENTATION_AUTHENTIFICATION.md](DOCUMENTATION_AUTHENTIFICATION.md)
**Architecture technique de l'authentification**
- Vue d'ensemble du système de sécurité
- Architecture détaillée avec schémas
- Explication de 5 fichiers clés
- Flux d'authentification étape par étape
- Stockage des utilisateurs en BD
- Rôles (ROLE_USER, ROLE_ADMIN)
- Questions/réponses pratiques
- Checklist pour développeurs juniors

👉 **Lisez ceci si vous** : travaillez sur l'authentification, modifiez la sécurité, ou apprenez le système

---

### 3. [TESTING_GUIDE.md](TESTING_GUIDE.md)
**Guide complet d'exécution des tests**
- Comment lancer les tests
- Tests individuels vs tous les tests
- Scripts automatisés (Windows/Linux)
- Résultats attendus
- Options PHPUnit utiles
- Dépannage des problèmes
- Détail de chaque test

👉 **Lisez ceci si vous** : lancez les tests, ajoutez des tests, ou débogez un test qui échoue

---

## 📊 Rapports et Résumés

### 4. [RAPPORT_COUVERTURE_TESTS.md](RAPPORT_COUVERTURE_TESTS.md)
**Rapport détaillé de couverture de code**
- Statistiques des 13 tests (100% passants)
- Couverture estimée : ~80-85%
- Détail de chaque anomalie corrigée
- Détail de chaque nouvelle fonctionnalité
- Couverture des contrôleurs, formulaires, entités

👉 **Lisez ceci si vous** : avez besoin de statistiques de tests, vérifiez la couverture, ou validez la qualité

---


## 🗂️ Organisation

```
documentations/
├── INDEX.md (ce fichier)
├── CONTRIBUTION.md                 👈 Commencez ici pour contribuer
├── DOCUMENTATION_AUTHENTIFICATION.md 👈 Commencez ici pour la sécurité
├── TESTING_GUIDE.md               👈 Commencez ici pour les tests
└── RAPPORT_COUVERTURE_TESTS.md     👈 Statistiques détaillées
```

---

## 🚀 Où Commencer ?

**Je suis nouveau sur le projet**
1. Lire [RAPPORT_COUVERTURE_TESTS.md](RAPPORT_COUVERTURE_TESTS.md) pour comprendre ce qui a été fait
2. Lire [CONTRIBUTION.md](CONTRIBUTION.md) pour savoir comment contribuer
3. Lire [TESTING_GUIDE.md](TESTING_GUIDE.md) pour pouvoir lancer les tests

**Je dois modifier l'authentification**
1. Lire [DOCUMENTATION_AUTHENTIFICATION.md](DOCUMENTATION_AUTHENTIFICATION.md) pour l'architecture
2. Consulter [TESTING_GUIDE.md](TESTING_GUIDE.md) pour tester mes changements
3. Vérifier [CONTRIBUTION.md](CONTRIBUTION.md) pour la PR

**Je dois lancer les tests**
1. Lire [TESTING_GUIDE.md](TESTING_GUIDE.md) pour les commandes
2. Consulter [RAPPORT_COUVERTURE_TESTS.md](RAPPORT_COUVERTURE_TESTS.md) pour le détail des tests

**Je dois faire un rapport**
1. Consulter [RAPPORT_COUVERTURE_TESTS.md](RAPPORT_COUVERTURE_TESTS.md)

---

## 📋 Résumé Rapide

| Document | Pages | Temps | Pour Qui ? |
|---|---|---|---|
| [CONTRIBUTION.md](CONTRIBUTION.md) | 20 | 15 min | Développeurs |
| [DOCUMENTATION_AUTHENTIFICATION.md](DOCUMENTATION_AUTHENTIFICATION.md) | 15 | 12 min | Dev + Lead |
| [TESTING_GUIDE.md](TESTING_GUIDE.md) | 10 | 8 min | QA + Dev |
| [RAPPORT_COUVERTURE_TESTS.md](RAPPORT_COUVERTURE_TESTS.md) | 8 | 6 min | QA + Lead |

---

## ✅ Checklist Avant de Contribuer

- [ ] J'ai lu [CONTRIBUTION.md](CONTRIBUTION.md)
- [ ] J'ai lu la section pertinente pour mon tâche
- [ ] J'ai exécuté les tests avec `./run_tests.sh` (ou `.bat`)
- [ ] Tous les tests passent (13/13)
- [ ] Mon code suit les normes PSR-2
- [ ] J'ai écrit des docblocks pour mes fonctions
- [ ] Je suis prêt à faire une PR

---

## 📞 Questions ?

1. Vérifiez d'abord la FAQ dans [CONTRIBUTION.md](CONTRIBUTION.md)
2. Consultez la section "Questions/Réponses" dans [DOCUMENTATION_AUTHENTIFICATION.md](DOCUMENTATION_AUTHENTIFICATION.md)
3. Lisez le "Dépannage" dans [TESTING_GUIDE.md](TESTING_GUIDE.md)

---

**Dernière mise à jour** : 28 décembre 2025  
**Statut** : ✅ Tous les documents à jour
