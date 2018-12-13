<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->labelPlaceholderConfig("Prénom", "Saisissez votre prénom"))
            ->add('lastName', TextType::class, $this->labelPlaceholderConfig("Nom", "Saisissez votre nom"))
            ->add('email', EmailType::class, $this->labelPlaceholderConfig("Email", "Saisissez votre adresse mail"))
            ->add('picture', UrlType::class, $this->labelPlaceholderConfig("Photo (facultatif)", "Saisissez l'url de votre photo ",['required' => false] ))
            ->add('hash',PasswordType::class, $this->labelPlaceholderConfig("Mot de passe", "Saisissez un mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->labelPlaceholderConfig("Re-confirmez le Mot de passe", "Saisissez a nouveau le mot de passe"))
            ->add('introduction',TextType::class, $this->labelPlaceholderConfig("Poste occupé", "Exemple: Agent de service, ou Chef de site..."))
            ->add('text', TextareaType::class, $this->labelPlaceholderConfig("Décrivez vous (facultatif)", "Décrivez vos disponibilités, vos besoins, vos souhaits, vos contraintes, etc...  ",['required' => false]))
            ->add('tel', IntegerType::class, $this->labelPlaceholderConfig("Téléphone", "Saisissez un numéro où vous serez joignable"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
