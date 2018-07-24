<?php

namespace Component\EventFilter\Model;

/**
 * Class HourInterval
 *
 * @package FrontendBundle\Model\EventFilter
 */
class HourInterval
{

    /**
     * @var integer
     */
    private $start;

    /**
     * @var integer
     */
    private $end;

    /**
     * HourInterval constructor.
     *
     * @param integer $start Start of hour interval.
     * @param integer $end   End of hour interval.
     */
    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return integer
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return integer
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->start .'-'. $this->end;
    }
}
