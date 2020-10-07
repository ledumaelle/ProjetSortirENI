<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile',VichImageType::class,
                [
                    'label' => false,
                    'required' => false
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Pseudo'
                ],
                'label' => false
            ])
            ->add('mail', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Mail'
                ],
                'label' => false
            ])
            ->add('nom',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Nom'
                ],
                'label' => false
            ])
            ->add('prenom',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Prénom'
                ],
                'label' => false
            ])
            ->add('telephone', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'placeholder' => 'Téléphone'
                ],
                'label' => false
            ])
            ->add('motPasse', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mot de passe doivent être identiques.',
                'required' => true,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-4',
                        'placeholder' => 'Mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-4',
                        'placeholder' => 'Confirmez le mot de passe'
                    ]
                ],
            ])
            ->add('campus',EntityType::class,[
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un campus',
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'mdb-select md-form'
                ],
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom',"ASC");
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
