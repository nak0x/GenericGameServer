<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\Roles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' =>'Username',
            ])
            ->add('rolesEnum', EnumType::class, [
                'label' => 'Roles',
                'class' => Roles::class,
                'multiple' => true,
                'expanded' => true,
                'mapped' => false,
                'choice_label' => fn (Roles $role) => match ($role) {
                    Roles::ADMIN => 'Admin',
                    Roles::GAME_OWNER => 'Game owner',
                    Roles::PLAYER => 'Player',
                },
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
