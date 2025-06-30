<?php

namespace App\DataFixtures\userRelated;

use App\DataFixtures\traits\hardcodedData\RoleData;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use RoleData;

    public function load(ObjectManager $manager): void
    {
        // Use all role names from the array
        $numberOfRoles = count(self::ROLE_NAMES);

        for ($i = 0; $i < $numberOfRoles; $i++) {
            $role = new Role();
            $role->setName(self::ROLE_NAMES[$i]);

            $manager->persist($role);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserAccountFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['userRelated'];
    }
}
