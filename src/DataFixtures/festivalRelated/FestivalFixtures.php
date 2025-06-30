<?php

namespace App\DataFixtures\festivalRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\FestivalData;
use App\Entity\Festival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class FestivalFixtures extends Fixture implements FixtureGroupInterface
{
    use AppGeneralConstants, FestivalData; //for using trait for constants for entire app
    public function load(ObjectManager $manager): void
    {
        $usedNames = [];

        for ($i = 0; $i < self::TOTAL_EXISTING_FESTIVALS; $i++) {
            $festival = new Festival();

            // Get unique festival name
            do {
                $festivalName = self::FESTIVAL_NAMES[array_rand(self::FESTIVAL_NAMES)];
            } while (in_array($festivalName, $usedNames));
            $usedNames[] = $festivalName;

            $festival->setName($festivalName);

            // Select random country and corresponding city
            $country = array_rand(self::COUNTRIES_CITIES);
            $cities = self::COUNTRIES_CITIES[$country];
            $city = $cities[array_rand($cities)];

            $festival->setCountry($country);
            $festival->setCity($city);

            // Street details
            $streetName = self::STREET_NAMES[array_rand(self::STREET_NAMES)];
            $streetNo = mt_rand(1, 999);

            $festival->setStreetName($streetName);
            $festival->setStreetNo($streetNo);

            // Contact email based on festival name
            $contactPrefix = $this->createEmailPrefix($festivalName);
            $contactDomain = self::CONTACT_DOMAINS[array_rand(self::CONTACT_DOMAINS)];
            $festival->setFestivalContact($contactPrefix . $contactDomain);

            // Website (80% chance of having one)
            if (mt_rand(1, 10) <= 8) {
                $websitePrefix = $this->createWebsitePrefix($festivalName);
                $websiteDomain = self::WEBSITE_DOMAINS[array_rand(self::WEBSITE_DOMAINS)];
                $festival->setWebsite('https://www.' . $websitePrefix . '.' . $websiteDomain);
            }

            // Logo URL using placeholder service
            $logoStyle = self::LOGO_PLACEHOLDER_STYLES[array_rand(self::LOGO_PLACEHOLDER_STYLES)];
            $logoSize = mt_rand(200, 400);
            $festival->setLogoUrl("https://placehold.co/{$logoSize}x{$logoSize}/{$logoStyle}");

            // Updated at - random date within last 6 months
            $updatedAt = new \DateTime();
            $updatedAt->modify('-' . mt_rand(0, 180) . ' days');
            $updatedAt->modify('-' . mt_rand(0, 23) . ' hours');
            $updatedAt->modify('-' . mt_rand(0, 59) . ' minutes');
            $festival->setUpdatedAt($updatedAt);

            $manager->persist($festival);
        }

        $manager->flush();
    }

    private function createEmailPrefix(string $festivalName): string
    {
        // Clean and create email-friendly prefix
        $prefix = strtolower($festivalName);
        $prefix = preg_replace('/[^a-z0-9\s]/', '', $prefix);
        $prefix = preg_replace('/\s+/', '', $prefix);

        // Truncate if too long and add common prefixes
        if (strlen($prefix) > 15) {
            $prefix = substr($prefix, 0, 15);
        }

        $prefixes = ['info', 'contact', 'hello', 'support'];
        $selectedPrefix = $prefixes[array_rand($prefixes)];

        return $selectedPrefix . '.' . $prefix;
    }

    private function createWebsitePrefix(string $festivalName): string
    {
        // Clean and create website-friendly prefix
        $prefix = strtolower($festivalName);
        $prefix = preg_replace('/[^a-z0-9\s]/', '', $prefix);
        $prefix = preg_replace('/\s+/', '', $prefix);

        // Truncate if too long
        if (strlen($prefix) > 20) {
            $prefix = substr($prefix, 0, 20);
        }

        return $prefix;
    }

    public static function getGroups(): array
    {
        return ['festivalRelated'];
    }
}
