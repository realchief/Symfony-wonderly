<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\Organize;

/**
 * Class OrganizeType
 * @package AdminBundle\Form
 */
class OrganizeType extends AbstractType
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
                'label' => 'Age',
            ])
            ->add('location', TextType::class, [
                'label' => 'Location',
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
            ])
            ->add('profession', ChoiceType::class, [
                'label' => 'Profession',
                'choices'  => array(
                    'Teacher' => 'Teacher',
                    'Instructor' => 'Instructor',
                    'Tutor' => 'Tutor',
                    'Admin' => 'Admin',
                    'Parent' => 'Parent',
                ),
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
            'data_class' => Organize::class,
        ));
    }
}
