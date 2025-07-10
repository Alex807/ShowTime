<?php

namespace App\DataFixtures\userRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\UserData;
use App\Entity\UserAccount;
use App\Entity\UserDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserDetailsFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    use AppGeneralConstants, UserData;
    public function load(ObjectManager $manager): void
    {
        // Get all existing user accounts
        $userAccounts = $manager->getRepository(UserAccount::class)->findAll();

        foreach ($userAccounts as $index => $userAccount) {
            $userDetails = new UserDetails();

            // Random name selection
            $firstName = self::FIRST_NAMES[array_rand(self::FIRST_NAMES)];
            $lastName = self::LAST_NAMES[array_rand(self::LAST_NAMES)];

            $userDetails->setFirstName($firstName);
            $userDetails->setLastName($lastName);
            $userDetails->setAge(mt_rand(14, 50));
            $userDetails->setPhoneNo('+407'. mt_rand(10000000, 99999999));

            // Set the FK relationship - UserDetails owns the FK to UserAccount
            $userDetails->setUser($userAccount);

            // Created date - random within last month
            $createdAt = new \DateTime();
            $createdAt->modify('-' . mt_rand(0, 30) . ' days');
            $createdAt->modify('-' . mt_rand(0, 23) . ' hours');
            $createdAt->modify('-' . mt_rand(0, 59) . ' minutes');
            $userDetails->setCreatedAt($createdAt);

            // Updated date - random between created date and up to 7 days after created date
            $updatedAt = clone $createdAt;
            $daysToAdd = mt_rand(0, 7);
            $updatedAt->modify('+' . $daysToAdd . ' days');
            // Add some random hours and minutes
            $updatedAt->modify('+' . mt_rand(0, 23) . ' hours');
            $updatedAt->modify('+' . mt_rand(0, 59) . ' minutes');
            $userDetails->setUpdatedAt($updatedAt);

            // Update the UserAccount email with proper names
            $emailPrefix = $this->createEmailPrefix($firstName, $lastName);
            $number = mt_rand(10, 99);
            $emailDomain = (mt_rand(0, 1) === 0) ? '@gmail.com' : '@yahoo.com';
            $email = strtolower($emailPrefix . $number . $emailDomain);

            $userAccount->setEmail($email); //update the EMAIL field to be matching names in both tables

            $manager->persist($userDetails);
        }

        $manager->flush(); //force the changes to be made in DB (is a MUST this line at the end)
    }

    private function createEmailPrefix(string $firstName, string $lastName): string
    {
        // Remove any special characters and spaces
        $firstName = $this->cleanString($firstName);
        $lastName = $this->cleanString($lastName);

        // Create different email patterns
        $patterns = [
            fn() => $firstName . '.' . $lastName,
            fn() => $firstName . '_' . $lastName,
            fn() => $firstName . $lastName,
            fn() => substr($firstName, 0, 1) . $lastName,
            fn() => $firstName . substr($lastName, 0, 1),
            fn() => $lastName . '.' . $firstName,
            fn() => $lastName . '_' . $firstName
        ];

        // Randomly select a pattern
        $selectedPattern = $patterns[array_rand($patterns)];

        return (string)$selectedPattern();
    }

    private function cleanString(string $str): string
    {
        // Remove any remaining special characters and spaces
        return preg_replace('/[^A-Za-z0-9]/', '', $str);
    }

    public function getDependencies(): array
    {
        return [
            UserAccountFixtures::class, // This ensures UserAccountFixtures runs first to set property for details
        ];
    }

    public static function getGroups(): array
    {
        return ['userRelated'];
    }
}
