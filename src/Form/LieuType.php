<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    protected $em;

    function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ville', EntityType::class, [
            'class' => Ville::class,
            'choice_label' => 'nom',
            'required' => true,
            'label' => 'Sélectionnez une ville',
            'attr' => [
                'class' => 'mdb-select md-form',

            ],
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('c')
                          ->orderBy('c.nom', "ASC");
            },
        ])
                ->add('nom', TextType::class, [
                    'attr' => [
                        'class' => 'form-control mb-4',
                    ],
                    'label' => 'Nom du lieu',
                ])
                ->add('rue', TextType::class, [
                    'attr' => [
                        'class' => 'form-control mb-4',
                    ],
                    'label' => 'Adresse de sortie',
                ])
                ->add('latitude', NumberType::class, [
                    'attr' => [
                        'class' => 'form-control mb-4',
                    ],
                    'label' => 'Latitude',
                    'required' => false,
                ])
                ->add('longitude', NumberType::class, [
                    'attr' => [
                        'class' => 'form-control mb-4',
                    ],
                    'label' => 'Longitude',
                    'required' => false,
                ])
                ->add('Enregistrer', SubmitType::class, [
                    'attr' => ['class' => 'btn light-blue darken-3 text-white btn-rounded waves-effect my-4 btn-block'],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
