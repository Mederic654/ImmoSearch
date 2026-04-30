<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Bien;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('prix', MoneyType::class, ['currency' => 'EUR'])
            ->add('surface', IntegerType::class, ['help' => 'En m²'])
            ->add('statut', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(fn ($s) => ucfirst(str_replace('_', ' ', $s)), Bien::STATUTS),
                    Bien::STATUTS,
                ),
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'nom',
            ])
            ->add('adresse', AdresseType::class, ['label' => 'Adresse'])
            ->add('caracteristique', BienCaracteristiqueType::class, ['label' => 'Caractéristiques']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Bien::class]);
    }
}
