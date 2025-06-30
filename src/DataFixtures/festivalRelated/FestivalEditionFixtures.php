<?php

namespace App\DataFixtures\festivalRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\Entity\Festival;
use App\Entity\FestivalEdition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FestivalEditionFixtures extends Fixture implements DependentFixtureInterface
{
    private const VENUE_TYPES = [
        'Arena', 'Stadium', 'Park', 'Beach', 'Amphitheater', 'Convention Center',
        'Outdoor Stage', 'Concert Hall', 'Festival Grounds', 'Open Air Theater',
        'Sports Complex', 'Exhibition Center', 'Cultural Center', 'Music Hall',
        'Fairgrounds', 'Racecourse', 'Castle Grounds', 'Historic Site'
    ];

    private const VENUE_NAMES = [
        'Central Park', 'Olympic Stadium', 'Royal Arena', 'Sunset Beach', 'Grand Amphitheater',
        'Metropolitan Center', 'Riverside Grounds', 'Golden Gate Park', 'Crystal Palace',
        'Phoenix Stadium', 'Liberty Square', 'Heritage Gardens', 'Marina Bay',
        'Thunder Valley', 'Moonlight Theater', 'Diamond Arena', 'Emerald Fields',
        'Silver Lake', 'Copper Canyon', 'Iron Mountain'
    ];

    private const EDITION_DESCRIPTIONS = [
        'An unforgettable musical journey featuring world-class artists and immersive experiences.',
        'Three days of non-stop entertainment with multiple stages and diverse musical genres.',
        'The ultimate celebration of music, art, and culture in a stunning outdoor setting.',
        'A premium festival experience combining legendary performers with emerging talent.',
        'An epic gathering of music lovers from around the world in a breathtaking venue.',
        'The definitive music festival featuring cutting-edge production and stellar lineups.',
        'A transformative experience blending music, technology, and artistic expression.',
        'The most anticipated music event of the year with exclusive performances.',
        'A multi-sensory festival experience featuring interactive art installations.',
        'The perfect fusion of music, food, and culture in an iconic location.'
    ];

    private const POSSIBLE_STATUSES = [
        'completed', 'upcoming', 'cancelled', 'postponed', 'sold_out'
    ];

    private const TERMS_CONDITIONS_TEMPLATES = [
        'All attendees must be 18+ with valid ID. No outside food or beverages allowed. Festival reserves the right to search bags and refuse entry.',
        'Tickets are non-refundable and non-transferable. Camping facilities available with separate ticket purchase. No glass containers permitted.',
        'Entry subject to security screening. Professional cameras prohibited without media accreditation. Medical facilities available on-site.',
        'Weather-dependent event - no refunds for weather-related cancellations. Designated smoking areas only. Lost wristbands cannot be replaced.',
        'Age restrictions apply for certain areas. No re-entry policy in effect. Emergency evacuation procedures will be announced if necessary.'
    ];

    use AppGeneralConstants; //for using constants

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

                // Realistic capacity based on venue type
                $capacityRanges = [
                    'Arena' => [5000, 20000],
                    'Stadium' => [30000, 80000],
                    'Park' => [10000, 50000],
                    'Beach' => [5000, 25000],
                    'Amphitheater' => [3000, 15000]
                ];

                $defaultRange = [5000, 30000];
                $range = $capacityRanges[$venueType] ?? $defaultRange;
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
}
