<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\TicketData;
use App\Entity\TicketType;
use App\Entity\FestivalEdition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TicketTypeFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use AppGeneralConstants, TicketData;
    public function load(ObjectManager $manager): void
    {
        // Get all existing festival editions
        $festivalEditions = $manager->getRepository(FestivalEdition::class)->findAll();

        if (empty($festivalEditions)) {
            return; // Skip if no data available
        }

        foreach ($festivalEditions as $edition) {
            // Each edition gets random number of ticket types
            $numberOfTicketTypes = mt_rand(2, self::MAX_TICKET_TYPES_PER_EDITION);

            $selectedTypes = [];

            // Add essential types first
            foreach (self::ESSENTIAL_TYPES as $essentialType) {
                $selectedTypes[] = $essentialType;
            }

            // Add random additional types
            $availableTypes = array_column(self::TICKET_TYPES_DATA, 'name');
            $remainingTypes = array_diff($availableTypes, $selectedTypes); //make sure we do not have duplicated ticket types for an edition
            shuffle($remainingTypes);

            $additionalTypesNeeded = $numberOfTicketTypes - count($selectedTypes); //if we have already enough types just from standard ones
            for ($i = 0; $i < $additionalTypesNeeded && $i < count($remainingTypes); $i++) {
                $selectedTypes[] = $remainingTypes[$i]; //add some types if needed
            }

            // Create ticket types for this edition
            foreach ($selectedTypes as $typeName) {
                $ticketTypeData = $this->getTicketTypeData($typeName);

                if ($ticketTypeData == null) { //case when a ticket type has only defined name(never when data is hardcoded)
                    continue;
                }

                $ticketType = new TicketType();
                $ticketType->setEdition($edition);
                $ticketType->setName($ticketTypeData['name']);
                $ticketType->setBenefits($ticketTypeData['benefits']);

                // Generate price within the specified range
                $minPrice = $ticketTypeData['base_price'][0];
                $maxPrice = $ticketTypeData['base_price'][1];
                $price = mt_rand($minPrice * 100, $maxPrice * 100) / 100;

                // Apply edition-specific pricing modifiers
                $price = $this->applyPricingModifiers($price, $typeName);

                $ticketType->setPrice(number_format($price, 2, '.', ''));

                $manager->persist($ticketType);
            }
        }

        $manager->flush();
    }

    private function getTicketTypeData(string $typeName): ?array
    {
        foreach (self::TICKET_TYPES_DATA as $typeData) {
            if ($typeData['name'] === $typeName) {
                return $typeData;
            }
        }
        return null;
    }

    private function applyPricingModifiers(float $basePrice, string $ticketTypeName): float
    {
        // Apply random market fluctuations (±10%)
        $marketModifier = mt_rand(-10, 10) / 100;
        $adjustedPrice = $basePrice * (1 + $marketModifier);

        // Apply type-specific modifiers
        switch ($ticketTypeName) {
            case 'Early Bird Special':
                // Early bird gets additional 5-15% discount
                $earlyBirdDiscount = mt_rand(5, 15) / 100;
                $adjustedPrice *= (1 - $earlyBirdDiscount);
                break;

            case 'Student Discount':
            case 'Senior Citizen (65+)':
                // Additional discount for special demographics
                $specialDiscount = mt_rand(10, 20) / 100;
                $adjustedPrice *= (1 - $specialDiscount);
                break;

            case 'Platinum VIP':
            case 'Glamping Experience':
                // Premium experiences get slight premium
                $premiumModifier = mt_rand(5, 15) / 100;
                $adjustedPrice *= (1 + $premiumModifier);
                break;

            case 'Group Package (4+)':
                // Group packages get bulk discount
                $groupDiscount = mt_rand(15, 25) / 100;
                $adjustedPrice *= (1 - $groupDiscount);
                break;
        }

        // Ensure minimum price
        return max($adjustedPrice, 5.00);
    }

    public function getDependencies(): array
    {
        return [
            PurchaseAmenityFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
