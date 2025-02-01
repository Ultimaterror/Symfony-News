<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    private const NB_ARTICLES = 100;
    private const NB_CATEGORY = 10;
    private const NB_WRITERS = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        $categories = [];
        for ($i = 0; $i < self::NB_CATEGORY; $i++) {
            $category = new Category();
            $category->setName($faker->unique()->word());
            $manager->persist($category);
            $categories[] = $category;
        }

        $admin = new User();
        $admin->setEmail("admin@example.com")
            ->setRoles(["ROLE_ADMIN", "ROLE_USER"])
            ->setPassword($this->hasher->hashPassword($admin, 'adminPassword'));
        $manager->persist($admin);

        $user = new User();
        $user->setEmail("user@example.com")
            ->setRoles(["ROLE_USER"])
            ->setPassword($this->hasher->hashPassword($user, 'userPassword'));
        $manager->persist($user);

        $authors = [];
        $firstWriter = new User();
        $firstWriter->setEmail("writer@example.com")
            ->setRoles(["ROLE_WRITER", "ROLE_USER"])
            ->setPassword($this->hasher->hashPassword($firstWriter, 'writerPassword'));
        $manager->persist($firstWriter);
        $authors[] = $firstWriter;

        for ($i = 0; $i < self::NB_CATEGORY; $i++) {
            $author = new User();
            $author->setEmail($faker->unique()->email())
                ->setRoles(["ROLE_WRITER", "ROLE_USER"])
                ->setPassword($this->hasher->hashPassword($author, 'writerPassword'));
            $manager->persist($author);
            $authors[] = $author;
        }

        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $category = new Article();
            $category->setTitle($faker->realTextBetween(25, 50))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years')))
                ->setContent($faker->realTextBetween(250, 500))
                ->setVisible($faker->boolean(70))
                ->setCategory($faker->randomElement($categories))
                ->setAuthor($faker->randomElement($authors));
            $manager->persist($category);
        }

        $manager->flush();
    }
}
