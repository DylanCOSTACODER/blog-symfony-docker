<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('Jean');
        $user->setPassword('$2y$13$ym4xaxlVeGAc1X9sIZY7V.9PMGoFzUD8dlmBkcaFk.VA8w88bYUTe');
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('$2y$13$ym4xaxlVeGAc1X9sIZY7V.9PMGoFzUD8dlmBkcaFk.VA8w88bYUTe');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }
}
