<?php

namespace App\DataFixtures\userRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\Entity\UserAccount;
use App\Entity\UserDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAccountFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher) //used to encrypt the password before save it to db
    {
        $this->hasher = $hasher;
    }

    use AppGeneralConstants; //constants between fixtures
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::TOTAL_EXISTING_USERS; $i++) {
            $userAccount = new UserAccount();

            $userAccount->setEmail("user{$i}@temp.com"); //set a temporary email that would be updated in UserDetailsFixtures(to contain matching names)
            $userAccount->setPassword($this->hasher->hashPassword($userAccount, 'password' . $i));
            $manager->persist($userAccount);
        }

        $manager->flush(); //force the changes to be made in DB (is a MUST this line at the end)
    }

    public static function getGroups(): array
    {
        return ['userRelated'];
    }
}
