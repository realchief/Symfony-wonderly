<?php

namespace FrontendBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use FrontendBundle\Entity\Age;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class AgeDataTransformer
 *
 * @package FrontendBundle\Form\DataTransformer
 */
class AgeDataTransformer implements DataTransformerInterface
{

    const MAX_AGE = 20;

    public $ageMap = [
        '0-2' => ['start' => 0, 'end' => 2],
        '2-4' => ['start' => 2, 'end' => 4],
        '4-6' => ['start' => 4, 'end' => 6],
        '6-10' => ['start' => 6, 'end' => 10],
        '10+' => ['start' => 10, 'end' => self::MAX_AGE],
    ];

    /**
     * Transforms a value from the original representation to a transformed
     * representation.
     *
     * This method is called on two occasions inside a form field:
     *
     * 1. When the form field is initialized with the data attached from the
     * datasource (object or array).
     * 2. When data from a request is submitted using {@link Form::submit()} to
     * transform the new input data back into the renderable format. For
     * example if you have a date field and submit '2009-10-10' you might
     * accept this value because its easily parsed, but the transformer still
     * writes back
     *    "2009/10/10" onto the form field (for further displaying or other
     *    purposes).
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that value transformers must be chainable. If the transform() method
     * of the first value transformer outputs NULL, the second value
     * transformer
     * must be able to process that value.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param mixed $value The value in the original representation.
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function transform($value)
    {
        if (! $value instanceof Collection) {
            throw new TransformationFailedException('Should be an instance of '. 'Doctrine\Common\Collections\Collection');
        }

        $count = count($value);

        if (($count === 0) || ($count === self::MAX_AGE + 1)) {
            return array( 'All Ages' );
        }

        $groups = $this->computeGroupCounts($value);

        $result = array();
        foreach ($groups as $key => $group) {
            if ($group >= 3) {
                $result[] = $key;
            }
        }
        return $result;
    }

    /**
     * Transforms a value from the transformed representation to its original
     * representation.
     *
     * This method is called when {@link Form::submit()} is called to transform
     * the requests tainted data into an acceptable format for your data
     * processing/model layer.
     *
     * This method must be able to deal with empty values. Usually this will
     * be an empty string, but depending on your implementation other empty
     * values are possible as well (such as NULL). The reasoning behind
     * this is that value transformers must be chainable. If the
     * reverseTransform() method of the first value transformer outputs an
     * empty string, the second value transformer must be able to process that
     * value.
     *
     * By convention, reverseTransform() should return NULL if an empty string
     * is passed.
     *
     * @param mixed $value The value in the transformed representation.
     *
     * @return mixed The value in the original representation
     *
     * @throws TransformationFailedException When the transformation fails.
     */
    public function reverseTransform($value)
    {
        $collection = new ArrayCollection();
        $array = array_flip($value);

        if (! is_array($array) || (count($array) === 0) || array_key_exists('All Ages', $array)) {
            for ($i = 0; $i < 21; $i++) {
                $collection->add(new Age($i));
            }

            return $collection;
        }

        $previous = false;
        foreach ($this->ageMap as $key => $range) {
            if (array_key_exists($key, $array)) {
                $start = $previous ? $range['start'] + 1: $range['start'];
                $end = $range['end'];

                for ($i = $start; $i <= $end; $i++) {
                    $collection->add(new Age($i));
                }
                $previous = true;
            } else {
                $previous = false;
            }
        }

        return $collection;
    }

    /**
     * @param ArrayCollection | PersistentCollection $collection Collection of Age entities.
     *
     * @return array
     */
    private function computeGroupCounts($collection)
    {
        $groups = array(
            '0-2' => 0,
            '2-4' => 0,
            '4-6' => 0,
            '6-10' => 0,
            '10+' => 0,
        );

        /** @var Age $age */
        foreach ($collection as $age) {
            $year = $age->getYear();
            foreach ($this->ageMap as $key => $range) {
                if (is_array($range) && ($year >= $range['start']) && ($year <= $range['end'])) {
                    $groups[$key]++;
                }
            }
        }

        return $groups;
    }
}
