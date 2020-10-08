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
                    'required' => false,
                    'allow_delete' => false,
                    'delete_label' => false,
                    'download_uri' => false,
                    'download_label' => false
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                ],
                'label' => 'Pseudo'
            ])
            ->add('mail', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4'
                ],
                'label' => 'Mail'
            ])
            ->add('nom',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4'
                ],
                'label' => 'Nom'
            ])
            ->add('prenom',TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4'
                ],
                'label' => 'Prénom'
            ])
            ->add('telephone', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4'
                ],
                'label' => 'Téléphone'
            ])
            ->add('motPasse', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mot de passe doivent être identiques.',
                'required' => false,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-4',
                        'placeholder' => 'Mot de passe'
                    ],
                    'empty_data' => ' ',
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-control mb-4',
                        'placeholder' => 'Confirmer le mot de passe'
                    ],
                    'empty_data' => ' ',
                ],
            ])
            ->add('campus',EntityType::class,[
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Sélectionnez un campus',
                'required' => true,
                'label' => 'Campus',
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
