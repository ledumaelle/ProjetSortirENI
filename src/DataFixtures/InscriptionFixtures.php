<?php

namespace App\DataFixtures;

use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InscriptionFixtures extends Fixture implements DependentFixtureInterface
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

        $inscriptionMaelleToPhilo = new Inscription();
        $inscriptionMaelleToPhilo->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToPhilo->setParticipant($maelle);
        $inscriptionMaelleToPhilo->setSortie($sortie);

        $manager->persist($inscriptionMaelleToPhilo);

        $inscriptionAngeliqueToPhilo = new Inscription();
        $inscriptionAngeliqueToPhilo->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToPhilo->setParticipant($angelique);
        $inscriptionAngeliqueToPhilo->setSortie($sortie);

        $manager->persist($inscriptionAngeliqueToPhilo);

        $sortie->addInscription($inscriptionMaelleToPhilo);
        $sortie->addInscription($inscriptionAngeliqueToPhilo);

        $manager->persist($sortie);




        $sortie = $this->getReference(SortieFixtures::SORTIE_CINEMA);

        $inscriptionThomasToCinema = new Inscription();
        $inscriptionThomasToCinema->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToCinema->setParticipant($thomas);
        $inscriptionThomasToCinema->setSortie($sortie);

        $manager->persist($inscriptionThomasToCinema);

        $inscriptionJulienToCinema = new Inscription();
        $inscriptionJulienToCinema->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToCinema->setParticipant($julien);
        $inscriptionJulienToCinema->setSortie($sortie);

        $manager->persist($inscriptionJulienToCinema);

        $sortie->addInscription($inscriptionThomasToCinema);
        $sortie->addInscription($inscriptionJulienToCinema);

        $manager->persist($sortie);






        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_PRIVATE);

        $inscriptionMaelleToPrivate = new Inscription();
        $inscriptionMaelleToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToPrivate->setParticipant($maelle);
        $inscriptionMaelleToPrivate->setSortie($sortie);

        $manager->persist($inscriptionMaelleToPrivate);

        $inscriptionAngeliqueToPrivate = new Inscription();
        $inscriptionAngeliqueToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToPrivate->setParticipant($angelique);
        $inscriptionAngeliqueToPrivate->setSortie($sortie);

        $manager->persist($inscriptionAngeliqueToPrivate);

        $inscriptionThomasToPrivate = new Inscription();
        $inscriptionThomasToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToPrivate->setParticipant($thomas);
        $inscriptionThomasToPrivate->setSortie($sortie);

        $manager->persist($inscriptionThomasToPrivate);

        $inscriptionJulienToPrivate = new Inscription();
        $inscriptionJulienToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToPrivate->setParticipant($julien);
        $inscriptionJulienToPrivate->setSortie($sortie);

        $manager->persist($inscriptionJulienToPrivate);

        $sortie->addInscription($inscriptionMaelleToPrivate);
        $sortie->addInscription($inscriptionAngeliqueToPrivate);
        $sortie->addInscription($inscriptionThomasToPrivate);
        $sortie->addInscription($inscriptionJulienToPrivate);

        $manager->persist($sortie);






        $sortie = $this->getReference(SortieFixtures::SORTIE_PATE_A_SEL);

        $inscriptionMaelleToPate = new Inscription();
        $inscriptionMaelleToPate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToPate->setParticipant($maelle);
        $inscriptionMaelleToPate->setSortie($sortie);

        $manager->persist($inscriptionMaelleToPate);

        $inscriptionJulienToPate = new Inscription();
        $inscriptionJulienToPate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToPate->setParticipant($julien);
        $inscriptionJulienToPate->setSortie($sortie);

        $manager->persist($inscriptionJulienToPate);

        $sortie->addInscription($inscriptionMaelleToPate);
        $sortie->addInscription($inscriptionJulienToPate);

        $manager->persist($sortie);






        $sortie = $this->getReference(SortieFixtures::SORTIE_CONCERT_METAL);

        $inscriptionThomasToMetal = new Inscription();
        $inscriptionThomasToMetal->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToMetal->setParticipant($thomas);
        $inscriptionThomasToMetal->setSortie($sortie);

        $manager->persist($inscriptionThomasToMetal);

        $inscriptionJulienToMetal = new Inscription();
        $inscriptionJulienToMetal->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToMetal->setParticipant($julien);
        $inscriptionJulienToMetal->setSortie($sortie);

        $manager->persist($inscriptionJulienToMetal);

        $sortie->addInscription($inscriptionThomasToMetal);
        $sortie->addInscription($inscriptionJulienToMetal);

        $manager->persist($sortie);






        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_PASSEE);

        $inscriptionThomasToSortiePassee = new Inscription();
        $inscriptionThomasToSortiePassee->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToSortiePassee->setParticipant($thomas);
        $inscriptionThomasToSortiePassee->setSortie($sortie);

        $manager->persist($inscriptionThomasToSortiePassee);

        $inscriptionJulienToSortiePassee = new Inscription();
        $inscriptionJulienToSortiePassee->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToSortiePassee->setParticipant($julien);
        $inscriptionJulienToSortiePassee->setSortie($sortie);

        $manager->persist($inscriptionJulienToSortiePassee);

        $inscriptionAngeliqueToSortiePassee = new Inscription();
        $inscriptionAngeliqueToSortiePassee->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToSortiePassee->setParticipant($angelique);
        $inscriptionAngeliqueToSortiePassee->setSortie($sortie);

        $manager->persist($inscriptionAngeliqueToSortiePassee);

        $sortie->addInscription($inscriptionThomasToSortiePassee);
        $sortie->addInscription($inscriptionJulienToSortiePassee);
        $sortie->addInscription($inscriptionAngeliqueToSortiePassee);

        $manager->persist($sortie);






        $sortie = $this->getReference(SortieFixtures::SORTIE_JARDINAGE);

        $inscriptionMaelleToJardinage = new Inscription();
        $inscriptionMaelleToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToJardinage->setParticipant($maelle);
        $inscriptionMaelleToJardinage->setSortie($sortie);

        $manager->persist($inscriptionMaelleToJardinage);

        $inscriptionAngeliqueToJardinage = new Inscription();
        $inscriptionAngeliqueToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToJardinage->setParticipant($angelique);
        $inscriptionAngeliqueToJardinage->setSortie($sortie);

        $manager->persist($inscriptionAngeliqueToJardinage);

        $inscriptionJulienToJardinage = new Inscription();
        $inscriptionJulienToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToJardinage->setParticipant($julien);
        $inscriptionJulienToJardinage->setSortie($sortie);

        $manager->persist($inscriptionJulienToJardinage);

        $inscriptionThomasToJardinage = new Inscription();
        $inscriptionThomasToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToJardinage->setParticipant($thomas);
        $inscriptionThomasToJardinage->setSortie($sortie);

        $manager->persist($inscriptionThomasToJardinage);


        $sortie->addInscription($inscriptionMaelleToJardinage);
        $sortie->addInscription($inscriptionAngeliqueToJardinage);
        $sortie->addInscription($inscriptionJulienToJardinage);
        $sortie->addInscription($inscriptionThomasToJardinage);

        $manager->persist($sortie);

        $sortie = $this->getReference(SortieFixtures::SORTIE_ORIGAMIE);

        $inscriptionJulienToOrigamie = new Inscription();
        $inscriptionJulienToOrigamie->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToOrigamie->setParticipant($julien);
        $inscriptionJulienToOrigamie->setSortie($sortie);

        $manager->persist($inscriptionJulienToOrigamie);

        $inscriptionThomasToOrigamie = new Inscription();
        $inscriptionThomasToOrigamie->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToOrigamie->setParticipant($thomas);
        $inscriptionThomasToOrigamie->setSortie($sortie);

        $manager->persist($inscriptionThomasToOrigamie);

        $sortie->addInscription($inscriptionJulienToOrigamie);
        $sortie->addInscription($inscriptionThomasToOrigamie);

        $manager->persist($sortie);




        $sortie = $this->getReference(SortieFixtures::SORTIE_ZOO);

        $inscriptionMaelleToZoo = new Inscription();
        $inscriptionMaelleToZoo->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToZoo->setParticipant($maelle);
        $inscriptionMaelleToZoo->setSortie($sortie);

        $manager->persist($inscriptionMaelleToZoo);

        $sortie->addInscription($inscriptionMaelleToZoo);

        $manager->persist($sortie);

        $manager->flush();
    }
}
