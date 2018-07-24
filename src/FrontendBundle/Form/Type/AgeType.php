<?php

namespace FrontendBundle\Form\Type;

use FrontendBundle\Form\DataTransformer\AgeDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AgeType
 *
 * @package FrontendBundle\Form\Type
 */
class AgeType extends AbstractType
{

//    const AGE_CHOICES = [
//        '0-2' => '0-2',
//        '2-4' => '2-4',
//        '4-6' => '4-6',
//        '6-10' => '6-10',
//        '10+' => '10+',
//        'All Ages' => 'All Ages',
//    ];
    public $ageChoices = array(
        '0-2' => '0-2',
        '2-4' => '2-4',
        '4-6' => '4-6',
        '6-10' => '6-10',
        '10+' => '10+',
        'All Ages' => 'All Ages',
    );

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
        $builder->addModelTransformer(new AgeDataTransformer());
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
            'multiple' => true,
            'choices' => $this->ageChoices,
            'by_reference' => false,
        ));
    }

    /**
     * Returns the name of the parent type.
     *
     * @return string|null The name of the parent type if any, null otherwise.
     */
    public function getParent()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
    }
}
