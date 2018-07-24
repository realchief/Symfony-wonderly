<?php

namespace FrontendBundle\Form;

use AppBundle\Validator\Constraint\Address;
use FrontendBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\Child;
use UserBundle\Entity\Father;

/**
 * Class FatherType
 * @package FrontendBundle\Form
 */
class FatherType extends AbstractType
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
            ->add('img', FileType::class, array(
                'label' => 'Profile Image',
                'required' => false,
                'data_class' => null,
            ))
            ->add('age', IntegerType::class, [
                'label' => 'Your age',
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
                'constraints' => new Address(),
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => true,
                'multiple' => true,
            ])
            ->add('child', CollectionType::class, [
                'label' => false,
                'entry_type' => ChildType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]);
    }

    /**
     * @param OptionsResolver $resolver The resolver for the options.
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Father::class,
        ));
    }
}
