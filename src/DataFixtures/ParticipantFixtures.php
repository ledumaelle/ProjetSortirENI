<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    public const MAELLE = 'maelle';
    public const ANGELIQUE = 'angelique';
    public const THOMAS = 'thomas';
    public const JULIEN = 'julien';
    public const INACTIF = 'inactif';

    public function getDependencies()
    {
        return array(
            CampusFixtures::class,
        );
    }

    public function load(ObjectManager $manager)
    {
        $participant = new Participant();
        $participant->setNom('LE ROUX');
        $participant->setPrenom('Angélique');
        $participant->setTelephone('0606060606');
        $participant->setMail('angelique.le-roux@cooperl.com');
        $participant->setMotPasse('leroux');
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        //on jaoute une réf de l'objet pour pouvoir l'utiliser dans une autre fixture
        $this->addReference(self::ANGELIQUE , $participant);

        $participant = new Participant();
        $participant->setNom('LE DU');
        $participant->setPrenom('Maëlle');
        $participant->setTelephone('0606060606');
        $participant->setMail('maelle.le-du@cooperl.com');
        $participant->setMotPasse('ledu');
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        $this->addReference(self::MAELLE , $participant);

        $participant = new Participant();
        $participant->setNom('COLLIN');
        $participant->setPrenom('Thomas');
        $participant->setTelephone('0606060606');
        $participant->setMail('thomas.collin@gmail.com');
        $participant->setMotPasse('collin');
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        $this->addReference(self::THOMAS , $participant);

        $participant = new Participant();
        $participant->setNom('VANDAMME');
        $participant->setPrenom('Julien');
        $participant->setTelephone('0606060606');
        $participant->setMail('julien.vandamme@gmail.com');
        $participant->setMotPasse('vandamme');
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        $this->addReference(self::JULIEN , $participant);

        $participant = new Participant();
        $participant->setNom('INACTIF');
        $participant->setPrenom('User');
        $participant->setTelephone('0606060606');
        $participant->setMail('user.inactif@gmail.com');
        $participant->setMotPasse('inactif');
        $participant->setAdministrateur(false);
        $participant->setActif(false);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_LA_ROCHE_SUR_YON));
        $manager->persist($participant);

        $this->addReference(self::INACTIF , $participant);

        $manager->flush();
    }
}
