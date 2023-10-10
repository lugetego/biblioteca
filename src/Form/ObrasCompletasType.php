<?php

namespace App\Form;

use App\Entity\ObrasCompletas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObrasCompletasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('autor')
            ->add('titulo')
            ->add('url')
            ->add('vol')
            ->add('clasificacion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ObrasCompletas::class,
        ]);
    }
}
