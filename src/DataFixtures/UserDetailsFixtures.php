<?php

namespace App\DataFixtures;

use App\DataFixtures\traits\TotalExistingUsersTrait;
use App\Entity\UserDetails;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserDetailsFixtures extends Fixture
{
    private const FIRST_NAMES = [
        'Alexandru', 'Maria', 'John', 'Wei', 'Yuki', 'Mohammed',
        'Sofia', 'Ivan', 'Giuseppe', 'Hans', 'Fatima', 'Pedro',
        'Raj', 'Olga', 'Lars', 'Chen', 'Aisha', 'Carlos',
        'Emma', 'Antoine', 'Dimitri', 'Sven', 'Isabella', 'Klaus',
        'Priya', 'Miguel', 'Andrei', 'Yusuf', 'Anna', 'Giovanni'
    ];

    private const LAST_NAMES = [
        'Popescu', 'Smith', 'Zhang', 'Tanaka', 'Al-Sayed',
        'García', 'Ivanov', 'Rossi', 'Mueller', 'Hassan',
        'Silva', 'Patel', 'Kowalski', 'Andersson', 'Li',
        'Kumar', 'Rodriguez', 'Dubois', 'Petrov', 'Bergmann',
        'Ferrari', 'Sanchez', 'Nielsen', 'Cohen', 'Kim',
        'Santos', 'Kovac', 'Ahmed', 'Jensen', 'Romano'
    ];

    use TotalExistingUsersTrait; //constants between fixtures
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::TOTAL_EXISTING_USERS; $i++) {
            $userDetails = new UserDetails();

            // Random name selection
            $userDetails->setFirstName(self::FIRST_NAMES[array_rand(self::FIRST_NAMES)]);
            $userDetails->setLastName(self::LAST_NAMES[array_rand(self::LAST_NAMES)]);

            $userDetails->setAge(mt_rand(14, 50));
            $userDetails->setPhoneNo('+407'. mt_rand(10000000, 99999999));

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

            $manager->persist($userDetails);
        }

        $manager->flush(); //force the changes to be made in DB (is a MUST this line at the end)
    }
}
