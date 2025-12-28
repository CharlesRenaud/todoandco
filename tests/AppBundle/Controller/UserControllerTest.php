<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\User;

class UserControllerTest extends WebTestCase
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
        // Supprime les utilisateurs créés lors des tests
        $users = $this->em->getRepository(User::class)->findByUsername('user_test');
        foreach ($users as $user) {
            $this->em->remove($user);
        }

        $users = $this->em->getRepository(User::class)->findByUsername('edit_user');
        foreach ($users as $user) {
            $this->em->remove($user);
        }

        $this->em->flush();
        parent::tearDown();
    }

    /**
     * Test : création d'un utilisateur avec un rôle choisi
     */
    public function testCreateUserWithRole()
    {
        // Accès à la page de création
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        // Soumission du formulaire
        $form = $crawler->selectButton('Ajouter')->form([
            'user[username]' => 'user_test',
            'user[email]'    => 'user@test.com',
            'user[password][first]'  => 'password',
            'user[password][second]' => 'password',
            'user[roles]'    => 'ROLE_ADMIN',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        // Vérification en base
        $user = $this->em->getRepository(User::class)
            ->findOneByUsername('user_test');

        $this->assertNotNull($user);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
    }

    /**
     * Test : modification du rôle d’un utilisateur
     */
    public function testEditUserRole()
    {
        // Création utilisateur initial
        $user = new User();
        $user->setUsername('edit_user');
        $user->setEmail('edit@test.com');
        $user->setPassword('encoded');
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        // Accès à la page d'édition
        $crawler = $this->client->request('GET', '/users/'.$user->getId().'/edit');
        $this->assertTrue($this->client->getResponse()->isSuccessful());

        // Changement de rôle
        $form = $crawler->selectButton('Modifier')->form([
            'user[roles]' => 'ROLE_ADMIN',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        // Recharger depuis la BD
        $this->em->clear();
        $userUpdated = $this->em->getRepository(User::class)->findOneByUsername('edit_user');

        $this->assertContains('ROLE_ADMIN', $userUpdated->getRoles());
    }
}
