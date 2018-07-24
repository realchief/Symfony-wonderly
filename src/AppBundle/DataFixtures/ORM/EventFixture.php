<?php

namespace AppBundle\DataFixtures\ORM;

use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Common\Persistence\ObjectManager;
use FrontendBundle\Entity\Age;
use FrontendBundle\Entity\Category;
use FrontendBundle\Entity\Event;
use UserBundle\Entity\Organize;

/**
 * Class EventFixture
 * @package AppBundle\DataFixtures\ORM
 */
class EventFixture extends AbstractFixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager A ObjectManager instance.
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        if (! $this->checkEnvironment('test')) {
            return;
        }

        /** @var Organize $organize */
        $organize = $this->getReference('organize1');

        /** @var Category $arts */
        $arts = $this->getReference('arts');
        /** @var Category $animals */
        $animals = $this->getReference('animals');
        /** @var Category $dance */
        $dance = $this->getReference('dance');

        // Event 1
        $event = new Event();

        $event
            ->setOrganize($organize)
            ->setName('event1')
            ->setEmail('event1@mail.dev')
            ->setAddress('event1 address')
            ->setPoint(new Point(55.9931214, 82.9763641))
            ->setAppointment(false)
            ->setZip('11111')
            ->setSite('http://event1.com')
            ->setPhonenumber('11111')
            ->setOutdoor('indoor')
            ->setDescription('some')
            ->setOrigin(date_create()->setTime(12, 30))
            ->setPrice(12.3)
            ->addCategory($arts)
            ->addCategory($animals);

        $this->addAges($event, [ 0, 1, 2, 4, 5, 6 ]);

        $manager->persist($event);

        // Event 2
        $event = new Event();

        $event
            ->setOrganize($organize)
            ->setName('event2')
            ->setEmail('event2@mail.dev')
            ->setAddress('event2 address')
            ->setPoint(new Point(55.968597, 82.970374))
            ->setAppointment(false)
            ->setZip('22222')
            ->setSite('http://event2.com')
            ->setPhonenumber('22222')
            ->setOutdoor('indoor')
            ->setDescription('some')
            ->setOrigin(date_create()->setTime(14, 30))
            ->addCategory($animals);

        $this->addAges($event, [ 4, 5, 6 ]);

        $manager->persist($event);

        // Event 3
        $event = new Event();

        $event
            ->setOrganize($organize)
            ->setName('event3')
            ->setEmail('event3@mail.dev')
            ->setAddress('event3 address')
            ->setPoint(new Point(55.994599, 82.674763))
            ->setAppointment(false)
            ->setZip('33333')
            ->setSite('http://event3.com')
            ->setPhonenumber('33333')
            ->setOutdoor('indoor')
            ->setDescription('some')
            ->setOrigin(date_create()->setTime(18, 30))
            ->addCategory($arts);

        $this->addAges($event, range(0, 21));

        $manager->persist($event);

        // Event 4
        $event = new Event();

        $event
            ->setOrganize($organize)
            ->setName('event4')
            ->setEmail('event4@mail.dev')
            ->setAddress('event4 address')
            ->setPoint(new Point(56.222035, 83.831074))
            ->setAppointment(false)
            ->setZip('44444')
            ->setSite('http://event4.com')
            ->setPhonenumber('44444')
            ->setOutdoor('indoor')
            ->setDescription('some')
            ->setOrigin(date_create()->setTime(15, 30))
            ->addCategory($dance);

        $this->addAges($event, [ 4, 5, 6, 7, 8, 9, 10 ]);

        $manager->persist($event);

        // Event 5
        $event = new Event();

        $event
            ->setOrganize($organize)
            ->setName('event5')
            ->setEmail('event5@mail.dev')
            ->setAddress('event5 address')
            ->setPoint(new Point(56.800935, 84.781391))
            ->setAppointment(false)
            ->setZip('55555')
            ->setSite('http://event5.com')
            ->setPhonenumber('55555')
            ->setOutdoor('indoor')
            ->setDescription('some')
            ->setOrigin(date_create()->setTime(15, 30))
            ->addCategory($animals)
            ->addCategory($dance);

        $this->addAges($event, [ 4, 5, 6, 7, 8, 9, 10 ]);

        $manager->persist($event);

        $manager->flush();
    }

    /**
     * @param Event $event A Event entity instance.
     * @param array $ages  Array of required ages.
     *
     * @return void
     */
    private function addAges(Event $event, array $ages)
    {
        foreach ($ages as $age) {
            $event->addAge(new Age($age));
        }
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
