<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PostComment;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPostCommentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $em)
    {
        for($i = 1; $i <= 150; $i++) {
            $postComment = new PostComment();

            $postComment->setMessage('Donec dolor est, ullamcorper in felis sed, dictum tincidunt felis. Aenean pulvinar bibendum odio, non rutrum velit tempus elementum. Donec hendrerit lorem vitae ullamcorper posuere. Sed mattis sem eget leo lobortis, sit amet volutpat risus ultricies. Ut facilisis blandit metus, facilisis luctus neque blandit ut. Praesent nec ultricies justo. Vestibulum vel massa turpis. Integer vestibulum massa id gravida lobortis. In ut sollicitudin ante. Duis tempus imperdiet lacinia. Donec aliquet quam interdum nisi iaculis, nec aliquet ligula viverra. Nulla vitae dolor ex.')
                ->setPost($this->getReference('post-'.rand(1, 15)))
                ->setUser($this->getReference('user-'.rand(1, 10)));

            $em->persist($postComment);
            $this->addReference('postComment-'.$i, $postComment);
        }
        $em->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}