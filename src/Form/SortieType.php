<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    protected $em;


    private $logger;


    function __construct(EntityManagerInterface $em,LoggerInterface $logger)
    {

        $this->logger=$logger;
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);



        $user = $options['user'];



        $builder->add('nom', TextType::class, [
            'attr' => [
                'class' => 'form-control mb-4',
            ],
            'label' => 'Nom de sortie',
        ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
                'widget' => 'single_text',
                'label' => false,
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'attr' => [
                    'class' => 'form-control datepicker',
                ],
                'widget' => 'single_text',

                'label' => false,
            ])
            ->add('duree', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                ],
                'label' => 'Durée de sortie (minutes)',
            ])
            ->add('nbInscriptionsMax', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                ],
                'label' => 'Nombres de places',
            ])
            ->add('infosSortie', TextareaType::class, [
                'attr' => [
                    'class' => 'md-textarea form-control',
                ],
                'label' => 'Infos de la sortie',

            ])
            ->add('isprivate', CheckboxType::class, [
                'label' => 'Sortie privé',
                'label_attr' => ['class' => 'form-check-label '],
                'row_attr' => ['class' => 'form-check mb-4'],
                'attr' => ['class' => 'form-check-input'],
                'required' => false,

            ])
            ->add('siteOrganisateur', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus',
                'required' => true,
                'disabled' => true,
                'attr' => [
                    'class' => 'mdb-select md-form',
                ],
                'query_builder' => function(EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.id=' . $user->getCampus()
                                ->getId());
                },
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'browser-default custom-select mb-4',

                ],
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', "ASC");
                },
            ])->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => false,
                'row_attr' => ['class' => 'flex-grow-1'],
                'attr' => [
                    'class' => 'browser-default custom-select mb-4',
                    'onChange' => 'changeLieu',
                ],
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', "ASC");
                },
                'choice_attr' => function($choice) {
                    return [
                        "data-ville" => $choice->getVille()
                            ->getId(),
                    ];
                },
            ]);

        //  $formModifier = function(Form $form,$participants) use ($user) {



        $idArray=array();

        $this->logger->info('formmodifer');

        $data=$builder->getData();


        $participants=$data->getInscritAjax();
        $this->logger->info(implode($participants));

        array_push($idArray,$user->getId());

        foreach ($participants as $participant){
            array_push($idArray,$participant);
        }


        $builder->add('userAll', EntityType::class, [
            'class' => Participant::class,
            'choice_label' => 'nom',
            'placeholder' => 'Liste des Utilisateur',
            'label' => false,
            'mapped' => false,
            'required' => false,
            'multiple'=>'multiple',
            'validation_groups'=>false,
            'attr' => [
                'class' => 'browser-default custom-select mb-4',

            ],
            'query_builder' => function (EntityRepository $er) use ($idArray) {
                return $er->createQueryBuilder('c')
                    ->where('c.id NOT IN (:user)')->setParameter('user',$idArray)
                    ->orderBy('c.nom', "ASC");
            }
        ]);
        $builder->add('userInscrit', EntityType::class, [
            'class' => Participant::class,
            'choice_label' => 'nom',
            'placeholder' => 'Liste des Utilisateur',
            'label' => false,
            'mapped' => false,
            'required' => false,
            'multiple'=>'multiple',
            'validation_groups'=>false,
            'attr' => [
                'class' => 'msel-selected browser-default custom-select mb-4',

            ],'query_builder' => function (EntityRepository $er) use ($idArray) {
                return $er->createQueryBuilder('c')
                    ->where('c.id IN (:user)')->setParameter('user',$idArray);
            }
        ]);


        $builder->get('userInscrit')->resetViewTransformers();
        // };





    }

    protected function addElements(Form $form, Ville $ville = null)
    {


        // Add the ville element
        $form->add('ville', EntityType::class, [
            'class' => Ville::class,
            'choice_label' => 'nom',
            'required' => true,
            'label' => false,
            'mapped' => false,
            'attr' => [
                'class' => 'browser-default custom-select',
                'onChange' => 'changeVille()',
            ],
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('c')//->where('c.campus=?1')->setParameters(array(1=>$user->getCampus()))
                ->orderBy('c.nom', "ASC");
            },
        ]);

        // Cities are empty, unless we actually supplied a ville
        if ($ville) {
            $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'browser-default custom-select',
                    'onChange' => 'changeLieu',
                ],
                'query_builder' => function(EntityRepository $er) use ($ville) {
                    return $er->createQueryBuilder('c')
                        ->where('c.ville=?1')
                        ->setParameters([1 => $ville])
                        ->orderBy('c.nom', "ASC");
                },
                'choice_attr' => function($choice) {
                    return [
                        "data-ville" => $choice->getVille()
                            ->getId(),
                    ];
                },
            ])
                ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-lg btn-info']]);
        } else {
            $form->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'browser-default custom-select',
                    'onChange' => 'changeLieu',
                ],
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', "ASC");
                },
                'choice_attr' => function($choice) {
                    return [
                        "data-ville" => $choice->getVille()
                            ->getId(),
                    ];
                },
            ])
                ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-lg btn-info']]);
        }



    }

    function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Note that the data is not yet hydrated into the entity.
        $ville = $this->em->getRepository(Ville::class)
            ->find($data['ville']);
        $this->addElements($form, $ville);
    }

    function onPreSetData(FormEvent $event)
    {

        $form = $event->getForm();

        // We might have an empty account (when we insert a new account, for instance)

        $this->addElements($form, null);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'user' => null,
        ]);
        $resolver->setAllowedTypes('user', Participant::class);
    }
}