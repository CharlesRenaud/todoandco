<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du rôle
            $role = $form->get('roles')->getData();
            $user->setRoles([$role]);

            // Encodage mot de passe
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        $originalPassword = $user->getPassword();

        $form = $this->createForm(UserType::class, $user);

        // Pré-sélection du rôle actuel
        $roles = $user->getRoles();
        $form->get('roles')->setData($roles[0]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du rôle
            $role = $form->get('roles')->getData();
            $user->setRoles([$role]);

            // Encodage mot de passe uniquement si rempli
            if (!empty($user->getPassword())) {
                $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
            } else {
                $user->setPassword($originalPassword);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
