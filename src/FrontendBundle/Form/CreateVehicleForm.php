<?php

namespace FrontendBundle\Form;

use AppBundle\Validator\Constraint\Address;
use FrontendBundle\Entity\Category;
use FrontendBundle\Entity\Periodic;
use FrontendBundle\Form\Type\AgeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CreateVehicleForm
 *
 * @package FrontendBundle\Form
 */
class CreateVehicleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        switch ($options['flow_step']) {
            case 1:
                $builder
                    ->add('img', FileType::class, array(
                        'label' => 'Profile Image',
                        'required' => false,
                        'data_class' => null,
                    ))
                    ->add('ageOrganizer', IntegerType::class, [
                        'label' => 'Your age',
                    ])
                    ->add('location', TextType::class, [
                        'label' => 'Location',
                    ])
                    ->add('address', TextType::class, [
                        'label' => 'Address',
                        'constraints' => new Address(),
                    ])
                    ->add('profession', ChoiceType::class, [
                        'label' => 'Profession',
                        'choices'  => array(
                            'Teacher' => 'Teacher',
                            'Instructor' => 'Instructor',
                            'Tutor' => 'Tutor',
                            'Parent' => 'Parent',
                        ),
                    ]);
                break;
            case 2:
                $builder
                    ->add('name', TextType::class, [
                        'label' => 'Name of your Business or Event',
                        'required' => false,
                    ])
                    ->add('category', EntityType::class, [
                        'class' => Category::class,
                        'required' => true,
                        'multiple' => true,
                        'label' => 'Type of event',
                    ])
                    ->add('addressEvent', TextType::class, [
                        'label' => 'Address',
                        'constraints' => new Address(),
                    ])
                    ->add('zip', TextType::class, [
                        'label' => 'Zip code',
                    ])
                    ->add('site', UrlType::class, [
                        'label' => 'Website',
                    ])
                    ->add('phonenumber', TextType::class, [
                        'label' => 'Telephone Number',
                        'required' => false,
                    ])
                    ->add('email', EmailType::class, ['label' => 'Contact e-mail', 'required' => false])//EmailType
                    ;
                break;
            case 3:
                $builder
                    ->add('outdoor', ChoiceType::class, [
                    'label' => 'Indoor or Outdoor',
                    'choices'  => array(
                        ' ' => null,
                        'Indoor' => 'indoor',
                        'Outdoor' => 'outdoor',
                        'Both' => 'both',
                    ),
                    ])
                    ->add('description', TextareaType::class, [
                        'label' => 'Brief Description',
                        'attr' => array(
                            'placeholder' => 'Enter description',
                        ),
                    ])
                    ->add('age', AgeType::class, [
                        'label' => 'Ages Appropriate',
                        'multiple' => true,
                        'expanded' => true,
                        'choice_attr' => [ 'class' => 'radio' ],
                        'label_attr' => [ 'class' => 'radio' ],
                    ])
                    ->add('eventDate', DateType::class, [
                        'label' => false,
                        'required' => false,
                    ])
                    ->add('eventDateEnd', DateType::class, [
                        'label' => false,
                        'required' => false,
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
                            ' ' => null,
                            'YES' => '1',
                            'NO' => '0',
                        ],
                        'label' => 'Food available for purchase',
                    ])
                    ->add('tips', TextType::class, [
                        'label' => 'Helpful Tips',
                        'required' => false,
                    ])
                    ->add('price', TextType::class, [
                        'label' => 'Pricing',
                        'required' => false,
                    ])
                    ->add('periodic', TextType::class, [
                        'label' => false,
                        'required' => false,
                    ]);
                $builder->get('periodic')
                    ->addModelTransformer(new CallbackTransformer(
                        function ($periodicAsArray) {
                            $periodicView = '';
                            foreach ($periodicAsArray as $periodic) {
                                /** @var Periodic $periodic */
                                $periodic = $periodic->getDay();
                                $periodicView .= ' Every ';
                                if (substr($periodic, 0, 1) !== '0') {
                                    $periodicView .= substr($periodic, 0, 1);
                                }
                                $periodicView .= ' week ';
                                $periodicView .= substr($periodic, 1) . ' /';
                            }
                            return $periodicView;
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
                break;
        }
    }

    public function getBlockPrefix()
    {
        return 'createVehicle';
    }
}
