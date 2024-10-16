<?php

namespace App\Form;

use App\Entity\Boletin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BoletinType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $months = [
            'Enero' => 1,
            'Febrero' => 2,
            'Marzo' => 3,
            'Abril' => 4,
            'Mayo' => 5,
            'Junio' => 6,
            'Julio' => 7,
            'Agosto' => 8,
            'Septiembre' => 9,
            'Octubre' => 10,
            'Noviembre' => 11,
            'Diciembre' => 12,
        ];

        $currentMonth = date('n'); // Current month as a number (1-12)

        $builder
            ->add('numero')
            ->add('mes', ChoiceType::class, [
                'choices' => $months,
                'placeholder' => 'Seleccionar', // Optional
                'data' => $currentMonth, // Set default to the current month
            ])
            ->add('anio', ChoiceType::class, [
                'choices' => [
                    '2023' => 2023,
                    '2024' => 2024,
                    '2025' => 2025,
                    '2026' => 2026,
                    '2027' => 2027,
                    '2028' => 2028,
                ],
                'placeholder' => 'Seleccionar', // Optional
                'data' => date('Y'), // Set default to the current year

            ])

            ->add('url')
            ->add('boletinFile', VichFileType::class, [
                'required' => false,
                'label'=>'Imagen',
                'allow_delete' => false,
                'download_link' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boletin::class,
        ]);
    }
}
