<?php

namespace App\DataFixtures\festivalRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\FestivalData;
use App\Entity\Festival;
use App\Entity\FestivalEdition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FestivalEditionFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use AppGeneralConstants, FestivalData; //for using constants

    public function load(ObjectManager $manager): void
    {
        // Get all existing festivals from database
        $festivals = $manager->getRepository(Festival::class)->findAll();

        foreach ($festivals as $festival) {

            // Random number of editions per festival
            $numberOfEditions = mt_rand(1, self::MAX_FESTIVAL_EDITIONS);

            // Start from a random year between 2019-2021 to have realistic historical data
            $currentYear = (int)date('Y');
            $startYear = $currentYear + 1; //we start always from current year

            for ($editionIndex = 0; $editionIndex < $numberOfEditions; $editionIndex++) {
                $year = $startYear - $editionIndex; //we go backwords

                $edition = new FestivalEdition();
                $edition->setFestival($festival);
                $edition->setYearHappened($year);

                // Create realistic venue name
                $venueType = self::VENUE_TYPES[array_rand(self::VENUE_TYPES)];
                $venueName = self::VENUE_NAMES[array_rand(self::VENUE_NAMES)];
                $edition->setVenueName($venueName . '-' . $venueType);

                // Set realistic description
                $description = self::EDITION_DESCRIPTIONS[array_rand(self::EDITION_DESCRIPTIONS)];
                $edition->setDescription($description);

                // Set status based on year (past events are completed, future might be upcoming)
                $currentYear = (int)date('Y');
                if ($year < $currentYear) {
                    $status = mt_rand(1, 10) <= 8 ? 'completed' : 'cancelled';
                } elseif ($year == $currentYear) {
                    $status = ['completed', 'upcoming', 'sold_out'][array_rand(['completed', 'upcoming', 'sold_out'])];
                } else {
                    $status = ['upcoming', 'postponed'][array_rand(['upcoming', 'postponed'])];
                }
                $edition->setStatus($status);

                // Set realistic dates within the year
                $festivalMonth = mt_rand(4, 9); // April to September (festival season)
                $festivalDay = mt_rand(1, 28);

                $startDate = new \DateTime();
                $startDate->setDate($year, $festivalMonth, $festivalDay);
                $edition->setStartDate($startDate);

                $endDate = clone $startDate;
                $festivalDuration = mt_rand(1, 4); // 1-4 days
                $endDate->modify("+{$festivalDuration} days");
                $edition->setEndDate($endDate);

                $defaultRange = [5000, 30000];
                $range = self::VENUE_CAPACITY_RANGES[$venueType] ?? $defaultRange;
                $capacity = mt_rand($range[0], $range[1]);
                $edition->setPeopleCapacity($capacity);

                // Set terms and conditions
                $termsTemplate = self::TERMS_CONDITIONS_TEMPLATES[array_rand(self::TERMS_CONDITIONS_TEMPLATES)];
                $edition->setTermsConditions($termsTemplate);

                // Set updated at - random time within last year
                $updatedAt = new \DateTime();
                $updatedAt->modify('-' . mt_rand(0, 365) . ' days');
                $updatedAt->modify('-' . mt_rand(0, 23) . ' hours');
                $updatedAt->modify('-' . mt_rand(0, 59) . ' minutes');
                $edition->setUpdatedAt($updatedAt);

                $manager->persist($edition);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FestivalFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['festivalRelated'];
    }
}
