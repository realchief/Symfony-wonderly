<?php

namespace FrontendBundle\Form;

use AppBundle\Validator\Constraint\Address;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\ORM\PersistentCollection;
use FrontendBundle\Entity\Category;
use FrontendBundle\Entity\Event;
use FrontendBundle\Entity\Periodic;
use FrontendBundle\Form\Type\AgeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use UserBundle\Entity\Organize;

/**
 * Class EventType
 *
 * @package FrontendBundle\Form
 */
class EventType extends AbstractType
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
            ->add('organize', EntityType::class, [
                'class' => Organize::class,
                'attr' => [ 'class' => 'select2' ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Name of the Event',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'What is location/address?',
                'constraints' => new Address(),
            ])
            ->add('zip', TextType::class, [
                'label' => 'What zip code is your event in?*',
            ])
            ->add('site', UrlType::class, [
                'label' => 'Website Address*',
            ])//URLType
            ->add('phonenumber', TextType::class, [
                'label' => 'Telephone Number',
                'required' => false,
            ])
            ->add('outdoor', ChoiceType::class, [
                'label' => 'Indoor or Outdoor',
                'choices'  => array(
                    '' => null,
                    'Indoor' => 'indoor',
                    'Outdoor' => 'outdoor',
                    'Both' => 'both',
                ),
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Brief Description (20 words or less)*',
            ])
            ->add('price', TextType::class, [
                'label' => 'Pricing',
                'required' => false,
            ])
            ->add('age', AgeType::class, [
                'label' => 'Ages Appropriate',
                'multiple' => true,
                'expanded' => true,
                'choice_attr' => [ 'class' => 'radio' ],
                'label_attr' => [ 'class' => 'radio' ],
            ])
            ->add('origin', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'date_format' => 'A',
            ])
            ->add('finish', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('originWork', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('finishWork', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('food', ChoiceType::class, [
                'choices'  => [
                    '' => null,
                    'YES' => '1',
                    'NO' => '0',
                ],
                'label' => 'Food available for purchase',
            ])
            ->add('tips', TextareaType::class, [
                'label' => 'Helpful Tips',
                'required' => false,
            ])
            ->add('eventDate', DateType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('eventDateEnd', DateType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('periodic', TextType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('recommend', ChoiceType::class, [
                'choices'  => [
                    'NO' => '0',
                    'YES' => '1',
                ],
                'label' => 'Reccommend',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'tag',
                'label' => 'Type of event',
                'choice_attr' => [ 'class' => 'radio' ],
                'label_attr' => [ 'class' => 'radio' ],
            ]);
        $builder->get('periodic')
            ->addModelTransformer(new CallbackTransformer(
                function ($periodicAsArray) {
//                    $periodicView = '';
//                    foreach ($periodicAsArray as $periodic) {
//                        /** @var Periodic $periodic */
//                        $periodic = $periodic->getDay();
//                        $periodicView .= ' Every ';
//                        if (substr($periodic, 0, 1) !== '0') {
//                            $periodicView .= substr($periodic, 0, 1);
//                        }
//                        $periodicView .= ' week ';
//                        $periodicView .= substr($periodic, 1) . ' /';
//                    }
                    return '';
                },
                function ($periodicAsString) {
                    $periodicAsArray = array();
                    foreach (explode(',', $periodicAsString) as $periodic) {
                        if ($periodic !== '') {
                            $periodicAsArray[] = new Periodic($periodic);
                        }
                    }
                    return $periodicAsArray;
                }
            ));
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
            'data_class' => Event::class,
            'compound' => true,
            'invalid_message' => 'The selected NIF does not exist',
        ));
    }
}
