<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Premier jour travaillé'
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Dernier jour travaillé'
            ])
            ->add('comment', TextType::class, [
                'label' => 'Commeentaire',
            ])
            ->add('booker', EntityType::class, [
                'class' => User::class,
                'label' => 'Nom du salarié',
                'choice_label' => function($user){
                    return $user->getFirstName() . " " . strtoupper($user->getLastName());
                }
            ])
            ->add('ad', EntityType::class, [
                'class' => Ad::class,
                'label' => "Titre de l'offre",
                'choice_label' => function($ad){
                    
                    return $ad->getTitle(). "  du " . $ad->getStartAdDate()->format("d/m/Y") . " au " . $ad->getEndAdDate()->format("d/m/Y");
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
