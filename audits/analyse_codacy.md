# Infos setup du projet
 
Le projet est ancien (Symfony 3.1, environ 9 ans), et certaines commandes Doctrine ou Symfony actuelles ne fonctionnent plus correctement sur cet environnement, notamment à cause d’un conflit avec l’option connection dans la console. Pour contourner ce problème :

Nous avons désactivé temporairement les bundles de génération et distribution (SensioDistributionBundle et SensioGeneratorBundle) qui provoquaient ce conflit.

Nous avons créé un script PHP maison pour envoyer directement nos entités (User, Task, etc.) dans la base de données, ce qui est plus simple et rapide que d’utiliser les commandes Doctrine bloquées.

Cette approche permet de mettre en place le schéma complet de la base sans impacter le fonctionnement des fonctionnalités principales du projet.

# Audit de sécurité Codacy --- Résumé des vulnérabilités

## 1. Vulnérabilités critiques identifiées

L'audit de sécurité Codacy a identifié plusieurs vulnérabilités
critiques dans le projet.\
Celles-ci se répartissent en quatre catégories principales :

### 🔴 1. Dépendances Symfony et SwiftMailer vulnérables

-   Plusieurs CVE affectent les versions utilisées.
-   Risques : exécution de commande distante (RCE), contournement de
    sécurité, autres failles critiques.
-   **Action recommandée** : mettre à jour Symfony vers une version
    supportée (≥ 3.4 ou idéalement 4.4) et SwiftMailer vers une version
    corrigée.

### 🔴 2. Sorties non échappées (XSS)

-   Fichiers concernés : `config.php`, `app_dev.php`.
-   HTML construit directement avec `echo`, sans échappement.
-   **Risque** : injection JavaScript, vol de session, modifications de
    page.
-   **Correction** : utiliser `htmlspecialchars()` ou l'escaping Twig.

### 🔴 3. Entrées non validées

-   Utilisation directe de `$_SERVER['REMOTE_ADDR']` sans validation.
-   **Risque** : contournement du mode dev, comportement inattendu,
    failles d'accès.
-   **Correction** : vérifier l'existence de l'index et filtrer les
    valeurs.

### 🔴 4. Manque général de sanitization / escaping

-   Plusieurs variables affichées sans nettoyage.
-   **Correction** : systématiser `htmlspecialchars()` ou la validation
    via FormTypes / Validator Symfony.

------------------------------------------------------------------------

## 2. Recommandation principale

La mesure la plus importante est la **mise à jour du framework Symfony**
vers une version maintenue (≥ 3.4 ou idéalement 4.4), la mise à jour de
SwiftMailer, et une **revue complète des sorties HTML** afin d'éliminer
les risques d'injection XSS.

Ces correctifs réduisent fortement la surface d'attaque et garantissent
un socle sécurisé pour le reste du projet.
# Audit des issues HIGH - Codacy

## Analyse des issues HIGH détectées

Codacy a identifié plusieurs vulnérabilités classées « HIGH », principalement dans des fichiers legacy générés automatiquement par Symfony 3 (`config.php`, `app_dev.php`, `app.php`).

### 1. File Access (require/include)
- Exemples : `require_once`, `include_once` sur des chemins internes.
- **Risque réel : faible**. Ces chemins ne sont jamais influencés par l'utilisateur.
- Ces instructions font partie du bootstrap Symfony.

### 2. Fonctions potentiellement sensibles (dirname, header, call_user_func)
- Ces fonctions sont utilisées dans des scripts système et des fichiers utilitaires.
- **Risque réel : faible**. Aucune valeur utilisateur ne peut les manipuler.

### 3. Input Validation - accès direct à $_SERVER
- Exemples : `$_SERVER['REMOTE_ADDR']`, `$_SERVER['HTTP_X_FORWARDED_FOR']`, `$_SERVER['HTTP_CLIENT_IP']`, `$_SERVER['HTTP_HOST']`.
- **Risque : moyen** si app_dev.php est exposé publiquement.
- Recommandation : valider et filtrer ces entrées, utiliser `$_SERVER['REMOTE_ADDR'] ?? null`.

### 4. echo / exit / header (comportement imprévisible)
- Utilisation dans des scripts utilitaires, principalement config.php et app_dev.php.
- **Risque réel : faible**, mais améliorable.
- Ces instructions ne sont jamais exécutées dans un contexte de production.

## Recommandations générales
- Mettre à jour Symfony vers une version supportée (≥ 3.4 ou idéalement 4.x).
- Sécuriser l'accès à `app_dev.php` via le serveur web (HTACCESS / VHOST).
- Ajouter validation/sanitization pour toutes les entrées `$_SERVER[...]`.
- Migrer vers Symfony Flex pour éliminer les fichiers legacy qui déclenchent ces alertes.

---

**Conclusion :**
La majorité des alertes HIGH sont dues à des fichiers legacy de Symfony 3 et ne constituent pas des failles exploitables dans l’application ToDo & Co. Quelques correctifs simples (validation des superglobales et sécurisation de l’accès dev) permettent de réduire la surface d’attaque et d’améliorer la conformité aux bonnes pratiques de sécurité.

