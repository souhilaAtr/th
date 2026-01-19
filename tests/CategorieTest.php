<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Tests\Helper\TestEntityFactory;
use App\Entity\Categorie;

class CategorieTest extends WebTestCase
{
     public function testLoggedUserCanCreateCategorie(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        /** @var UserPasswordHasherInterface $hasher */
        $hasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $user = TestEntityFactory::createUser($em, $hasher);
        $client->loginUser($user);

        $client->request('GET', '/categorie/new');
        $this->assertResponseIsSuccessful();

        $nom = 'Comedie '.uniqid();
        $slug = 'comedie-'.uniqid();

        $client->submitForm('Save', [
            'categorie[nom]' => $nom,
            'categorie[slug]' => $slug,
        ]);

        // CRUD standard: redirect après succès
        // $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('/categorie', 303);


        // Vérif DB
        $repo = $em->getRepository(Categorie::class);
        $saved = $repo->findOneBy(['slug' => $slug]);

        self::assertNotNull($saved);
        self::assertSame($nom, $saved->getNom());
    }
}
