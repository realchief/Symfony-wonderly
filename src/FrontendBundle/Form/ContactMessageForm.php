<?php

namespace FrontendBundle\Form;

use FrontendBundle\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventType
 *
 * @package FrontendBundle\Form
 */
class ContactMessageForm extends AbstractType
{

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder.
     * @param array                $options The options.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('email', EmailType::class, [
            'label' => false,
            'attr' => array(
                'placeholder' => 'Email',
            ),
        ])
        ->add('name', TextType::class, [
            'label' => false,
            'attr' => array(
                'placeholder' => 'Name',
            ),
        ])
        ->add('message', TextareaType::class, [
            'label' => false,
            'attr' => array(
                'placeholder' => 'Message',
            ),
        ])
        ->add('setTo', HiddenType::class);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ContactMessage::class,
            'csrf_protection' => false,
        ));
    }
}
