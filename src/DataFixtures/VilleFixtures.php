<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public const VILLE_SAINT_HERBLAIN = 'vile_saint_herblain';
    public const VILLE_HERBLAY = 'vile_herblay';
    public const VILLE_CHERBOURG = 'vile_cherbourg';

    public function load(ObjectManager $manager)
    {
        $ville = new Ville();
        $ville->setNom('SAINT HERBLAIN');
        $ville->setCodePostal('44800');
        $manager->persist($ville);

        //on jaoute une réf de l'objet pour pouvoir l'utiliser dans une autre fixture
        $this->addReference(self::VILLE_SAINT_HERBLAIN , $ville);

        $ville = new Ville();
        $ville->setNom('HERBLAY');
        $ville->setCodePostal('95220');
        $manager->persist($ville);

        $this->addReference(self::VILLE_HERBLAY , $ville);

        $ville = new Ville();
        $ville->setNom('CHERBOURG');
        $ville->setCodePostal('50100');
        $manager->persist($ville);

        $this->addReference(self::VILLE_CHERBOURG , $ville);

        $manager->flush();
    }
}
