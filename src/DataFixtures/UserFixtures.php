<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $participant = new Participant();
        $participant->setNom('Dupont');
        $participant->setPrenom('Jacques');
        $password = $this->encoder->encodePassword($participant, 'admin');
        $participant->setMotPasse($password);
        $participant->setMail("admin@campus-eni.fr");
        $participant->setActif(true);
        $participant->setAdministrateur(true);

        $manager->persist($participant);

        $manager->flush();
    }
}
