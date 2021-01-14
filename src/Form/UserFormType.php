<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        //Prepare le formulaire avec les données " classique"
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Employé' => 'ROLE_STAFF',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'help' => 'Sélectionnez un rôle',
                'multiple' => false,
                'mapped' => false,
            ]);
        //Ajoute le password en vérifiant l'etat de l'utilisation : creation/edition
        if (isset($options["data"]) && $options["data"]->getId() !== null) {
            $builder->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
            ]);
        } else {
            $builder->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ]);
        }
        //Ajout du submit
        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
