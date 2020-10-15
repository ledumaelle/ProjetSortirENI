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


        // ------------------------ SORTIE ZOO
        /** @var Sortie $sortie */
        $sortie = $this->getReference(SortieFixtures::SORTIE_ZOO);

        $inscriptionMaelleToZoo = new Inscription();
        $inscriptionMaelleToZoo->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToZoo->setParticipant($maelle);
        $inscriptionMaelleToZoo->setSortie($sortie);
        $inscriptionMaelleToZoo->setDateCreated();

        $manager->persist($inscriptionMaelleToZoo);

        $sortie->addInscription($inscriptionMaelleToZoo);

        $sortie->setDateModified();

        $manager->persist($sortie);



        // ------------------------ SORTIE ORIGAMIE
        $sortie = $this->getReference(SortieFixtures::SORTIE_ORIGAMIE);

        $inscriptionJulienToOrigamie = new Inscription();
        $inscriptionJulienToOrigamie->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToOrigamie->setParticipant($julien);
        $inscriptionJulienToOrigamie->setSortie($sortie);
        $inscriptionJulienToOrigamie->setDateCreated();

        $manager->persist($inscriptionJulienToOrigamie);

        $inscriptionThomasToOrigamie = new Inscription();
        $inscriptionThomasToOrigamie->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToOrigamie->setParticipant($thomas);
        $inscriptionThomasToOrigamie->setSortie($sortie);
        $inscriptionThomasToOrigamie->setDateCreated();

        $manager->persist($inscriptionThomasToOrigamie);

        $sortie->addInscription($inscriptionJulienToOrigamie);
        $sortie->addInscription($inscriptionThomasToOrigamie);

        $sortie->setDateModified();
        $manager->persist($sortie);



        // ------------------------ SORTIE PHILO
        $sortie = $this->getReference(SortieFixtures::SORTIE_PHILO);

        $inscriptionMaelleToPhilo = new Inscription();
        $inscriptionMaelleToPhilo->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToPhilo->setParticipant($maelle);
        $inscriptionMaelleToPhilo->setSortie($sortie);
        $inscriptionMaelleToPhilo->setDateCreated();

        $manager->persist($inscriptionMaelleToPhilo);

        $inscriptionAngeliqueToPhilo = new Inscription();
        $inscriptionAngeliqueToPhilo->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToPhilo->setParticipant($angelique);
        $inscriptionAngeliqueToPhilo->setSortie($sortie);
        $inscriptionAngeliqueToPhilo->setDateCreated();

        $manager->persist($inscriptionAngeliqueToPhilo);

        $sortie->addInscription($inscriptionMaelleToPhilo);
        $sortie->addInscription($inscriptionAngeliqueToPhilo);

        $sortie->setDateModified();

        $manager->persist($sortie);


        // ------------------------ SORTIE CONCERT METAL
        $sortie = $this->getReference(SortieFixtures::SORTIE_CONCERT_METAL);

        $inscriptionThomasToMetal = new Inscription();
        $inscriptionThomasToMetal->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToMetal->setParticipant($thomas);
        $inscriptionThomasToMetal->setSortie($sortie);
        $inscriptionThomasToMetal->setDateCreated();

        $manager->persist($inscriptionThomasToMetal);

        $inscriptionJulienToMetal = new Inscription();
        $inscriptionJulienToMetal->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToMetal->setParticipant($julien);
        $inscriptionJulienToMetal->setSortie($sortie);
        $inscriptionJulienToMetal->setDateCreated();

        $manager->persist($inscriptionJulienToMetal);

        $sortie->addInscription($inscriptionThomasToMetal);
        $sortie->addInscription($inscriptionJulienToMetal);

        $sortie->setDateModified();

        $manager->persist($sortie);



        // ------------------------ SORTIE JARDINAGE
        $sortie = $this->getReference(SortieFixtures::SORTIE_JARDINAGE);

        $inscriptionMaelleToJardinage = new Inscription();
        $inscriptionMaelleToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToJardinage->setParticipant($maelle);
        $inscriptionMaelleToJardinage->setSortie($sortie);
        $inscriptionMaelleToJardinage->setDateCreated();

        $manager->persist($inscriptionMaelleToJardinage);

        $inscriptionAngeliqueToJardinage = new Inscription();
        $inscriptionAngeliqueToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToJardinage->setParticipant($angelique);
        $inscriptionAngeliqueToJardinage->setSortie($sortie);
        $inscriptionAngeliqueToJardinage->setDateCreated();

        $manager->persist($inscriptionAngeliqueToJardinage);

        $inscriptionJulienToJardinage = new Inscription();
        $inscriptionJulienToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToJardinage->setParticipant($julien);
        $inscriptionJulienToJardinage->setSortie($sortie);
        $inscriptionJulienToJardinage->setDateCreated();

        $manager->persist($inscriptionJulienToJardinage);

        $inscriptionThomasToJardinage = new Inscription();
        $inscriptionThomasToJardinage->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToJardinage->setParticipant($thomas);
        $inscriptionThomasToJardinage->setSortie($sortie);
        $inscriptionThomasToJardinage->setDateCreated();

        $manager->persist($inscriptionThomasToJardinage);

        $sortie->addInscription($inscriptionMaelleToJardinage);
        $sortie->addInscription($inscriptionAngeliqueToJardinage);
        $sortie->addInscription($inscriptionJulienToJardinage);
        $sortie->addInscription($inscriptionThomasToJardinage);

        $sortie->setDateModified();

        $manager->persist($sortie);



        // ------------------------ SORTIE PATE A SEL
        $sortie = $this->getReference(SortieFixtures::SORTIE_PATE_A_SEL);

        $inscriptionMaelleToPate = new Inscription();
        $inscriptionMaelleToPate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToPate->setParticipant($maelle);
        $inscriptionMaelleToPate->setSortie($sortie);
        $inscriptionMaelleToPate->setDateCreated();

        $manager->persist($inscriptionMaelleToPate);

        $inscriptionJulienToPate = new Inscription();
        $inscriptionJulienToPate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToPate->setParticipant($julien);
        $inscriptionJulienToPate->setSortie($sortie);
        $inscriptionJulienToPate->setDateCreated();

        $manager->persist($inscriptionJulienToPate);

        $sortie->addInscription($inscriptionMaelleToPate);
        $sortie->addInscription($inscriptionJulienToPate);

        $sortie->setDateModified();

        $manager->persist($sortie);




        // ------------------------ SORTIE SORTIE PASSEE
        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_PASSEE);

        $inscriptionThomasToSortiePassee = new Inscription();
        $inscriptionThomasToSortiePassee->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToSortiePassee->setParticipant($thomas);
        $inscriptionThomasToSortiePassee->setSortie($sortie);
        $inscriptionThomasToSortiePassee->setDateCreated();

        $manager->persist($inscriptionThomasToSortiePassee);

        $inscriptionJulienToSortiePassee = new Inscription();
        $inscriptionJulienToSortiePassee->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToSortiePassee->setParticipant($julien);
        $inscriptionJulienToSortiePassee->setSortie($sortie);
        $inscriptionJulienToSortiePassee->setDateCreated();

        $manager->persist($inscriptionJulienToSortiePassee);

        $inscriptionAngeliqueToSortiePassee = new Inscription();
        $inscriptionAngeliqueToSortiePassee->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToSortiePassee->setParticipant($angelique);
        $inscriptionAngeliqueToSortiePassee->setSortie($sortie);
        $inscriptionAngeliqueToSortiePassee->setDateCreated();

        $manager->persist($inscriptionAngeliqueToSortiePassee);

        $sortie->addInscription($inscriptionThomasToSortiePassee);
        $sortie->addInscription($inscriptionJulienToSortiePassee);
        $sortie->addInscription($inscriptionAngeliqueToSortiePassee);

        $sortie->setDateModified();

        $manager->persist($sortie);



        // ------------------------ SORTIE PRIVATE
        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_PRIVATE);

        $inscriptionMaelleToPrivate = new Inscription();
        $inscriptionMaelleToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToPrivate->setParticipant($maelle);
        $inscriptionMaelleToPrivate->setSortie($sortie);
        $inscriptionMaelleToPrivate->setDateCreated();

        $manager->persist($inscriptionMaelleToPrivate);

        $inscriptionAngeliqueToPrivate = new Inscription();
        $inscriptionAngeliqueToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToPrivate->setParticipant($angelique);
        $inscriptionAngeliqueToPrivate->setSortie($sortie);
        $inscriptionAngeliqueToPrivate->setDateCreated();

        $manager->persist($inscriptionAngeliqueToPrivate);

        $inscriptionThomasToPrivate = new Inscription();
        $inscriptionThomasToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionThomasToPrivate->setParticipant($thomas);
        $inscriptionThomasToPrivate->setSortie($sortie);
        $inscriptionThomasToPrivate->setDateCreated();

        $manager->persist($inscriptionThomasToPrivate);

        $inscriptionJulienToPrivate = new Inscription();
        $inscriptionJulienToPrivate->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToPrivate->setParticipant($julien);
        $inscriptionJulienToPrivate->setSortie($sortie);
        $inscriptionJulienToPrivate->setDateCreated();

        $manager->persist($inscriptionJulienToPrivate);

        $sortie->addInscription($inscriptionMaelleToPrivate);
        $sortie->addInscription($inscriptionAngeliqueToPrivate);
        $sortie->addInscription($inscriptionThomasToPrivate);
        $sortie->addInscription($inscriptionJulienToPrivate);

        $sortie->setDateModified();

        $manager->persist($sortie);


        // ------------------------ SORTIE ANNULEE
        $sortie = $this->getReference(SortieFixtures::SORTIE_SORTIE_ANNULEE);

        $inscriptionMaelleToAnnulation = new Inscription();
        $inscriptionMaelleToAnnulation->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionMaelleToAnnulation->setParticipant($maelle);
        $inscriptionMaelleToAnnulation->setSortie($sortie);
        $inscriptionMaelleToAnnulation->setDateCreated();

        $manager->persist($inscriptionMaelleToAnnulation);

        $inscriptionAngeliqueToAnnulation = new Inscription();
        $inscriptionAngeliqueToAnnulation->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionAngeliqueToAnnulation->setParticipant($angelique);
        $inscriptionAngeliqueToAnnulation->setSortie($sortie);
        $inscriptionAngeliqueToAnnulation->setDateCreated();

        $manager->persist($inscriptionAngeliqueToAnnulation);

        $inscriptionJulienToAnnulation = new Inscription();
        $inscriptionJulienToAnnulation->setDateInscription($sortie->getDateHeureDebut());
        $inscriptionJulienToAnnulation->setParticipant($julien);
        $inscriptionJulienToAnnulation->setSortie($sortie);
        $inscriptionJulienToAnnulation->setDateCreated();

        $manager->persist($inscriptionJulienToAnnulation);

        $sortie->addInscription($inscriptionMaelleToAnnulation);
        $sortie->addInscription($inscriptionAngeliqueToAnnulation);
        $sortie->addInscription($inscriptionJulienToAnnulation);

        $sortie->setDateModified();

        $manager->persist($sortie);


        //FIN DES JEUX D'ESSAIS

        $manager->flush();
    }
}
