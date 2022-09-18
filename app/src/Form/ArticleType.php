<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Node\Expression\Binary\SubBinary;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'titre',
                null,
                [
                    'attr' => ['placeholder' => "Ajouter un titre à l'article"]
                ]
            )
            ->add('contenu')
            ->add(
                'date_creation',
                null,
                ['widget' => 'single_text']
            )
            ->add(
                'categories',
                EntityType::class,
                [
                    'class' => Category::class,
                    'multiple' => true,
                    'by_reference' => false
                ]

            )
            ->add(
                'brouillon',
                SubmitType::class,
                ['label' => 'Enregistrer en brouillon']
            )
            ->add(
                'publier',
                SubmitType::class,
                ['label' => 'A Publier']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
