<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class LieuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLieu', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '255'
                ],
                'label' => 'Nom :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 255 ]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('typeLieu', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => '255'
                ],
                'label' => 'Type de lieu :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['max' => 255 ]),
                ]

            ])
            ->add('numeroTelLieu', TelType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => '20'
                ],
                'label' => 'Numéro de téléphone :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['max' => 20 ]),
                ]

            ])

            ->add('emailLieu', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => '255'
                ],
                'label' => 'Adresse Email :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['max' => 255 ]),
                ]

            ])
            ->add('urlLieu', UrlType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => '255'
                ],
                'label' => 'Site web :',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['max' => 255 ]),
                ]

                ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3'
                ],
                'label' => 'Valider'
            ]); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
