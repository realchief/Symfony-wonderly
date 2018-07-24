<?php

namespace FrontendBundle\Form;

use FrontendBundle\Entity\ImageEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('img', FileType::class, array('label' => 'Image', 'multiple' => 'multiple'))
            ->add('img', 'Symfony\Component\Form\Extension\Core\Type\FileType', array(
                'label' => 'Image',
                'required' => false,
                ))
            ->add('url', 'Symfony\Component\Form\Extension\Core\Type\UrlType', array(
                'label' => 'URL',
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontendBundle\Entity\ImageEvent',
        ));
    }
}
