<?php

namespace App\Form\Type;

use App\Entity\SearchEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchbarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'empty_data' => '',
                'required' => false,
                'data' => $options['search'] ?? '',
                'label' => false,
                'attr' => [
                    'placeholder' => 'Search',
                    'class' => 'rounded-left',
                    'aria-label' => 'Search',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => '<i class="bi bi-search"></i>',
                'label_html' => true,
                'attr' => [
                    'title' => 'Submit Search',
                    'class' => 'btn btn-secondary rounded-right',
                    'aria-label' => 'Submit Search',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchEntity::class,
            'search' => '',
        ]);
    }
}
