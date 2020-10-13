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
        $builder->add('ville',EntityType::class,[
            'class' => Ville::class,
            'choice_label' => 'nom',
            'placeholder' => 'Sélectionnez une ville',
            'required' => true,
            'label' => false,
            'required'=>false,
            'attr' => [
                'class' => 'browser-default custom-select mb-4',

            ],
            'query_builder' => function(EntityRepository $er)  {
                return $er->createQueryBuilder('c')
                    ->orderBy('c.nom',"ASC");
            }
        ])->add('nom',TextType::class,[
            'attr' => [
                'class' => 'form-control mb-4',
                'placeholder' => 'Nom du lieu'
            ],
            'label' => false
        ])->add('rue',TextType::class,[
            'attr' => [
                'class' => 'form-control mb-4',
                'placeholder' => 'Adresse de sortie'
            ],
            'label' => false
        ])->add('latitude',NumberType::class,[
            'attr' => [
                'class' => 'form-control mb-4',
                'placeholder' => 'latitude'
            ],
            'label' => false,
            'required'=>false
        ])->add('longitude',NumberType::class,[
            'attr' => [
                'class' => 'form-control mb-4',
                'placeholder' => 'longitude'
            ],
            'label' => false,
            'required'=>false
        ])->add('Enregistrer',SubmitType::class,[
            'attr'=>['class'=>'btn btn-primary btn-rounded waves-effect waves-light']
        ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>Lieu::class
        ));

    }

}