<?php

namespace AppBundle\DataFixtures\ORM;

use \Doctrine\Common\DataFixtures\AbstractFixture as BaseFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class AbstractFixture
 * @package AppBundle\DataFixtures\ORM
 */
abstract class AbstractFixture extends BaseFixture implements
    OrderedFixtureInterface,
    ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance
     *                                           or null.
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * Checks that current environment equal to specified.
     *
     * @param string|string[] $environments Expected environment or environments.
     *
     * @return boolean
     */
    protected function checkEnvironment($environments)
    {
        $environment = $this->container->getParameter('kernel.environment');

        return in_array($environment, (array) $environments, true);
    }
}
