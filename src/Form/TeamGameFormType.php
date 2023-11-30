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
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        ->add('seasonYear', ChoiceType::class, [
            'label' => 'Season Year',
            'choices' => $this->getYearChoices(),
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Créer matches',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    
    private function getYearChoices(): array
    {
        $currentYear = (int) date('Y');
        $nextYear = $currentYear + 1;

        return [
            $currentYear => $currentYear,
            "{$currentYear}-{$nextYear}" => "{$currentYear}-{$nextYear}",
            $nextYear => $nextYear,
        ];
    }
}
