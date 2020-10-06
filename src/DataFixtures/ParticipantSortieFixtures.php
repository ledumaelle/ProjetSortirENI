<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipantSortieFixtures extends Fixture implements DependentFixtureInterface
{

    public function getDependencies()
    {
        return array(
            ParticipantFixtures::class,
            SortieFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        /** @var Participant $maelle */
        $maelle = $this->getReference(ParticipantFixtures::MAELLE);
        /** @var Participant $angelique */
        $angelique = $this->getReference(ParticipantFixtures::ANGELIQUE);
        /** @var Participant $thomas */
        $thomas = $this->getReference(ParticipantFixtures::THOMAS);
        /** @var Participant $julien */
        $julien = $this->getReference(ParticipantFixtures::JULIEN);
        /** @var Participant $inactif */
        $inactif = $this->getReference(ParticipantFixtures::INACTIF);


        /** @var Sortie $sortie */
        $sortie = $this->getReference(SortieFixtures::SORTIE_PHILO);
        $sortie->addInscription($maelle);
        $sortie->addInscription($angelique);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_CINEMA);
        $sortie->addInscription($thomas);
        $sortie->addInscription($julien);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_PRIVATE);
        $sortie->addInscription($maelle);
        $sortie->addInscription($julien);
        $sortie->addInscription($angelique);
        $sortie->addInscription($thomas);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_PATE_A_SEL);
        $sortie->addInscription($maelle);
        $sortie->addInscription($julien);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_CONCERT_METAL);
        $sortie->addInscription($thomas);
        $sortie->addInscription($julien);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_PASSEE);
        $sortie->addInscription($thomas);
        $sortie->addInscription($julien);
        $sortie->addInscription($angelique);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_JARDINAGE);
        $sortie->addInscription($thomas);
        $sortie->addInscription($julien);
        $sortie->addInscription($angelique);
        $sortie->addInscription($maelle);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_ORIGAMIE);
        $sortie->addInscription($thomas);
        $sortie->addInscription($julien);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_ZOO);
        $sortie->addInscription($maelle);

        $manager->persist($sortie);

        $manager->flush();
    }
}
