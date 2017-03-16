<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            if ($i == 1) {
                $user->setIsAdmin(true);
            }
            $user->setEmail('user'.$i.'@company.com')
                ->setUserName('user'.$i)
                ->setPlainPassword('user'.$i)
                ->setSalt(md5(random_bytes(15)));
            $password = $this->container->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password)
                ->eraseCredentials();

            $em->persist($user);
            $this->setReference('user-'.$i, $user);
        }
        $em->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}