<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{


    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer){
        $this->transformer = $transformer;
    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->labelPlaceholderConfig("Date du premier jour que vous aller travailler",
             "Date à laquelle vous voulez commencer à travailler"))
            ->add('endDate', TextType::class, $this->labelPlaceholderConfig("Date du dernier jour que vous allez travailler",
             "Date du dernier jour que vous allez travailler sur ce site"))
             ->add('comment', TextareaType::class, $this->labelPlaceholderConfig(false, "Si vous avez un commentaire, écrivez-le ici!", ["required" => false]));
    
             $builder->get('startDate')->addModelTransformer($this->transformer);
             $builder->get('endDate')->addModelTransformer($this->transformer);
            }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class
        ]);
    }
}
