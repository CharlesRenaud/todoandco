# Audit Twig Templates - TodoList Symfony Project

Date: 2025-12-02

## 🔹 Analyse globale

1. **Code dupliqué**
   - Les fichiers `list.html.twig` et `create.html.twig` pour les tâches contiennent beaucoup de duplication (boucle pour afficher les tâches).
   - **Solution :** Extraire ces sections dans un **partial Twig** (`_task_list.html.twig`) et inclure avec `{% include '_task_list.html.twig' %}`.

2. **Forms dans Twig**
   - Utilisation correcte de `form_start`, `form_widget`, `form_end`.
   - Les `action` explicites sont parfois redondantes, Symfony peut gérer automatiquement si le formType est bien relié au contrôleur.

3. **Sécurité**
   - Actions sensibles (`task_toggle`, `task_delete`) déclenchées via `GET`.
   - **Risque :** Vulnérabilité CSRF.
   - **Correction :** Utiliser `POST` avec token CSRF.

4. **Gestion de l’authentification**
   - Liens conditionnels selon `app.user` sont corrects.
   - Logout via GET n’est pas sécurisé. Préférer un formulaire POST.

5. **Assets manquants / warnings**
   - Certains fichiers CSS/JS référencés dans `base.html.twig` peuvent manquer (`jquery.js`, `bootstrap.min.css`).
   - Vérifier ou remplacer par un CDN.

6. **Accessibilité et SEO**
   - Les balises `alt` pour les images sont trop génériques (`alt="todo list"`).
   - Ajouter des descriptions détaillées.

7. **Responsive / design**
   - Classes Bootstrap utilisées correctement.
   - Attention aux `pull-right` pour les petits écrans.

8. **Twig deprecated / best practices**
   - Certaines classes Twig sont dépréciées (`Twig_Loader_Filesystem`, `Twig_Extension_InitRuntimeInterface`).
   - Pas urgent si Symfony 3, mais prévoir migration.

9. **Organisation**
   - `create.html.twig` est dupliqué pour `task` et `user`. Peut être factorisé avec des partials (`_form_task.html.twig`, `_form_user.html.twig`).

---

## 🔹 Points critiques à corriger

| Problème | Fichier(s) | Solution |
|-----------|------------|---------|
| Actions sensibles via GET (delete / toggle) | `list.html.twig` | Passer en POST + ajouter token CSRF |
| Logout via GET | `base.html.twig` | Utiliser un form POST avec token CSRF |
| Code dupliqué | `list.html.twig`, `create.html.twig`, `edit.html.twig` | Extraire des partials `_task_list.html.twig`, `_form_task.html.twig`, `_form_user.html.twig` |
| Assets manquants | Tous | Vérifier que tous les fichiers référencés existent dans `web/css` et `web/js` ou remplacer par CDN |
| `alt` des images trop générique | Tous | Ajouter des descriptions plus explicites pour SEO et accessibilité |
| Pull-right / responsive | Tous | Tester sur mobile et remplacer par classes Bootstrap modernes (`float-end` sur BS5 ou `d-flex justify-content-end`) |

---

## 🔹 Suggestions d’améliorations

1. **Factoriser les templates**
   - Partial pour listes de tâches : `_task_item.html.twig`.
   - Partial pour formulaires : `_task_form.html.twig` et `_user_form.html.twig`.

2. **Sécuriser les actions**
```twig
<form method="post" action="{{ path('task_delete', {'id': task.id}) }}">
    <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ task.id) }}">
    <button class="btn btn-danger btn-sm pull-right">Supprimer</button>
</form>
```

3. **Améliorer UX**
   - Ajouter messages flash pour erreurs et succès.
   - Ajouter pagination si beaucoup de tâches ou utilisateurs.

4. **Migration future**
   - Préparer Twig 3 et Symfony 4/5 si le projet doit être modernisé.

---

*Audit rédigé par l’équipe de revue de code - Projet TodoList Symfony*

