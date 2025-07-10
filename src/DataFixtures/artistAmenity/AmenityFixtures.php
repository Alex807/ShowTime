<?php

namespace App\DataFixtures\artistAmenity;

use App\DataFixtures\traits\hardcodedData\AmenityData;
use App\Entity\Amenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AmenityFixtures extends Fixture implements FixtureGroupInterface
{
    use AmenityData;
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
            $amenity->setPrice($price);

            $manager->persist($amenity);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['artistAmenity'];
    }
}
