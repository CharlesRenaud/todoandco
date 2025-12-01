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
