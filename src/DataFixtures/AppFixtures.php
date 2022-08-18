<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create normal user
        $user = new User();
        $user->setEmail("user@bookapi.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);

        // Create an admin
        $admin = new User();
        $admin->setEmail("admin@bookapi.com");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, "password"));
        $manager->persist($admin);

        // Create author
        $listAuthor = [];
        for ($i = 0; $i < 10; $i++) {
            $author = new Author();
            $author->setFirstName("Prénom " . $i);
            $author->setLastName("Nom " . $i);
            $manager->persist($author);
            $listAuthor[] = $author;
        }

        // Create books
        for ($i = 0; $i < 20; $i++) {
            $book = new Book;
            $book->setTitle('Livre '.$i);
            $book->setCoverText('Quatrième de couverture numéro : '.$i);
            $book->setAuthor($listAuthor[array_rand($listAuthor)]);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
