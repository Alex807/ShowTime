<?php

namespace App\DataFixtures\artistAmenity;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\AmenityData;
use App\Entity\EditionAmenity;
use App\Entity\FestivalEdition;
use App\Entity\Amenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EditionAmenityFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use AppGeneralConstants, AmenityData; //for general constants over the app
    public function load(ObjectManager $manager): void
    {
        // Get all existing festival editions and amenities
        $festivalEditions = $manager->getRepository(FestivalEdition::class)->findAll();
        $amenities = $manager->getRepository(Amenity::class)->findAll();

        if (empty($festivalEditions) || empty($amenities)) {
            return; // Skip if no data available
        }

        foreach ($festivalEditions as $edition) {
            // Each edition gets random number of amenities
            $numberOfAmenities = mt_rand(1, self::MAX_AMENITIES_PER_EDITION);
            $usedAmenities = []; // Track amenities already assigned to this edition

            // Randomly select amenities for this edition
            $availableAmenities = array_values($amenities); //this method return just values from a map
            shuffle($availableAmenities); //used to not start from the same elements every time

            for ($i = 0; $i < $numberOfAmenities && $i < count($availableAmenities); $i++) {
                $amenity = $availableAmenities[$i];

                // Skip if already used (shouldn't happen due to unique constraint, but safety check)
                if (in_array($amenity, $usedAmenities)) {
                    continue;
                }

                $usedAmenities[] = $amenity; //add it to already have it / edition

                $editionAmenity = new EditionAmenity();
                $editionAmenity->setEdition($edition);
                $editionAmenity->setAmenity($amenity);

                // Set start and end times based on amenity type
                $this->setAmenityTiming($editionAmenity, $amenity->getName(), $edition);

                $manager->persist($editionAmenity);
            }
        }

        $manager->flush();
    }

    private function setAmenityTiming(EditionAmenity $editionAmenity, string $amenityName, FestivalEdition $edition): void
    {
        // Get edition dates
        $editionStart = $edition->getStartDate();
        $editionEnd = $edition->getEndDate();

        if (in_array($amenityName, self::FULL_DURATION_AMENITIES)) {
            // Available throughout the entire festival
            $editionAmenity->setStartAt($editionStart);
            $editionAmenity->setEndAt($editionEnd);

        } elseif (in_array($amenityName, self::SCHEDULED_AMENITIES)) {
            // Specific time slots during the festival
            $startTime = clone $editionStart;
            $startTime->modify('+' . mt_rand(0, 3) . ' days'); // Random day during festival
            $startTime->modify('+' . mt_rand(8, 20) . ' hours'); // Random time of day

            $endTime = clone $startTime;
            $endTime->modify('+' . mt_rand(1, 6) . ' hours'); // Duration 1-6 hours

            $editionAmenity->setStartAt($startTime);
            $editionAmenity->setEndAt($endTime);

        } elseif (in_array($amenityName, self::ONE_TIME_AMENITIES)) {
            // One-time services, might be before, during, or after festival
            $serviceTime = clone $editionStart;
            $serviceTime->modify('-' . mt_rand(0, 2) . ' days'); // Can be before festival
            $serviceTime->modify('+' . mt_rand(0, 23) . ' hours');

            // Some services are instantaneous, others have duration
            if (in_array($amenityName, ['Luxury Transportation', 'Helicopter Transfer', 'Private Jet Charter'])) {
                // Transportation services have specific pickup/drop off times
                $endTime = clone $serviceTime;
                $endTime->modify('+' . mt_rand(1, 4) . ' hours');
                $editionAmenity->setEndAt($endTime);

            } elseif (in_array($amenityName, ['Professional Sound Equipment', 'Stage Lighting Package', 'Video Production Service'])) {
                // Technical services span the entire event
                $editionAmenity->setEndAt($editionEnd);
            } else {
                // Other one-time services
                $endTime = clone $serviceTime;
                $endTime->modify('+' . mt_rand(2, 12) . ' hours');
                $editionAmenity->setEndAt($endTime);
            }

            $editionAmenity->setStartAt($serviceTime);

        } else {
            // Default: available during festival with some random timing
            $startTime = clone $editionStart;
            $startTime->modify('+' . mt_rand(0, 2) . ' days');
            $startTime->modify('+' . mt_rand(6, 18) . ' hours');

            $endTime = clone $startTime;
            $endTime->modify('+' . mt_rand(2, 24) . ' hours');

            // Ensure end time doesn't exceed festival end
            if ($endTime > $editionEnd) {
                $endTime = $editionEnd;
            }

            $editionAmenity->setStartAt($startTime);
            $editionAmenity->setEndAt($endTime);
        }
    }

    public function getDependencies(): array
    {
        return [
            AmenityFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['artistAmenity'];
    }
}
