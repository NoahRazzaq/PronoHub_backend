<?php

namespace App\Form;

use App\Entity\LeagueApi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeamGameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('leagueId', EntityType::class, [
            'class' => LeagueApi::class,
            'choice_label' => 'name',
            'choice_value' => 'identifier',
            'label' => 'Select League',
        ])
        ->add('round', IntegerType::class, [
            'label' => 'Round',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Create Matches',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
