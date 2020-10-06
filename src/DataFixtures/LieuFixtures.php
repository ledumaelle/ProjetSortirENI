<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public const LIEU_ZOO = 'lieu_zoo';
    public const LIEU_LE_CAP = 'lieu_le_cap';
    public const LIEU_PANAME = 'lieu_paname';
    public const LIEU_CINEMA = 'lieu_cinema';

    public function getDependencies()
    {
        return array(
            VilleFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        $lieu = new Lieu();
        $lieu->setNom('LE CAP');
        $lieu->setRue('3 rue de la pomme verte');
        $lieu->setLatitude(48.862725);
        $lieu->setLongitude(2.287592);
        $lieu->setVille($this->getReference(VilleFixtures::VILLE_SAINT_HERBLAIN));

        $manager->persist($lieu);

        $this->addReference(self::LIEU_LE_CAP , $lieu);

        $lieu = new Lieu();
        $lieu->setNom('Zoo');
        $lieu->setRue('8 rue des boomers');
        $lieu->setLatitude(48.862725);
        $lieu->setLongitude(2.287592);
        $lieu->setVille($this->getReference(VilleFixtures::VILLE_SAINT_HERBLAIN));

        $manager->persist($lieu);

        $this->addReference(self::LIEU_ZOO , $lieu);

        $lieu = new Lieu();
        $lieu->setNom('Cinéma');
        $lieu->setRue('11 impasse des roues de secours');
        $lieu->setLatitude(48.862725);
        $lieu->setLongitude(2.287592);
        $lieu->setVille($this->getReference(VilleFixtures::VILLE_HERBLAY));

        $manager->persist($lieu);

        $this->addReference(self::LIEU_CINEMA , $lieu);

        $lieu = new Lieu();
        $lieu->setNom('Paname');
        $lieu->setRue('7 rue de la Réupublique');
        $lieu->setLatitude(48.862725);
        $lieu->setLongitude(2.287592);
        $lieu->setVille($this->getReference(VilleFixtures::VILLE_CHERBOURG));

        $manager->persist($lieu);

        $this->addReference(self::LIEU_PANAME , $lieu);

        $manager->flush();
    }
}
