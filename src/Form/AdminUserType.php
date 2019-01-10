<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->labelPlaceholderConfig("Prénom", "Saisissez le prénom"))
            ->add('lastName', TextType::class, $this->labelPlaceholderConfig("Nom", "Saisissez le nom"))
            ->add('email', EmailType::class, $this->labelPlaceholderConfig("Email", "Saisissez la nouvelle adresse mail"))
            ->add('picture', UrlType::class, $this->labelPlaceholderConfig("Photo (facultatif)", "Saisissez l'url de la nouvelle photo ",['required' => false] ))
            ->add('introduction',TextType::class, $this->labelPlaceholderConfig("Poste occupé", "Nouvelle fonction"))
            ->add('text', TextareaType::class, $this->labelPlaceholderConfig("Décription (facultatif)", "Nouvelle description  ",['required' => false]))
            ->add('tel', IntegerType::class, $this->labelPlaceholderConfig("Téléphone", "Saisissez le nouveau numéro"))
          /*  ->add('userRole', EntityType::class, [
                    'class' => UserRole::class,
                    'label' => "Niveau d'authorisation de l'utilisateur",
                ]) 
        */;
    } 

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class, 
        ]);
    }
}
