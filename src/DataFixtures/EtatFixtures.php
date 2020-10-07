<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public const ETAT_CREEE = 'etat_creee';
    public const ETAT_OUVERTE = 'etat_ouverte';
    public const ETAT_CLOTUREE = 'etat_cloturee';
    public const ETAT_ACTIVITE_EN_COURS = 'etat_activite_en_cours';
    public const ETAT_PASSEE = 'etat_passee';
    public const ETAT_ANNULEE = 'etat_annulee';

    public function load(ObjectManager $manager)
    {
        $etat = new Etat();
        $etat->setLibelle('Créée');
        $etat->setDateCreated();
        $manager->persist($etat);

        //on jaoute une réf de l'objet pour pouvoir l'utiliser dans une autre fixture
        $this->addReference(self::ETAT_CREEE , $etat);

        $etat = new Etat();
        $etat->setLibelle('Ouverte');
        $etat->setDateCreated();
        $manager->persist($etat);

        $this->addReference(self::ETAT_OUVERTE , $etat);

        $etat = new Etat();
        $etat->setLibelle('Clôturée');
        $etat->setDateCreated();
        $manager->persist($etat);

        $this->addReference(self::ETAT_CLOTUREE , $etat);

        $etat = new Etat();
        $etat->setLibelle('Activité en cours');
        $etat->setDateCreated();
        $manager->persist($etat);

        $this->addReference(self::ETAT_ACTIVITE_EN_COURS , $etat);

        $etat = new Etat();
        $etat->setLibelle('Passée');
        $etat->setDateCreated();
        $manager->persist($etat);

        $this->addReference(self::ETAT_PASSEE , $etat);

        $etat = new Etat();
        $etat->setLibelle('Annulée');
        $etat->setDateCreated();
        $manager->persist($etat);

        $this->addReference(self::ETAT_ANNULEE , $etat);

        $manager->flush();
    }
}
