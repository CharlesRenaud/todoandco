<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class AuthorizationTest extends WebTestCase
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
        // Supprime les utilisateurs et tâches créés lors des tests
        $users = $this->em->getRepository(User::class)->findByUsername('user_delete_test');
        foreach ($users as $user) {
            $this->em->remove($user);
        }

        $users = $this->em->getRepository(User::class)->findByUsername('admin_delete_test');
        foreach ($users as $user) {
            $this->em->remove($user);
        }

        $users = $this->em->getRepository(User::class)->findByUsername('other_user_test');
        foreach ($users as $user) {
            $this->em->remove($user);
        }

        $tasks = $this->em->getRepository(Task::class)->findByTitle('Task delete test');
        foreach ($tasks as $task) {
            $this->em->remove($task);
        }

        $anonymous = $this->em->getRepository(User::class)->findOneByUsername('anonyme');
        if ($anonymous) {
            $tasks = $this->em->getRepository(Task::class)->findByAuthor($anonymous);
            foreach ($tasks as $task) {
                $this->em->remove($task);
            }
            $this->em->remove($anonymous);
        }

        $this->em->flush();
        parent::tearDown();
    }

    /**
     * Simule la connexion d'un utilisateur
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

    private function createUser($username, $roles = ['ROLE_USER'])
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username.'@test.com');
        $user->setPassword(
            $this->client->getContainer()->get('security.password_encoder')
                ->encodePassword($user, 'password')
        );
        $user->setRoles($roles);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * Test : Accès aux pages utilisateurs - seul l'admin peut accéder
     */
    public function testUserPagesAccessDeniedForNonAdmin()
    {
        $user = $this->createUser('user_delete_test');
        $this->logUser($user);

        $this->client->request('GET', '/users');
        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test : Accès aux pages utilisateurs - admin peut accéder
     */
    public function testUserPagesAccessAllowedForAdmin()
    {
        $admin = $this->createUser('admin_delete_test', ['ROLE_ADMIN']);
        $this->logUser($admin);

        $this->client->request('GET', '/users');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * Test : Un utilisateur ne peut supprimer que ses propres tâches
     */
    public function testUserCanOnlyDeleteOwnTasks()
    {
        $user = $this->createUser('user_delete_test');
        $otherUser = $this->createUser('other_user_test');

        // Créer une tâche pour l'autre utilisateur
        $task = new Task();
        $task->setTitle('Task delete test');
        $task->setContent('Content');
        $task->setAuthor($otherUser);
        $this->em->persist($task);
        $this->em->flush();

        // L'utilisateur essaie de supprimer la tâche de quelqu'un d'autre
        $this->logUser($user);
        $this->client->request('GET', '/tasks/'.$task->getId().'/delete');

        // Vérifier que l'accès est refusé
        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test : Un utilisateur peut supprimer sa propre tâche
     */
    public function testUserCanDeleteOwnTasks()
    {
        $user = $this->createUser('user_delete_test');

        // Créer une tâche pour l'utilisateur
        $task = new Task();
        $task->setTitle('Task delete test');
        $task->setContent('Content');
        $task->setAuthor($user);
        $this->em->persist($task);
        $this->em->flush();

        $taskId = $task->getId();

        // L'utilisateur supprime sa propre tâche
        $this->logUser($user);
        $this->client->request('GET', '/tasks/'.$taskId.'/delete');

        // Vérifier que la suppression a réussi
        $this->client->followRedirect();
        $deletedTask = $this->em->getRepository(Task::class)->find($taskId);
        $this->assertNull($deletedTask);
    }

    /**
     * Test : Un admin peut supprimer n'importe quelle tâche
     */
    public function testAdminCanDeleteAnyTask()
    {
        $user = $this->createUser('user_delete_test');
        $admin = $this->createUser('admin_delete_test', ['ROLE_ADMIN']);

        // Créer une tâche pour l'utilisateur
        $task = new Task();
        $task->setTitle('Task delete test');
        $task->setContent('Content');
        $task->setAuthor($user);
        $this->em->persist($task);
        $this->em->flush();

        $taskId = $task->getId();

        // L'admin supprime la tâche de l'utilisateur
        $this->logUser($admin);
        $this->client->request('GET', '/tasks/'.$taskId.'/delete');

        // Vérifier que la suppression a réussi
        $this->client->followRedirect();
        $deletedTask = $this->em->getRepository(Task::class)->find($taskId);
        $this->assertNull($deletedTask);
    }

    /**
     * Test : Les tâches anonymes ne peuvent être supprimées que par un admin
     */
    public function testAnonymousTaskCanOnlyBeDeletedByAdmin()
    {
        $user = $this->createUser('user_delete_test');

        // Créer l'utilisateur anonyme
        $anonymous = new User();
        $anonymous->setUsername('anonyme');
        $anonymous->setEmail('anonyme@example.com');
        $anonymous->setPassword(
            $this->client->getContainer()->get('security.password_encoder')
                ->encodePassword($anonymous, 'password')
        );
        $anonymous->setRoles(['ROLE_USER']);
        $this->em->persist($anonymous);
        $this->em->flush();

        // Créer une tâche anonyme
        $task = new Task();
        $task->setTitle('Task delete test');
        $task->setContent('Content');
        $task->setAuthor($anonymous);
        $this->em->persist($task);
        $this->em->flush();

        $taskId = $task->getId();

        // L'utilisateur normal essaie de supprimer la tâche anonyme
        $this->logUser($user);
        $this->client->request('GET', '/tasks/'.$taskId.'/delete');

        // Vérifier que l'accès est refusé
        $this->assertFalse($this->client->getResponse()->isSuccessful());
        $this->assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test : Un admin peut supprimer les tâches anonymes
     */
    public function testAdminCanDeleteAnonymousTask()
    {
        $admin = $this->createUser('admin_delete_test', ['ROLE_ADMIN']);

        // Créer l'utilisateur anonyme
        $anonymous = new User();
        $anonymous->setUsername('anonyme');
        $anonymous->setEmail('anonyme@example.com');
        $anonymous->setPassword(
            $this->client->getContainer()->get('security.password_encoder')
                ->encodePassword($anonymous, 'password')
        );
        $anonymous->setRoles(['ROLE_USER']);
        $this->em->persist($anonymous);
        $this->em->flush();

        // Créer une tâche anonyme
        $task = new Task();
        $task->setTitle('Task delete test');
        $task->setContent('Content');
        $task->setAuthor($anonymous);
        $this->em->persist($task);
        $this->em->flush();

        $taskId = $task->getId();

        // L'admin supprime la tâche anonyme
        $this->logUser($admin);
        $this->client->request('GET', '/tasks/'.$taskId.'/delete');

        // Vérifier que la suppression a réussi
        $this->client->followRedirect();
        $deletedTask = $this->em->getRepository(Task::class)->find($taskId);
        $this->assertNull($deletedTask);
    }
}
