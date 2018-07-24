<?php

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use FrontendBundle\Services\GetNearestDate;

/**
 * Class TwigExtension
 *
 * @package AppBundle\Twig
 */
class TwigExtension extends \Twig_Extension
{
    /** @var GetNearestDate  */
    private $closestDate;

    /** @var EntityManagerInterface  */
    private $em;

    /**
     * TwigExtension constructor.
     *
     * @param GetNearestDate         $closestDate A GetNearestDate instance.
     * @param EntityManagerInterface $em          A EntityManagerInterface instance.
     *
     */
    public function __construct(
        GetNearestDate $closestDate,
        EntityManagerInterface $em
    ) {
        $this->closestDate = $closestDate;
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('instanceof', function ($object, $class) {
                // TODO rewrite by using 'instanceof' operator!
                $reflectionClass = new \ReflectionClass($class);

                return $reflectionClass->isInstance($object);
            }),
        );
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('nearestDate', function ($start, $end, $array) {
                return $this->closestDate->getNearestDate($start, $end, $array);
            }),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getAdvertisement', function ($place) {
                $advt = $this->em->getRepository('AdminBundle:Advt')
                    ->findOneBy(['place' => $place], ['id' => 'DESC']);

                return ($advt === null) ? '' : $advt->getContent();
            }),
        );
    }
}
