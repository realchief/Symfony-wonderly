<?php

namespace AppBundle\DataFixtures\ORM;

use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use FrontendBundle\Entity\Age;
use FrontendBundle\Entity\Category;
use FrontendBundle\Entity\Event;
use UserBundle\Entity\Organize;
use UserBundle\Entity\User;

/**
 * Class UserFixture
 * @package AppBundle\DataFixtures\ORM
 */
class UserFixture extends AbstractFixture
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
        if (! $this->checkEnvironment([ 'test', 'dev' ])) {
            return;
        }

        /** @var UserManagerInterface $userManager */
        $userManager = $this->container->get('fos_user.user_manager');

        $admin = new User();
        $admin
            ->setFirstname('Admin')
            ->setLastname('Admin')
            ->setUsername('admin')
            ->setEmail('admin@email.com')
            ->setPlainPassword('admin')
            ->setRoles([ 'ROLE_SUPER_ADMIN' ])
            ->setEnabled(true);

        $userManager->updateUser($admin);

        $admin = new User();
        $admin
            ->setFirstname('Father')
            ->setLastname('Father')
            ->setUsername('father')
            ->setEmail('father@email.com')
            ->setPlainPassword('father')
            ->setRoles([ 'ROLE_FATHER' ])
            ->setEnabled(true);

        $userManager->updateUser($admin);
    }
}
