<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category();
        $category->setName('Shoes')
            ->setDifferentSizePrices(0);
        $manager->persist($category);

        $category = new Category();
        $category->setName('Jewelry')
            ->setDifferentSizePrices(1);
        $manager->persist($category);

        $manager->flush();
    }
}
