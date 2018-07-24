<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use UserBundle\Entity\Organize;
use UserBundle\Entity\User;

/**
 * Class OrganizeFixture
 * @package AppBundle\DataFixtures\ORM
 */
class OrganizeFixture extends AbstractFixture
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

        /** @var UserManagerInterface $userManager */
        $userManager = $this->container->get('fos_user.user_manager');

        $organize = new Organize();

        /** @var User $user */
        $user = $userManager->createUser();

        $user
            ->setFirstname('John')
            ->setLastname('Due')
            ->setEmail('organize1@mail.dev')
            ->setPlainPassword('123456')
            ->setUsername('organize1');

        $userManager->updateUser($user);

        $organize
            ->setProfession('Teacher')
            ->setLocation('Some')
            ->setAge(20)
            ->setUser($user)
            ->setAddress('organize1 address');
        $manager->persist($organize);
        $this->addReference('organize1', $organize);

        $manager->flush();
    }
}
