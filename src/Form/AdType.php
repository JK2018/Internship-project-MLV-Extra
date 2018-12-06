<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdType extends AbstractType
{





    /**
     * allows to easly configure labels and placeholders in ->add()
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function labelPlaceholderConfig($label, $placeholder){
        return [
            'label' => $label,
            'attr' => ['placeholder' => $placeholder]
        ];
    }





    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->labelPlaceholderConfig('Titre', 'Définissez le titre de l\'offre'))
            ->add('slug') //masquer
            ->add('hoursPerDay', IntegerType::class, $this->labelPlaceholderConfig('Durée quotidienne', 'Durée de travail par jour (en heures)')) 
            ->add('introduction', TextType::class, $this->labelPlaceholderConfig('Introduction', 'Décrivez une brève introduction de l\'offre'))
            ->add('content', TextareaType::class, $this->labelPlaceholderConfig('Contenu', 'Décrivez les détails de l\'offre'))
            ->add('coverImage', UrlType::class, $this->labelPlaceholderConfig('Url Photo', 'Url de la photo principale'))
            ->add('daysPerMission', IntegerType::class, $this->labelPlaceholderConfig('Nombre de jours', 'Durée totale de la mission (en jours)'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
