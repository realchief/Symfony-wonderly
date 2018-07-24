<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use FrontendBundle\Entity\Category;

/**
 * Class CategoryFixture
 * @package AppBundle\DataFixtures\ORM
 */
class CategoryFixture extends AbstractFixture
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
        $category = new Category();
        $category->setTag('Arts + Crafts');
        $manager->persist($category);
        $this->addReference('arts', $category);

        $category = new Category();
        $category->setTag('Dance+Fitness');
        $manager->persist($category);
        $this->addReference('dance', $category);

        $category = new Category();
        $category->setTag('Animals/Parks/Nature');
        $manager->persist($category);
        $this->addReference('animals', $category);

        $category = new Category();
        $category->setTag('Market + Fair');
        $manager->persist($category);
        $this->addReference('market', $category);

        $category = new Category();
        $category->setTag('Music + Singing + Drama');
        $manager->persist($category);
        $this->addReference('music', $category);

        $category = new Category();
        $category->setTag('Exhibitions/Shows');
        $manager->persist($category);
        $this->addReference('shows', $category);

        $category = new Category();
        $category->setTag('Movie');
        $manager->persist($category);
        $this->addReference('movie', $category);

        $category = new Category();
        $category->setTag('Special Learning');
        $manager->persist($category);
        $this->addReference('learning', $category);

        $category = new Category();
        $category->setTag('Performances');
        $manager->persist($category);
        $this->addReference('performances', $category);

        $category = new Category();
        $category->setTag('Story + Talks');
        $manager->persist($category);
        $this->addReference('story', $category);

        $category = new Category();
        $category->setTag('All + Events');
        $manager->persist($category);
        $this->addReference('all', $category);

        $manager->flush();
    }
}
