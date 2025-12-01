ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1



L’audit de sécurité Codacy a identifié plusieurs vulnérabilités critiques dans le projet.
Celles-ci se répartissent en 4 catégories principales :

Dépendances Symfony et SwiftMailer vulnérables (plusieurs CVE, RCE potentielle).

Sorties non échappées (XSS) dans config.php et app_dev.php.

Entrées non validées, notamment l’utilisation directe de $_SERVER['REMOTE_ADDR'].

Manque d’escaping ou sanitization dans plusieurs fichiers.

La principale action corrective recommandée est la mise à jour du framework Symfony vers une version supportée (≥ 3.4 ou idéalement 4.4) et la mise à jour de SwiftMailer, accompagnée d’une revue complète des sorties HTML pour éliminer les risques d’injection XSS.
