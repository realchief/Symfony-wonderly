<?php

namespace FrontendBundle\Controller;

use FrontendBundle\Entity\Event;
use FrontendBundle\Entity\Periodic;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AbstractController
 * @package FrontendBundle\Controller
 */
class AbstractController extends Controller
{

    /**
     * @param AbstractPagination | array $events List events.
     *
     * @return AbstractPagination | array
     */
    protected function addCheckedUserLiked($events)
    {
        if ($this->getUser() === null) {
            return $events;
        }

        $paginator = (is_array($events)) ? false : $events;
        $events = (is_array($events)) ? $events : $events->getItems();

        $eventsId = array_map(array($this, 'getEventId'), $events);
        $favoritedEvents = $this->getDoctrine()
            ->getRepository('FrontendBundle:Event')
            ->getMatchIfUserLiked($this->getUser(), $eventsId);

        $events = array_map(function ($event) use ($favoritedEvents) {
            if (in_array($event, $favoritedEvents)) {
                $event->liked = true;
            }
            return $event;
        }, $events);

        if ($paginator) {
            $paginator->setItems($events);
            $events = $paginator;
        }
        return $events;
    }

    /**
     * Help function get periodic array
     *
     * @param Event $event A Event instance.
     *
     * @return array
     */
    protected function getPeriodicArray(Event $event)
    {
        $periodicTableArray = $event->getPeriodic()->toArray();
        $weekArray = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $weekNumberArray = [];
        for ($i = 0; $i < 5; $i++) {
            foreach ($weekArray as $value) {
                $weekNumberArray[] = $i . $value;
            }
        }
        $periodic = [];
        $multipleDates = [];
        /** @var Periodic $value */
        foreach ($periodicTableArray as $value) {
            if (in_array($value->getDay(), $weekNumberArray)) {
                $periodic[] = $value->getDay();
            } else {
                $multipleDates[] = $value->getDay();
            }
        }

        return [$periodic, $multipleDates];
    }

    /**
     * Help function get periodic array
     *
     * @param array $periodic A Event instance.
     *
     * @return string
     */
    protected function getPeriodicString(array $periodic)
    {
        if (empty($periodic)) {
            return '';
        }
        $periodicString = 'Every:';
        foreach ($periodic as $day) {
            if (substr($day, 0, 1) === '0') {
                $day = substr($day, 1);
                $periodicString .= ' ' . $day . ',';
            } else {
                $periodicString .= ' ' . substr($day, 0, 1) . ' weeks in ' .  substr($day, 1) . ',';
            }
        }
        return $periodicString;
    }

    /**
     * Help function get Multiple Date String
     *
     * @param array $multiple A Event instance.
     *
     * @return string
     */
    protected function getMultipleDateString(array $multiple)
    {
        if (empty($multiple)) {
            return '';
        }
        $multipleDateString = '';

        foreach ($multiple as $date) {
            $dateTime = new \DateTime($date);
            $multipleDateString .= $dateTime->format('l, d F') . '; ';
        }

        return $multipleDateString;
    }

    /**
     * Help function get Date String
     *
     * @param Event $event A Event instance.
     *
     * @return string
     */
    protected function getDateString(Event $event)
    {
        $periodicArray = $this->getPeriodicArray($event);

        $multipleDateString = $this->getMultipleDateString($periodicArray[1]);
        $periodicString = $this->getPeriodicString($periodicArray[0]);

        $dateString  = $multipleDateString . $periodicString;

        if (substr($dateString, -1) === ',' or substr($dateString, -1) === ';') {
            $dateString = substr($dateString, 0, -1);
        }

        if (substr($dateString, -1) === ' ') {
            $dateString = substr($dateString, 0, -2);
        }

        return $dateString;
    }

    /**
     * Help Function For addCheckedUserLiked
     *
     * @param Event $event A Event instance.
     *
     * @return integer
     */
    protected function getEventId(Event $event)
    {
        return $event->getId();
    }
}
