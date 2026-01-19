<?php

namespace App\Tests\Helper;

use App\Entity\Categorie;
use App\Entity\Utilisateur;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class TestEntityFactory
{
    public static function createUser(
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher,
        ?string $email = null,
        string $plainPassword = 'password123'
    ): Utilisateur {
        $user = new Utilisateur();
        $user->setEmail($email ?? ('user_'.uniqid().'@test.local'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($hasher->hashPassword($user, $plainPassword));

        // Si ton Utilisateur a createdAt obligatoire
        if (method_exists($user, 'setCreatedAt')) {
            $user->setCreatedAt(new DateTimeImmutable());
        }

        $em->persist($user);
        $em->flush();

        return $user;
    }

    public static function createCategorie(
        EntityManagerInterface $em,
        ?string $nom = null,
        ?string $slug = null
    ): Categorie {
        $categorie = new Categorie();
        $categorie->setNom($nom ?? ('Cat '.uniqid()));
        $categorie->setSlug($slug ?? ('cat-'.uniqid()));

        // Si ton Categorie a createdAt obligatoire et pas en __construct()
        if (method_exists($categorie, 'setCreatedAt')) {
            $categorie->setCreatedAt(new DateTimeImmutable());
        }

        $em->persist($categorie);
        $em->flush();

        return $categorie;
    }
}
