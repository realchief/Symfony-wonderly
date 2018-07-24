<?php

namespace FrontendBundle\Services;

use FrontendBundle\Entity\Periodic;

/**
 * Class GetNearestDate
 * @package FrontendBundle\Services
 */
class GetNearestDate
{
    /** @var array  */
    private $periodicMaps = [
        '0Monday', '0Tuesday', '0Wednesday', '0Thursday', '0Friday', '0Saturday', '0Sunday',
        '1Monday', '1Tuesday', '1Wednesday', '1Thursday', '1Friday', '1Saturday', '1Sunday',
        '2Monday', '2Tuesday', '2Wednesday', '2Thursday', '2Friday', '2Saturday', '2Sunday',
        '3Monday', '3Tuesday', '3Wednesday', '3Thursday', '3Friday', '3Saturday', '3Sunday',
        '4Monday', '4Tuesday', '4Wednesday', '4Thursday', '4Friday', '4Saturday', '4Sunday',
    ];

    /** @var string */
    private $format = 'm/d/Y';

    /**
     * Get Closest Date
     *
     * @param \DateTime|null $start Start date range.
     * @param \DateTime|null $end   End date range.
     * @param array          $array Array Periodic.
     *
     * @return false|string
     */
    public function getNearestDate($start, $end, $array)
    {

        if ($start === null and empty($array)) {
            return date_format(new \DateTime(), 'm/d/y');
        }
        $rangeDates = ($end !== null) ? $this->getDatesFromRange($start, $end) : [];
        $periodicDates = $this->definePeriodicDates($array);

        $result = $this->find_closest(array_merge($rangeDates, $periodicDates), date_format(new \DateTime(), $this->format));

        $date = new \DateTime();
        $date->setTimestamp($result);

        if ($date->format('m/d/y') == '01/01/70') {
            return '';
        }

        return $date->format('m/d/y');
    }

    /**
     * Generate an array of string dates between 2 dates
     *
     * @param \DateTime $start Start date.
     * @param \DateTime $end   End date.
     *
     * @return array
     */
    private function getDatesFromRange($start, $end)
    {
        $array = array();
        $interval = new \DateInterval('P1D');

        $end->add($interval);

        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $date) {
            /** @var \DateTime $date */
            $array[] = $date->format($this->format);
        }
        return $array;
    }


    /**
     * Find Closest Date
     *
     * @param array  $dates   Array all dates.
     * @param string $findate Today.
     *
     * @return string
     */
    private function find_closest($dates, $findate)
    {
        $dates = array_map(function ($date) {
            return strtotime($date);
        }, $dates);

        $dates = array_filter($dates, function ($date) use ($findate) {
            return ($date >= strtotime($findate));
        });
        sort($dates);

        return array_shift($dates);
    }

    /**
     * Define Periodic Dates
     *
     * @param $array
     *
     * @return array
     */
    private function definePeriodicDates($array)
    {
        if (empty($array)) {
            return [];
        }
        $periodicMaps = $this->periodicMaps;

        $array = array_map(function ($periodic) {
            /** @var Periodic $periodic */
            return $periodic->getDay();
        }, $array);

        $periodicArr = array_filter($array, function ($item) use ($periodicMaps) {
            return (in_array($item, $periodicMaps));
        });
        $datesArr = array_filter($array, function ($item) use ($periodicMaps) {
            return (!in_array($item, $periodicMaps));
        });

        $periodicArr = $this->definePeriodicDays($periodicArr);

        return array_merge($periodicArr, $datesArr);
    }

    /**
     * Define Periodic Days
     *
     * @param array $periodicArr Array with Periodic instance.
     *
     * @return array
     */
    private function definePeriodicDays($periodicArr)
    {
        $allDates = $this->getArrayPeriodicMap();

        $newArray = [];
        foreach ($allDates as $date) {
            if (in_array($date[1], $periodicArr)) {
                $newArray[] = $date[0];
            } elseif (count($date) === 3 and in_array($date[2], $periodicArr)) {
                $newArray[] = $date[0];
            }
        }

        return $newArray;
    }

    /**
     * Get all day to month with periodic name
     *
     * @return array
     */
    private function getArrayPeriodicMap()
    {
        $list = $this->getAllDatesMonth([], new \DateTime('first day of this month'));
        $list = $this->getAllDatesMonth($list, new \DateTime('first day of next month'));

        return array_map(function ($date) {
            return $this->helpFunctionDefinedDay(new \DateTime($date));
        }, $list);
    }

    /**
     * Get Event by day.
     *
     * @param \DateTime $eventDate Time start.
     *
     * @return array
     */
    protected function helpFunctionDefinedDay(\DateTime $eventDate)
    {
        $day = (int) $eventDate->format('j');

        if ($day - 7 <= 0) {
            $date = 1;
        } elseif ($day - 14 <= 0) {
            $date = 2;
        } elseif ($day - 21 <= 0) {
            $date = 3;
        } elseif ($day - 28 <= 0) {
            $date = 4;
        }
        if (isset($date)) {
            return [$eventDate->format($this->format), $date . $eventDate->format('l'), 0 . $eventDate->format('l')];
        }
        return [$eventDate->format($this->format), 0 . $eventDate->format('l')];
    }

    /**
     * Get Array Dates 1 month.
     *
     * @param array     $list Array.
     * @param \DateTime $date A Datetime Instance.
     *
     * @return array
     */
    private function getAllDatesMonth($list, $date)
    {
        $start_time = strtotime($date->format($this->format));
        $end_time = strtotime("+1 month", $start_time);
        for($i = $start_time; $i < $end_time; $i += 86400) {
            $list[] = date($this->format, $i);
        }
        return $list;
    }
}
