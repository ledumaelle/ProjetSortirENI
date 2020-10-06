<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public const CAMPUS_SAINT_HERBLAIN = 'saint_herblain';
    public const CAMPUS_CHARTRES_DE_BRETAGNE = 'chartres_de_bretagne';
    public const CAMPUS_LA_ROCHE_SUR_YON = 'la_roche_sur_yon';

    public function load(ObjectManager $manager)
    {
        // create 3 campus
        $campus = new Campus();
        $campus->setNom('SAINT HERBLAIN');
        $manager->persist($campus);

        //on jaoute une réf de l'objet pour pouvoir l'utiliser dans une autre fixture
        $this->addReference(self::CAMPUS_SAINT_HERBLAIN , $campus);

        $campus = new Campus();
        $campus->setNom('CHARTRES DE BRETAGNE');
        $manager->persist($campus);

        $this->addReference(self::CAMPUS_CHARTRES_DE_BRETAGNE , $campus);

        $campus = new Campus();
        $campus->setNom('LA ROCHE SUR YON');
        $manager->persist($campus);

        $this->addReference(self::CAMPUS_LA_ROCHE_SUR_YON , $campus);

        $manager->flush();
    }
}
