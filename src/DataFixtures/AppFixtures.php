<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 100;
    private const NB_CATEGORY = 10;

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


        // $article1 = new Article();
        // $article1->setTitle('Mon premier article !')
        //     ->setCreatedAt(new \DateTimeImmutable())
        //     ->setContent("Le contenu de l'article")
        //     ->setVisible(false)
        //     ->setCategory($categories[0]);
        // $manager->persist($article1);

        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $category = new Article();
            $category->setTitle($faker->realTextBetween(25, 50))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 years')))
                ->setContent($faker->realTextBetween(250, 500))
                ->setVisible($faker->boolean(70))
                ->setCategory($faker->randomElement($categories));
            $manager->persist($category);
        }

        $manager->flush();
    }
}
