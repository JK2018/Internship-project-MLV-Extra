<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                 TextType::class,
                  $this->labelPlaceholderConfig('Titre - Nom du site', 'Définissez le nom du magasin (ou site)')
                  )
                  //slug feild unneeded since slug is automatically generated with title.
           /* ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web", "Tapez l'adresse du site (automatique)", [
                    'required' => false
                ])
            )*/
            ->add(
                'introduction',
                 TextType::class,
                  $this->labelPlaceholderConfig('Introduction', 'Décrivez une brève introduction de l\'offre')
                  )
            ->add(
                'content',
                 TextareaType::class,
                  $this->labelPlaceholderConfig('Contenu', 'Décrivez les détails de l\'offre')
                  )
            ->add(
                'coverImage',
                 UrlType::class,
                  $this->labelPlaceholderConfig('Url Photo Principale' , 'Url de la photo principale')
                  )
           /* ->add(
                'daysPerMission',
                 IntegerType::class,
                  $this->labelPlaceholderConfig('Nombre de jours', 'Durée totale de la mission (en jours)')
                  )*/
            ->add(
                'hoursPerDay',
                 IntegerType::class,
                  $this->labelPlaceholderConfig('Durée quotidienne de travail (en heures)', 'Durée de travail par jour (en heures)', [
                    'attr' => [
                        'min' => 1,
                        'max' => 12,
                        'step' => 0.5
                    ]]
                  ))
            ->add('startAdDate', TextType::class, $this->labelPlaceholderConfig("Date de démarrage",
            "Premier jour travaillé")
                  )
            ->add('endAdDate', TextType::class, $this->labelPlaceholderConfig("Date de fin (inclus)",
            "Dernier jour travaillé")
                  )
            ->add( //created new form class ImageType 
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                    
                ]);
                $builder->get('startAdDate')->addModelTransformer($this->transformer);
                $builder->get('endAdDate')->addModelTransformer($this->transformer);
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
