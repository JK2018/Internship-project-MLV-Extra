<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->labelPlaceholderConfig("Ancien mot de passe", "Saisissez votre mot de passe actuel") )
            ->add('newPassword', PasswordType::class, $this->labelPlaceholderConfig("Nouveau mot de passe", "Saisissez votre nouveau mot de passe") )
            ->add('confirmPassword', PasswordType::class, $this->labelPlaceholderConfig("Confirmation mot de passe", "Reconfirmez votre nouveau mot de passe") )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
