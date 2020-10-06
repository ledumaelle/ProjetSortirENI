<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantFixtures extends Fixture implements DependentFixtureInterface
{
    public const MAELLE = 'maelle';
    public const ANGELIQUE = 'angelique';
    public const THOMAS = 'thomas';
    public const JULIEN = 'julien';
    public const INACTIF = 'inactif';


    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

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
        $participant->setPseudo('angelique.le-roux');
        $participant->setMail('angelique.le-roux@cooperl.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant, 'leroux'));
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        //on jaoute une réf de l'objet pour pouvoir l'utiliser dans une autre fixture
        $this->addReference(self::ANGELIQUE, $participant);

        $participant = new Participant();
        $participant->setNom('LE DU');
        $participant->setPrenom('Maëlle');
        $participant->setTelephone('0606060606');
        $participant->setPseudo('maelle.le-du');
        $participant->setMail('maelle.le-du@cooperl.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant, 'ledu'));
        $participant->setAdministrateur(true);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        $this->addReference(self::MAELLE, $participant);

        $participant = new Participant();
        $participant->setNom('COLLIN');
        $participant->setPrenom('Thomas');
        $participant->setTelephone('0606060606');
        $participant->setPseudo('thomas.collin');
        $participant->setMail('thomas.collin@gmail.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant, 'collin'));
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        $this->addReference(self::THOMAS, $participant);

        $participant = new Participant();
        $participant->setNom('VANDAMME');
        $participant->setPrenom('Julien');
        $participant->setTelephone('0606060606');
        $participant->setPseudo('julien.vandamme');
        $participant->setMail('julien.vandamme@gmail.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant, 'vandamme'));
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_CHARTRES_DE_BRETAGNE));
        $manager->persist($participant);

        $this->addReference(self::JULIEN, $participant);

        $participant = new Participant();
        $participant->setNom('INACTIF');
        $participant->setPrenom('User');
        $participant->setTelephone('0606060606');
        $participant->setPseudo('user.inactif');
        $participant->setMail('user.inactif@gmail.com');
        $participant->setMotPasse($this->encoder->encodePassword($participant, 'inactif'));
        $participant->setAdministrateur(false);
        $participant->setActif(false);
        $participant->setCampus($this->getReference(CampusFixtures::CAMPUS_LA_ROCHE_SUR_YON));
        $manager->persist($participant);

        $this->addReference(self::INACTIF, $participant);

        $manager->flush();
    }
}
