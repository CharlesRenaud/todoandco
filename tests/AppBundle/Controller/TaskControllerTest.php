<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class TaskControllerTest extends WebTestCase
{
    private $client;
    private $em;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        // Supprime tous les utilisateurs et tâches de test
        $taskUsers = $this->em->getRepository(User::class)->findByUsername('task_user');
        foreach ($taskUsers as $user) {
            $this->em->remove($user);
        }

        $anonymous = $this->em->getRepository(User::class)->findOneByUsername('anonyme');
        if ($anonymous) {
            $this->em->remove($anonymous);
        }

        $tasks = $this->em->getRepository(Task::class)->findByTitle(['Tâche test', 'Task edit', 'Task sans auteur']);
        foreach ($tasks as $task) {
            $this->em->remove($task);
        }

        $this->em->flush();
        parent::tearDown();
    }

    /**
     * Simule la connexion d’un utilisateur
     */
    private function logUser(User $user)
    {
        $session = $this->client->getContainer()->get('session');
        $firewallName = 'main';

        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallName, serialize($token));
        $session->save();

        $this->client->getCookieJar()->set(
            new Cookie($session->getName(), $session->getId())
        );
    }

    private function createAuthenticatedUser($username = 'task_user')
    {
        // Supprime si existant
        $existingUser = $this->em->getRepository(User::class)->findOneByUsername($username);
        if ($existingUser) {
            $this->em->remove($existingUser);
            $this->em->flush();
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username.'@test.com');
        $user->setPassword(
            $this->client->getContainer()->get('security.password_encoder')
                ->encodePassword($user, 'password')
        );
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        $this->logUser($user);

        return $user;
    }

    /**
     * Test : auteur est automatiquement l'utilisateur connecté lors de la création
     */
    public function testTaskAuthorIsAutomaticallyAssigned()
    {
        $user = $this->createAuthenticatedUser();

        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $form = $crawler->selectButton('Ajouter')->form([
            'task[title]'   => 'Tâche test',
            'task[content]' => 'Contenu test',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $task = $this->em->getRepository(Task::class)
            ->findOneByTitle('Tâche test');

        $this->assertNotNull($task);
        $this->assertEquals($user->getId(), $task->getAuthor()->getId());
    }

    /**
     * Test : l’auteur ne peut pas être modifié après création
     */
    public function testTaskAuthorCannotBeModified()
    {
        $user = $this->createAuthenticatedUser();

        $task = new Task();
        $task->setTitle('Task edit');
        $task->setContent('content');
        $task->setAuthor($user);
        $this->em->persist($task);
        $this->em->flush();

        $crawler = $this->client->request('GET', '/tasks/'.$task->getId().'/edit');
        $form = $crawler->selectButton('Modifier')->form([
            'task[title]' => 'Titre modifié',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->em->clear();
        $task = $this->em->getRepository(Task::class)->find($task->getId());

        $this->assertEquals($user->getId(), $task->getAuthor()->getId());
        $this->assertEquals('Titre modifié', $task->getTitle());
    }

    /**
     * Test : toggle de la tâche
     */
    public function testToggleTask()
    {
        $user = $this->createAuthenticatedUser();

        $task = new Task();
        $task->setTitle('Task toggle');
        $task->setContent('content');
        $task->setAuthor($user);
        $this->em->persist($task);
        $this->em->flush();

        $this->client->request('GET', '/tasks/'.$task->getId().'/toggle');
        $this->em->clear();
        $task = $this->em->getRepository(Task::class)->find($task->getId());

        $this->assertTrue($task->isDone());
    }

    /**
     * Test : suppression d’une tâche
     */
    public function testDeleteTask()
    {
        $user = $this->createAuthenticatedUser();

        $task = new Task();
        $task->setTitle('Task delete');
        $task->setContent('content');
        $task->setAuthor($user);
        $this->em->persist($task);
        $this->em->flush();

        $this->client->request('GET', '/tasks/'.$task->getId().'/delete');
        $this->em->clear();
        $task = $this->em->getRepository(Task::class)->findOneByTitle('Task delete');

        $this->assertNull($task);
    }

    /**
     * Test : assignation des tâches sans auteur à l’utilisateur anonyme
     */
    /*
    public function testAssignAnonymousTasks()
    {
        // S'assurer que l'utilisateur anonyme existe
        $anonymous = $this->em->getRepository(User::class)->findOneByUsername('anonyme');
        if (!$anonymous) {
            $anonymous = new User();
            $anonymous->setUsername('anonyme');
            $anonymous->setEmail('anonyme@example.com');
            $anonymous->setPassword(
                $this->client->getContainer()->get('security.password_encoder')
                    ->encodePassword($anonymous, 'changeme')
            );
            $anonymous->setRoles(['ROLE_USER']);
            $this->em->persist($anonymous);
            $this->em->flush();
        }

        // Créer un utilisateur temporaire pour la tâche
        $user = $this->createAuthenticatedUser('temp_user');
        $task = new Task();
        $task->setTitle('Task sans auteur');
        $task->setContent('content');
        $task->setAuthor($user);
        $this->em->persist($task);
        $this->em->flush();

        // Appel de la route assign-anonymous
        $this->client->request('GET', '/tasks/assign-anonymous');

        // Recharger la tâche depuis la base
        $this->em->clear();
        $taskFromDb = $this->em->getRepository(Task::class)->findOneByTitle('Task sans auteur');

        $this->assertNotNull($taskFromDb->getAuthor());
        $this->assertEquals('anonyme', $taskFromDb->getAuthor()->getUsername());
    }
    */
}
