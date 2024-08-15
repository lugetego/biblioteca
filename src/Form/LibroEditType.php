<?php

namespace App\Form;

use App\Entity\Alerta;
use App\Entity\Libro;
use App\Repository\AlertaRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

class LibroEditType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo')
            ->add('autor')
            ->add('editorial')
            ->add('url')
            ->add('anio')
            ->add('clasificacion')
            ->add('isbn')
            ->add('portadaFile', VichFileType::class, [
                'required' => false,
                'label'=>'Portada',
                'allow_delete' => false,
                'download_link' => false,
            ])
            ->add('alerta', EntityType::class, [
                'class' => Alerta::class,
                'query_builder' => function (AlertaRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.numero', 'DESC');
                },
                'choice_label' => 'numero',
                'required'=>true,
                'empty_data'=>null,
                'placeholder' => 'Choose an option', // Placeholder text
                ])
            ->add('slide')

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => Libro::class,

        ]);
    }



}

