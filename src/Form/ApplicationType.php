<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;



class ApplicationType extends AbstractType {





       /**
     * allows to easly configure labels and placeholders in ->add()
     * merges label & placeholder with options array(empty by default).
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function labelPlaceholderConfig($label, $placeholder, $options=[]){
        return array_merge([
            'label' => $label,
            'attr' => ['placeholder' => $placeholder]
        ], $options);
    }
    
}

