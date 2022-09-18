<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $state = ['brouillon', 'publie'];

        for ($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setTitre('Titre de l\'article' . $i);
            $article->setContenu('cest' . $i);
            $article->setState($state[array_rand($state)]);
            $date = new \DateTime();
            $date->modify('-' . $i . ' days');
            $article->setDateCreation(new \DateTime());
            $this->addReference('article-' . $i, $article);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
