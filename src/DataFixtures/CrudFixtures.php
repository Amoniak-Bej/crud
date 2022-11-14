<?php

namespace App\DataFixtures;

use App\Entity\Crud;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CrudFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $crud = new Crud();
        $crud->setHeader('d');
        $crud->setContent('d');
        $manager->persist($crud);

        $crud2 = new Crud();
        $crud2->setHeader('a');
        $crud2->setContent('a');
        $manager->persist($crud2);

        $manager->flush();
    }
}
