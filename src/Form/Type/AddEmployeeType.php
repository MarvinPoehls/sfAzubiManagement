<?php

namespace App\Form\Type;

use App\Entity\EmployeeEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddEmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstname', TextType::class, ['empty_data' => '', 'data' => $options['firstname'], 'label' => 'Vorname']);
        $builder->add('lastname', TextType::class, ['empty_data' => '', 'data' => $options['lastname'], 'label' => 'Nachname']);
        $builder->add('birthday', DateType::class, ['data' => $options['birthday'], 'label' => 'Geburtstag']);
        $builder->add('email', EmailType::class, ['required' => false, 'data' => $options['email'], 'label' => 'Email']);
        $builder->add('github', TextType::class, ['empty_data' => '', 'required' => false, 'data' => $options['github'], 'label' => 'Github']);
        $builder->add('image', FileType::class, [
            'label' => false,
            'attr' => [
                'aria-label' => 'profile picture input',
                'class' => 'img-input',
            ],
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => ['image/*'],
                    'mimeTypesMessage' => 'Please upload a valid Image File (png, jpeg, svg etc.)',
                ]),
            ],
        ]);
        $builder->add('atFatchipSince', DateType::class, [
            'data' => $options['atFatchipSince'],
            'label' => 'Bei Fatchip seit',
        ]);
        $builder->add('preSkills', CollectionType::class, [
            'entry_type' => SkillType::class,
            'label' => 'Vorherige Skills',
            'allow_add' => true,
            'allow_delete' => true,
        ]);
        $builder->add('newSkills', CollectionType::class, [
            'entry_type' => SkillType::class,
            'label' => 'Neu bei Fatchip gelernt',
            'allow_add' => true,
            'allow_delete' => true,
        ]);
        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeEntity::class,
            'id' => '',
            'birthday' => null,
            'email' => '',
            'firstname' => '',
            'lastname' => '',
            'github' => '',
            'image' => null,
            'atFatchipSince' => new \DateTime(),
        ]);
    }
}
