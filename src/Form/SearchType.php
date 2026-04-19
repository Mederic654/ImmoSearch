<?php

namespace App\Form;

use App\Entity\BienCaracteristique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod(Request::METHOD_GET)
            ->add('type', ChoiceType::class, [
                'choices' => array_combine(
                    array_map('ucfirst', BienCaracteristique::TYPES),
                    BienCaracteristique::TYPES,
                ),
                'placeholder' => 'Tous types',
                'required' => false,
            ])
            ->add('ville', TextType::class, ['required' => false])
            ->add('prixMin', IntegerType::class, ['label' => 'Prix min', 'required' => false])
            ->add('prixMax', IntegerType::class, ['label' => 'Prix max', 'required' => false])
            ->add('surfaceMin', IntegerType::class, ['label' => 'Surface min (m²)', 'required' => false])
            ->add('tri', ChoiceType::class, [
                'choices' => [
                    'Plus récents' => 'recent',
                    'Prix croissant' => 'prix_asc',
                    'Prix décroissant' => 'prix_desc',
                    'Surface croissante' => 'surface_asc',
                    'Surface décroissante' => 'surface_desc',
                ],
                'required' => false,
            ]);
    }
}
