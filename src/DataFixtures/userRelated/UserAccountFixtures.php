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

class UserAccountFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    use AppGeneralConstants; //constants between fixtures
    public function load(ObjectManager $manager): void
    {
        // Get all existing user details
        $userDetails = $manager->getRepository(UserDetails::class)->findAll();

        foreach ($userDetails as $index => $details) {
            $userAccount = new UserAccount();

            // Create email from first name and last name
            $emailPrefix = $this->createEmailPrefix($details->getFirstName(), $details->getLastName());
            $number = mt_rand(10, 99); //for adding to create unique email address
            $emailDomain = (mt_rand(0, 1) === 0) ? '@gmail.com' : '@yahoo.com';
            $email = strtolower($emailPrefix . $number . $emailDomain);

            $userAccount->setEmail($email);
            $userAccount->setPassword($this->hasher->hashPassword($userAccount, 'password' . $index));
            $userAccount->setUserDetails($details);

            $manager->persist($userAccount);
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

    public static function getGroups(): array
     {
         return ['userRelated'];
     }

    public function getDependencies(): array
    {
        return [
            UserDetailsFixtures::class, // This ensures UserDetailsFixtures runs first
        ];
    }
}
