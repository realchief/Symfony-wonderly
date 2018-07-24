<?php

namespace FrontendBundle\Form;

use AppBundle\Validator\Constraint\Address;
use Component\EventFilter\Model\ComparableValue;
use Component\EventFilter\Model\EventFilters;
use Component\EventFilter\Model\HourInterval;
use Component\Locator\AddressResolverException;
use Component\Locator\AddressResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use FrontendBundle\Entity\Category;
use FrontendBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function \nspl\a\map;
use function \nspl\op\methodCaller;
use function \nspl\f\partial;

/**
 * Class FilterType
 *
 * @package FrontendBundle\Form
 */
class FilterType extends AbstractType implements DataMapperInterface
{

    /**
     * Available ages.
     */
    const AGE_CHOICES = [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        '11' => '11',
        '12' => '12',
        '13' => '13',
        '14' => '14',
        '15+' => '15+',
    ];

    /**
     * Available distances.
     */
    const DISTANCE_CHOICES = [
        '5' => '5',
        '10' => '10',
        '20' => '20',
        '50+' => '50',
    ];

    /**
     * Available times.
     */
    const TIME_CHOICES = [
        'Morning' => 'Morning',
        'Afternoon' => 'Afternoon',
        'Evening' => 'Evening',
    ];

    /**
     * Map between hours interval and time period name.
     */
    const HOURS_TO_TIME_MAP = [
        '6-12' => 'Morning',
        '12-17' => 'Afternoon',
        '17-19' => 'Evening',
    ];

    /**
     * Map between hours interval and time period name.
     */
    const TIME_TO_HOURS_MAP = [
        'Morning' => '6-12',
        'Afternoon' => '12-17',
        'Evening' => '17-19',
    ];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var AddressResolverInterface
     */
    private $addressResolver;

    /**
     * FilterType constructor.
     *
     * @param EntityManagerInterface   $em              A EntityManagerInterface
     *                                                  instance.
     * @param AddressResolverInterface $addressResolver A AddressResolverInterface
     *                                                  instance.
     */
    public function __construct(
        EntityManagerInterface $em,
        AddressResolverInterface $addressResolver
    ) {
        $this->em = $em;
        $this->addressResolver = $addressResolver;
    }

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
            ->add('free', CheckboxType::class, [
                'required' => false,
            ])
            ->add('remember', CheckboxType::class, [
                'required' => false,
            ])
            ->add('ages', ChoiceType::class, [
                'required' => false,
                'choices' => self::AGE_CHOICES,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('address', TextType::class, [
                'constraints' => new Address(),
            ])
            ->add('distance', ChoiceType::class, [
                'required' => false,
                'choices' => self::DISTANCE_CHOICES,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('time', ChoiceType::class, [
                'required' => false,
                'choices' => self::TIME_CHOICES,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('categories', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (CategoryRepository $repository) {
                    return $repository->createQueryBuilder('Category')
                        ->select('partial Category.{id, tag}');
                },
            ])
            ->setDataMapper($this);
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
        $resolver->setDefaults([
            'data_class' => EventFilters::class,
            'data' => new EventFilters(),
            'csrf_protection' => false,
        ]);
    }

    /**
     * Maps properties of some data to a list of forms.
     *
     * @param EventFilters|null                          $data  Structured data.
     * @param FormInterface[]|\RecursiveIteratorIterator $forms A list of
     *                                                          {@link FormInterface}
     *                                                          instances.
     *
     * @return void
     */
    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);

        $comparableValueConverter = function (ComparableValue $value) {
            $actualValue = $value->getValue();

            if ($value->isOperator(ComparableValue::OPERATOR_GTE)) {
                $actualValue .= '+';
            }

            return $actualValue;
        };

        $forms['free']->setData($data->isOnlyFree());
        $forms['remember']->setData($data->isRemember());
        $forms['ages']->setData(map($comparableValueConverter, $data->getAges()));
        $forms['distance']->setData(map($comparableValueConverter, $data->getDistances()));
        $forms['time']->setData(map(function (HourInterval $interval) {
            return self::HOURS_TO_TIME_MAP[(string) $interval];
        }, $data->getHours()));

        $forms['address']->setData($data->getAddress());

        $forms['categories']->setData(map(
            partial([ $this->em, 'getReference' ], Category::class),
            $data->getCategories()
        ));
    }

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[]|\RecursiveIteratorIterator $forms A list of
     *                                                          {@link FormInterface}
     *                                                          instances.
     * @param EventFilters|null                          $data  Structured data.
     *
     * @return void
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $comparableValueConverter = function ($value) {
            $operator = ComparableValue::OPERATOR_LTE;
            if (strpos($value, '+') !== false) {
                $value = str_replace('+', '', $value);
                $operator = ComparableValue::OPERATOR_GTE;
            }

            return new ComparableValue((int) $value, $operator);
        };

        if (isset($forms['free'])) {
            $data->setOnlyFree($forms['free']->getData());
        }

        if (isset($forms['remember'])) {
            $data->setRemember($forms['remember']->getData());
        }

        if (isset($forms['ages'])) {
            $data->setAges(map($comparableValueConverter, $forms['ages']->getData()));
        }

        if (isset($forms['address'])) {
            $address = $forms['address']->getData();

            $data->setAddress($address);
            try {
                $data->setDestination($this->addressResolver->resolveAddress($address));
            } catch (AddressResolverException $e) {
                //
                // We should not through exception here, cause validators will
                // handle this.
                //
            }

            if (isset($forms['distance'])) {
                $data->setDistances(map($comparableValueConverter, $forms['distance']->getData()));
            }
        }

        if (isset($forms['time'])) {
            $data->setHours(map(function ($time) {
                list ($start, $end) = explode('-', self::TIME_TO_HOURS_MAP[$time]);

                return new HourInterval($start, $end);
            }, $forms['time']->getData()));
        }

        if (isset($forms['categories'])) {
            $data->setCategories(map(methodCaller('getId'), $forms['categories']->getData()));
        }
    }
}
