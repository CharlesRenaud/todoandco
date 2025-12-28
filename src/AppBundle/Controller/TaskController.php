<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        $tasks = $this->getDoctrine()->getRepository('AppBundle:Task')->findAll();

        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'user' => $this->getUser() // utilisateur connecté
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    // Pour activer/désactiver facilement la route, commenter ou décommenter la ligne ci-dessous
    // @Route("/tasks/assign-anonymous", name="task_assign_anonymous")
    public function assignAnonymousTasksAction()
    {
        $em = $this->getDoctrine()->getManager();

        // récupérer ou créer l'utilisateur anonyme
        $anonymous = $em->getRepository('AppBundle:User')->findOneByUsername('anonyme');

        if (!$anonymous) {
            $anonymous = new \AppBundle\Entity\User();
            $anonymous->setUsername('anonyme');
            $anonymous->setEmail('anonyme@example.com');

            $password = $this->get('security.password_encoder')
                ->encodePassword($anonymous, 'changeme');

            $anonymous->setPassword($password);
            $anonymous->setRoles(['ROLE_USER']);

            $em->persist($anonymous);
            $em->flush();
        }

        // récupérer uniquement les tâches sans auteur
        $tasks = $em->getRepository('AppBundle:Task')->findBy(['author' => null]);

        foreach ($tasks as $task) {
            $task->setAuthor($anonymous);
        }

        $em->flush();

        return new \Symfony\Component\HttpFoundation\Response(
            sprintf('%d tâches ont été assignées à l’utilisateur anonyme.', count($tasks))
        );
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $currentUser = $this->getUser();
        
        // Vérifier si l'utilisateur peut supprimer la tâche
        $isAuthor = $task->getAuthor()->getId() === $currentUser->getId();
        $isAdmin = in_array('ROLE_ADMIN', $currentUser->getRoles());
        $isAnonymousTask = $task->getAuthor()->getUsername() === 'anonyme';
        
        // Un utilisateur peut supprimer sa tâche, ou un admin peut supprimer n'importe quelle tâche
        // Les tâches anonymes peuvent seulement être supprimées par un admin
        if ($isAnonymousTask) {
            if (!$isAdmin) {
                throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer une tâche anonyme.');
            }
        } else {
            if (!$isAuthor && !$isAdmin) {
                throw $this->createAccessDeniedException('Vous ne pouvez supprimer que vos propres tâches.');
            }
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
