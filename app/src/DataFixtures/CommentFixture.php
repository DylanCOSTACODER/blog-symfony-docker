<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 1; $i <= 10; $i++) {
            $comment = new Comment();
            $comment->setContenu("C'est un commentaire");
            $comment->setAuthor("Dylan");
            $comment->setDateComment(new \DateTime());
            $comment->setArticle($this->getReference('article-1'));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class
        ];
    }
}
