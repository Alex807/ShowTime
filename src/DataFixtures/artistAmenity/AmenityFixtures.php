<?php

namespace App\DataFixtures\ArtistAmenity;

use App\Entity\Amenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AmenityFixtures extends Fixture implements FixtureGroupInterface
{
    private const AMENITIES_DATA = [
        // VIP & Premium Services
        [
            'name' => 'VIP Backstage Access',
            'description' => 'Exclusive access to backstage areas, meet and greet opportunities with artists, and premium viewing areas.',
            'capacity' => [10, 50],
            'price' => [500.00, 1500.00]
        ],
        [
            'name' => 'Artist Green Room',
            'description' => 'Private relaxation space for artists with comfortable seating, refreshments, and quiet environment.',
            'capacity' => [5, 15],
            'price' => [200.00, 800.00]
        ],
        [
            'name' => 'VIP Lounge Access',
            'description' => 'Premium lounge with complimentary drinks, gourmet food, air conditioning, and exclusive restrooms.',
            'capacity' => [20, 100],
            'price' => [150.00, 400.00]
        ],
        [
            'name' => 'Private Hospitality Suite',
            'description' => 'Luxury suite with panoramic stage views, personal butler service, and premium catering.',
            'capacity' => [8, 25],
            'price' => [1000.00, 3000.00]
        ],

        // Accommodation Services
        [
            'name' => 'Artist Hotel Suite',
            'description' => 'Luxury hotel accommodation with room service, spa access, and transportation to venue.',
            'capacity' => [1, 4],
            'price' => [300.00, 1200.00]
        ],
        [
            'name' => 'Premium Camping Package',
            'description' => 'Pre-pitched luxury tents with comfortable beds, private bathrooms, and daily housekeeping.',
            'capacity' => [2, 6],
            'price' => [200.00, 600.00]
        ],
        [
            'name' => 'Glamping Experience',
            'description' => 'Glamorous camping with furnished tents, electricity, Wi-Fi, and shared premium facilities.',
            'capacity' => [2, 8],
            'price' => [150.00, 450.00]
        ],
        [
            'name' => 'Artist Trailer Rental',
            'description' => 'Mobile dressing room and rest area with air conditioning, mirrors, and power outlets.',
            'capacity' => [1, 6],
            'price' => [100.00, 400.00]
        ],

        // Food & Beverage Services
        [
            'name' => 'Gourmet Catering Service',
            'description' => 'Premium catering with chef-prepared meals, dietary accommodations, and table service.',
            'capacity' => [10, 200],
            'price' => [50.00, 150.00]
        ],
        [
            'name' => 'Private Bar Service',
            'description' => 'Exclusive bar with premium spirits, craft cocktails, and dedicated bartender.',
            'capacity' => [15, 80],
            'price' => [300.00, 1000.00]
        ],
        [
            'name' => 'Artist Meal Package',
            'description' => 'Daily meal service with healthy options, special dietary requirements, and backstage delivery.',
            'capacity' => [1, 20],
            'price' => [30.00, 100.00]
        ],
        [
            'name' => 'VIP Dining Experience',
            'description' => 'Multi-course dining experience with wine pairings and exclusive chef interactions.',
            'capacity' => [8, 40],
            'price' => [100.00, 300.00]
        ],

        // Transportation Services
        [
            'name' => 'Luxury Transportation',
            'description' => 'Premium vehicle service with professional chauffeur, refreshments, and Wi-Fi.',
            'capacity' => [1, 8],
            'price' => [200.00, 800.00]
        ],
        [
            'name' => 'Helicopter Transfer',
            'description' => 'VIP helicopter transportation to avoid traffic with aerial views and champagne service.',
            'capacity' => [2, 6],
            'price' => [1500.00, 5000.00]
        ],
        [
            'name' => 'Private Jet Charter',
            'description' => 'Exclusive private jet service with luxury amenities and flexible scheduling.',
            'capacity' => [4, 12],
            'price' => [5000.00, 20000.00]
        ],
        [
            'name' => 'Festival Shuttle Service',
            'description' => 'Convenient shuttle transportation between venues, hotels, and airports.',
            'capacity' => [10, 50],
            'price' => [25.00, 100.00]
        ],

        // Technical & Production Services
        [
            'name' => 'Professional Sound Equipment',
            'description' => 'High-end audio equipment rental with technical support and setup assistance.',
            'capacity' => [50, 10000],
            'price' => [500.00, 5000.00]
        ],
        [
            'name' => 'Stage Lighting Package',
            'description' => 'Complete lighting setup with LED systems, special effects, and lighting technician.',
            'capacity' => [100, 5000],
            'price' => [800.00, 8000.00]
        ],
        [
            'name' => 'Video Production Service',
            'description' => 'Professional video recording and live streaming with multiple camera angles.',
            'capacity' => [50, 2000],
            'price' => [1000.00, 10000.00]
        ],
        [
            'name' => 'Technical Crew Support',
            'description' => 'Experienced technical staff for setup, operation, and breakdown of equipment.',
            'capacity' => [10, 100],
            'price' => [100.00, 500.00]
        ],

        // Wellness & Comfort Services
        [
            'name' => 'Artist Spa Package',
            'description' => 'Relaxation services including massage, facial treatments, and wellness consultations.',
            'capacity' => [1, 10],
            'price' => [150.00, 500.00]
        ],
        [
            'name' => 'Medical Support Service',
            'description' => 'On-site medical staff with first aid, emergency response, and health consultations.',
            'capacity' => [10, 1000],
            'price' => [200.00, 2000.00]
        ],
        [
            'name' => 'Security Detail',
            'description' => 'Professional security personnel for artist protection and crowd management.',
            'capacity' => [1, 50],
            'price' => [300.00, 2000.00]
        ],
        [
            'name' => 'Personal Assistant Service',
            'description' => 'Dedicated assistant for scheduling, coordination, and personal needs management.',
            'capacity' => [1, 5],
            'price' => [200.00, 800.00]
        ],

        // Entertainment & Activities
        [
            'name' => 'Artist Meet & Greet',
            'description' => 'Organized fan interaction sessions with photo opportunities and autograph signing.',
            'capacity' => [20, 200],
            'price' => [50.00, 200.00]
        ],
        [
            'name' => 'Exclusive Workshop Access',
            'description' => 'Private masterclasses and workshops with renowned artists and industry professionals.',
            'capacity' => [10, 50],
            'price' => [100.00, 500.00]
        ],
        [
            'name' => 'VIP Photo Shoot',
            'description' => 'Professional photography session with styling, makeup, and digital delivery.',
            'capacity' => [1, 10],
            'price' => [300.00, 1500.00]
        ],
        [
            'name' => 'Exclusive Merchandise Package',
            'description' => 'Limited edition merchandise, signed items, and collectible festival memorabilia.',
            'capacity' => [1, 1000],
            'price' => [25.00, 200.00]
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        $numberOfAmenities = count(self::AMENITIES_DATA);

        for ($i = 0; $i < $numberOfAmenities; $i++) {
            $amenityData = self::AMENITIES_DATA[$i];

            $amenity = new Amenity();
            $amenity->setName($amenityData['name']);
            $amenity->setDescription($amenityData['description']);

            // Random capacity within the specified range
            $minCapacity = $amenityData['capacity'][0];
            $maxCapacity = $amenityData['capacity'][1];
            $capacity = mt_rand($minCapacity, $maxCapacity);
            $amenity->setPeopleCapacity($capacity);

            // Random price within the specified range
            $minPrice = $amenityData['price'][0];
            $maxPrice = $amenityData['price'][1];
            $price = mt_rand($minPrice * 100, $maxPrice * 100) / 100; // Maintain 2 decimal places
            $amenity->setPrice(number_format($price, 2, '.', ''));

            $manager->persist($amenity);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['artistAmenity'];
    }
}
