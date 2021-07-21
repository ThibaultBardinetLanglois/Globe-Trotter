<?php

namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword
        ($this->passwordHasher->hashPassword(
            $admin,
            'admin'
        ));

        $manager->persist($admin);

        $toto = new User();
        $toto->setUsername('toto');
        $toto->setPassword
        ($this->passwordHasher->hashPassword(
            $toto,
            'toto'
        ));

        $manager->persist($toto);

        $manager->flush();
    }
}
