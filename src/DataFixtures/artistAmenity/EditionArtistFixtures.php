<?php

namespace App\DataFixtures\artistAmenity;

use App\DataFixtures\traits\AppGeneralConstants;
use App\Entity\EditionArtist;
use App\Entity\FestivalEdition;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EditionArtistFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    // Festival time slots (24-hour format)
    private const TIME_SLOTS = [
        ['start' => 12, 'end' => 18], // Afternoon slot 12:00-18:00
        ['start' => 18, 'end' => 24], // Evening slot 18:00-24:00
        ['start' => 0, 'end' => 4],   // Late night slot 00:00-04:00
    ];

    use AppGeneralConstants;
    public function load(ObjectManager $manager): void
    {
        // Get all existing festival editions and artists
        $festivalEditions = $manager->getRepository(FestivalEdition::class)->findAll();
        $artists = $manager->getRepository(Artist::class)->findAll();

        if (empty($festivalEditions) || empty($artists)) {
            return; // Skip if no data available
        }

        foreach ($festivalEditions as $edition) {
            // Each edition gets random number of artists
            $numberOfArtists = mt_rand(self::MIN_ARTISTS_PER_EDITION, self::MAX_ARTISTS_PER_EDITION);
            $usedArtists = []; // Track artists already assigned to this edition

            // Generate festival dates
            $festivalStartDate = $edition->getStartDate();
            $festivalEndDate = $edition->getEndDate();
            $interval = $festivalStartDate->diff($festivalEndDate);
            $festivalDays = max(1, $interval->days);  //calculate days for festival edition


            // Create performance schedule for each day
            $dailySchedules = [];
            for ($day = 0; $day < $festivalDays; $day++) {
                $currentDate = clone $festivalStartDate;
                $currentDate->modify('+' . $day . ' days');
                $dailySchedules[$day] = [
                    'date' => $currentDate,
                    'performances' => []
                ];
            }

            // Randomly select and assign artists
            $availableArtists = array_values($artists);
            shuffle($availableArtists);

            for ($i = 0; $i < $numberOfArtists && $i < count($availableArtists); $i++) {
                $artist = $availableArtists[$i];

                // Skip if already used
                if (in_array($artist, $usedArtists)) {
                    continue;
                }

                $usedArtists[] = $artist; //update the list of already used artists

                // Select random day for performance
                $performanceDay = mt_rand(0, $festivalDays - 1);
                $performanceDate = $dailySchedules[$performanceDay]['date'];

                // Generate performance time slot
                $timeSlot = $this->generatePerformanceTimeSlot($dailySchedules[$performanceDay]['performances']);

                if ($timeSlot === null) {
                    // Skip if no available time slot
                    continue;
                }

                $editionArtist = new EditionArtist();
                $editionArtist->setEdition($edition);
                $editionArtist->setArtist($artist);
                $editionArtist->setIsHeadliner(false); // All set to false as requested

                // Set performance date
                $editionArtist->setPerformanceDate($performanceDate);

                // Set start and end times
                $startTime = new \DateTime();
                $startTime->setTime($timeSlot['start_hour'], $timeSlot['start_minute']);
                $editionArtist->setStartTime($startTime);

                $endTime = new \DateTime();
                $endTime->setTime($timeSlot['end_hour'], $timeSlot['end_minute']);
                $editionArtist->setEndTime($endTime);

                // Add to daily schedule for conflict checking
                $dailySchedules[$performanceDay]['performances'][] = [
                    'start_hour' => $timeSlot['start_hour'],
                    'start_minute' => $timeSlot['start_minute'],
                    'end_hour' => $timeSlot['end_hour'],
                    'end_minute' => $timeSlot['end_minute'],
                    'duration' => $timeSlot['duration']
                ];

                $manager->persist($editionArtist);
            }
        }

        $manager->flush();
    }

    private function generatePerformanceTimeSlot(array $existingPerformances): ?array
    {
        $maxAttempts = 50;
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $attempts++;

            // Select random time slot category
            $timeSlotCategory = self::TIME_SLOTS[array_rand(self::TIME_SLOTS)];

            // Generate random performance duration
            $duration = mt_rand(self::MIN_PERFORMANCE_DURATION, self::MAX_PERFORMANCE_DURATION);

            // Calculate available time window
            $availableMinutes = ($timeSlotCategory['end'] - $timeSlotCategory['start']) * 60;
            if ($timeSlotCategory['end'] < $timeSlotCategory['start']) {
                // Handle overnight slot (00:00-04:00)
                $availableMinutes = (24 - $timeSlotCategory['start'] + $timeSlotCategory['end']) * 60;
            }

            // Check if duration fits in time slot
            if ($duration > $availableMinutes - self::PERFORMANCE_BUFFER) {
                continue;
            }

            // Generate random start time within the slot
            $maxStartMinutes = $availableMinutes - $duration - self::PERFORMANCE_BUFFER;
            $startOffsetMinutes = mt_rand(0, max(0, $maxStartMinutes));

            $startHour = $timeSlotCategory['start'];
            $startMinute = $startOffsetMinutes % 60;
            $startHour += intval($startOffsetMinutes / 60);

            // Handle hour overflow
            if ($startHour >= 24) {
                $startHour -= 24;
            }

            // Calculate end time
            $endTotalMinutes = ($startHour * 60 + $startMinute) + $duration;
            $endHour = intval($endTotalMinutes / 60) % 24;
            $endMinute = $endTotalMinutes % 60;

            $proposedSlot = [
                'start_hour' => $startHour,
                'start_minute' => $startMinute,
                'end_hour' => $endHour,
                'end_minute' => $endMinute,
                'duration' => $duration
            ];

            // Check for conflicts with existing performances
            if (!$this->hasTimeConflict($proposedSlot, $existingPerformances)) {
                return $proposedSlot;
            }
        }

        return null; // No available slot found
    }

    private function hasTimeConflict(array $proposedSlot, array $existingPerformances): bool
    {
        $proposedStart = $proposedSlot['start_hour'] * 60 + $proposedSlot['start_minute'];
        $proposedEnd = $proposedSlot['end_hour'] * 60 + $proposedSlot['end_minute'];

        // Handle overnight performances
        if ($proposedEnd < $proposedStart) {
            $proposedEnd += 24 * 60;
        }

        foreach ($existingPerformances as $existing) {
            $existingStart = $existing['start_hour'] * 60 + $existing['start_minute'];
            $existingEnd = $existing['end_hour'] * 60 + $existing['end_minute'];

            // Handle overnight performances
            if ($existingEnd < $existingStart) {
                $existingEnd += 24 * 60;
            }

            // Check for overlap (including buffer time)
            $bufferStart = $existingStart - self::PERFORMANCE_BUFFER;
            $bufferEnd = $existingEnd + self::PERFORMANCE_BUFFER;

            if (($proposedStart >= $bufferStart && $proposedStart < $bufferEnd) ||
                ($proposedEnd > $bufferStart && $proposedEnd <= $bufferEnd) ||
                ($proposedStart <= $bufferStart && $proposedEnd >= $bufferEnd)) {
                return true; // Conflict found
            }
        }

        return false; // No conflict
    }

    public function getDependencies(): array
    {
        return [
            ArtistFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['artistAmenity'];
    }
}
