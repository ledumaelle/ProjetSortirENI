<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{

    const SORTIE_ZOO = 'sortie_zoo';
    const SORTIE_ORIGAMIE = 'sortie_origamie';
    const SORTIE_PERLES = 'sortie_perles';
    const SORTIE_PHILO = 'sortie_philo';
    const SORTIE_CONCERT_METAL = 'sortie_concert_metal';
    const SORTIE_JARDINAGE = 'sortie_jardinage';
    const SORTIE_CINEMA = 'sortie_cinema';
    const SORTIE_PATE_A_SEL = 'sortie_pate_a_sel';
    const SORTIE_SORTIE_PASSEE = 'sortie_sortie_passee';
    const SORTIE_SORTIE_PRIVATE = 'sortie_private';
    const SORTIE_SORTIE_ANNULEE = 'sortie_annulee';
    const SORTIE_SORTIE_ARCHIVEE = 'sortie_archivee';

    public function getDependencies()
    {
        return array(
            EtatFixtures::class,
            ParticipantFixtures::class,
            CampusFixtures::class,
            LieuFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");

        // ------------------------ SORTIE ZOO
        $sortie = new Sortie();
        $sortie->setNom("Sortie au zoo");
        $date = $faker->dateTimeBetween("now","now");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(8);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_ACTIVITE_EN_COURS));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::MAELLE));
        $sortie->setDuree(2);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_LE_CAP));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_SAINT_HERBLAIN));
        $sortie->setInfosSortie("ça va être la fiesta boum boum !");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_ZOO , $sortie);

        // ------------------------ SORTIE ORIGAMIE
        $sortie = new Sortie();
        $sortie->setNom("Origamie");
        $date = $faker->dateTimeBetween("now","+15 days");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(5);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_CLOTUREE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::ANGELIQUE));
        $sortie->setDuree(4);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_CINEMA));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $sortie->setInfosSortie("Origamiiiiiiie !! ");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_ORIGAMIE , $sortie);

        // ------------------------ SORTIE PERLES
        $sortie = new Sortie();
        $sortie->setNom("Perles");
        $date = $faker->dateTimeBetween("now","+2 days");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($faker->dateTimeBetween("-3 days", $date));
        $sortie->setNbInscriptionsMax(12);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_CLOTUREE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::THOMAS));
        $sortie->setDuree(1);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_PANAME));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $sortie->setInfosSortie("Let's go to paname !!");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_PERLES , $sortie);

        // ------------------------ SORTIE PHILO
        $sortie = new Sortie();
        $sortie->setNom("Philo");
        $date = $faker->dateTimeBetween("now","+2 days");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($faker->dateTimeBetween("-3 days", $date));
        $sortie->setNbInscriptionsMax(5);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_CLOTUREE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::ANGELIQUE));
        $sortie->setDuree(1);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_LE_CAP));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_LA_ROCHE_SUR_YON));
        $sortie->setInfosSortie("Un cours de philo comme on les aime LOL");
        $sortie->setDateCreated();
        $sortie->setDateModified();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_PHILO , $sortie);

        // ------------------------ SORTIE CONCERT METAL
        $sortie = new Sortie();
        $sortie->setNom("Concert métal");
        $date = $faker->dateTimeBetween("now","+20 days");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($faker->dateTimeBetween("-7 days", $date));
        $sortie->setNbInscriptionsMax(2);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_OUVERTE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::JULIEN));
        $sortie->setDuree(9);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_CINEMA));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_LA_ROCHE_SUR_YON));
        $sortie->setInfosSortie("ça va zouker ou pas du tout mdr");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_CONCERT_METAL , $sortie);

        // ------------------------ SORTIE JARDINAGE
        $sortie = new Sortie();
        $sortie->setNom("Jardinage");
        $date = $faker->dateTimeBetween("now","+1 months");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($faker->dateTimeBetween("-15 days", $date));
        $sortie->setNbInscriptionsMax(3);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_OUVERTE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::THOMAS));
        $sortie->setDuree(4);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_ZOO));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $sortie->setInfosSortie("Je suis une fleur je suis une très jolie fleur. :) (P'tit ref)");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_JARDINAGE , $sortie);


        // ------------------------ SORTIE CINEMA
        $sortie = new Sortie();
        $sortie->setNom("Cinéma");
        $date = $faker->dateTimeBetween("now","+5 days");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(3);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_CREEE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::MAELLE));
        $sortie->setDuree(2);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_CINEMA));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $sortie->setInfosSortie("Creed 2 ! ");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_CINEMA , $sortie);


        // ------------------------ SORTIE PATE A SEL
        $sortie = new Sortie();
        $sortie->setNom("Pâte à sel");
        $date = $faker->dateTimeBetween("now","+1 months");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($faker->dateTimeBetween("-15 days", $date));
        $sortie->setNbInscriptionsMax(3);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_OUVERTE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::ANGELIQUE));
        $sortie->setDuree(1);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_PANAME));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_LA_ROCHE_SUR_YON));
        $sortie->setInfosSortie("Go faire de la pate à sel LOL !! :)");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_PATE_A_SEL , $sortie);


        // ------------------------ SORTIE SORTIE PASSEE
        $sortie = new Sortie();
        $sortie->setNom("Sortie passée");
        $date = $faker->dateTimeBetween("-1 months","now");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(5);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_PASSEE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::MAELLE));
        $sortie->setDuree(8);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_LE_CAP));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $sortie->setInfosSortie("Go se faire une journée entière à la playa + faire du bateau hehe :) !");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_SORTIE_PASSEE , $sortie);

        // ------------------------ SORTIE SORTIE PRIVATE
        $sortie = new Sortie();
        $sortie->setNom("Sortie PRIVATE");
        $date = $faker->dateTimeBetween("now","+1 months");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(4);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_OUVERTE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::MAELLE));
        $sortie->setDuree(1);
        $sortie->setIsPrivate(true);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_PANAME));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_LA_ROCHE_SUR_YON));
        $sortie->setInfosSortie("Boire un verre dans un bar!");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_SORTIE_PRIVATE , $sortie);



        // ------------------------ SORTIE SORTIE ANNULEE
        $sortie = new Sortie();
        $sortie->setNom("Sortie annulée");
        $date = $faker->dateTimeBetween("now","+1 months");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(3);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_ANNULEE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::ANGELIQUE));
        $sortie->setDuree(1);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_CINEMA));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_SAINT_HERBLAIN));
        $sortie->setInfosSortie("La sortie est annulée donc on s'en balek ! ");
        $sortie->setMotifAnnulation("Motif de la sortie annulée : Elle est annulée et tu vas faire quoi ? NADA ! ");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_SORTIE_ANNULEE , $sortie);


        // ------------------------ SORTIE SORTIE ARCHIVEE
        $sortie = new Sortie();
        $sortie->setNom("Sortie archivée");
        $date = $faker->dateTimeBetween("-2 months","-1 months");
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);
        $sortie->setNbInscriptionsMax(12);
        $sortie->setEtat($this->getReference(EtatFixtures::ETAT_PASSEE));
        $sortie->setOrganisateur($this->getReference(ParticipantFixtures::MAELLE));
        $sortie->setDuree(8);
        $sortie->setIsPrivate(false);
        $sortie->setLieu($this->getReference(LieuFixtures::LIEU_ZOO));
        $sortie->setSiteOrganisateur($this->getReference(CampusFixtures::CAMPUS_SAINT_HERBLAIN));
        $sortie->setInfosSortie("La sortie a été réalisé ya 2 mois donc elle est archivée !");
        $sortie->setDateCreated();
        $manager->persist($sortie);

        $this->addReference(self::SORTIE_SORTIE_ARCHIVEE , $sortie);

        $manager->flush();
    }
}
