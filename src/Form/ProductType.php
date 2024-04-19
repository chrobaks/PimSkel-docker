<?php

namespace App\Form;

use Pimcore\Model\DataObject\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-sm'],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $product = $event->getData();
                $description = $product->getDescription();
                // strip HTML from description before populating form
                $product->setDescription(strip_tags($description));
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                if (isset($data['name'])) {
                    $form->add('name', TextType::class);
                }
                if (isset($data['description'])) {
                    $data['description'] = strip_tags($data['description']);
                    $event->setData($data);
                    $form->add('description', TextareaType::class);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
