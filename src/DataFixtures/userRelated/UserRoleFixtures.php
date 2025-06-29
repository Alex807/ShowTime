<?php

namespace App\DataFixtures\userRelated;

use App\Entity\UserRole;
use App\Entity\UserAccount;
use App\Entity\Role;
use App\DataFixtures\traits\AppGeneralConstants;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserRoleFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use AppGeneralConstants;

    public function load(ObjectManager $manager): void
    {
        // Get all existing users and roles directly from database
        $userAccounts = $manager->getRepository(UserAccount::class)->findAll();
        $roles = $manager->getRepository(Role::class)->findAll();

        foreach ($userAccounts as $userAccount) {
            // Each user gets 1-MAX roles
            $numberOfRoles = mt_rand(1, min(self::MAX_ROLLS_PER_USER, count($roles))); // Don't exceed available roles
            $assignedRoles = []; //array to know what roles our user already has

            for ($i = 0; $i < $numberOfRoles; $i++) {
                // Get a random role that hasn't been assigned to this user yet
                do {
                    $randomRole = $roles[array_rand($roles)];
                } while (in_array($randomRole, $assignedRoles));

                $assignedRoles[] = $randomRole;

                $userRole = new UserRole();
                $userRole->setUserAccount($userAccount);
                $userRole->setRole($randomRole);

                // Set since date - random date within last 2 years
                $sinceDate = new \DateTime();
                $sinceDate->modify('-' . mt_rand(0, 730) . ' days'); // 0 to 2 years ago

                // Add some random hours and minutes for more realistic timestamps
                $sinceDate->modify('-' . mt_rand(0, 23) . ' hours');
                $sinceDate->modify('-' . mt_rand(0, 59) . ' minutes');

                $userRole->setSinceDate($sinceDate);

                $manager->persist($userRole);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['userRelated'];
    }
}
