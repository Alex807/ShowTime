<?php

namespace App\DataFixtures\userRelated;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private const ROLE_NAMES = [
        'Administrator',
        'Super Admin',
        'Content Manager',
        'Event Organizer',
        'Festival Director',
        'Marketing Manager',
        'Social Media Manager',
        'Customer Support',
        'Ticket Manager',
        'Artist Coordinator',
        'Venue Manager',
        'Security Manager',
        'Technical Support',
        'Finance Manager',
        'HR Manager',
        'Volunteer Coordinator',
        'Media Relations',
        'Sponsorship Manager',
        'Operations Manager',
        'Quality Assurance',
        'Data Analyst',
        'Community Manager',
        'Content Creator',
        'Event Photographer',
        'Sound Engineer',
        'Stage Manager',
        'Logistics Coordinator',
        'Guest Relations',
        'VIP Manager',
        'Merchandise Manager'
    ];

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
