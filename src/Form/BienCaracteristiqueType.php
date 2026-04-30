<?php

namespace App\Form;

use App\Entity\BienCaracteristique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienCaracteristiqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => array_combine(
                    array_map('ucfirst', BienCaracteristique::TYPES),
                    BienCaracteristique::TYPES,
                ),
                'placeholder' => '-- choisir --',
                'required' => false,
            ])
            ->add('nbrPiece', IntegerType::class, ['label' => 'Nombre de pièces', 'required' => false])
            ->add('nbrChambre', IntegerType::class, ['label' => 'Nombre de chambres', 'required' => false])
            ->add('etage', IntegerType::class, ['required' => false])
            ->add('energie', ChoiceType::class, [
                'choices' => array_combine(BienCaracteristique::ENERGIES, BienCaracteristique::ENERGIES),
                'placeholder' => '-- DPE --',
                'required' => false,
            ])
            ->add('chauffage', ChoiceType::class, [
                'choices' => array_combine(
                    array_map('ucfirst', BienCaracteristique::CHAUFFAGES),
                    BienCaracteristique::CHAUFFAGES,
                ),
                'placeholder' => '-- choisir --',
                'required' => false,
            ])
            ->add('balcon', CheckboxType::class, ['required' => false])
            ->add('meuble', CheckboxType::class, ['label' => 'Meublé', 'required' => false])
            ->add('cave', CheckboxType::class, ['required' => false])
            ->add('ascenseur', CheckboxType::class, ['required' => false])
            ->add('jardin', CheckboxType::class, ['required' => false])
            ->add('parking', CheckboxType::class, ['required' => false])
            ->add('garage', CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => BienCaracteristique::class]);
    }
}
