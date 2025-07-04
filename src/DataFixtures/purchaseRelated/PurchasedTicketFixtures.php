<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\hardcodedData\TicketData;
use App\Entity\PurchasedTicket;
use App\Entity\Purchase;
use App\Entity\TicketType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PurchasedTicketFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use TicketData;
    public function load(ObjectManager $manager): void
    {
        // Get all existing purchases and ticket types
        $purchases = $manager->getRepository(Purchase::class)->findAll();
        $ticketTypes = $manager->getRepository(TicketType::class)->findAll();

        if (empty($purchases) || empty($ticketTypes)) {
            return; // Skip if no data available
        }

        // Create ticket type lookup by edition
        $ticketTypeLookup = [];
        foreach ($ticketTypes as $ticketType) {
            $editionId = $ticketType->getEdition()->getId();
            $ticketTypeLookup[$editionId][] = $ticketType; ///hashmap key -> array of ticket_types
        }

        foreach ($purchases as $purchase) {

            // Skip if purchase is not completed (only completed purchases have active tickets)
            if ($purchase->getStatus() !== 'completed') {
                continue;
            }

            $editionId = $purchase->getEdition()->getId();

            // Check if this edition has available ticket types
            if (!isset($ticketTypeLookup[$editionId])) {
                continue;
            }

            $availableTicketTypes = $ticketTypeLookup[$editionId];

            // Most purchases have 1 ticket type, some have 2-3 (like main ticket + parking + food)
            $numberOfTicketTypes = $this->getNumberOfTicketTypes();

            // Randomly select ticket types for this purchase
            shuffle($availableTicketTypes);
            $selectedTicketTypes = array_slice($availableTicketTypes, 0, min($numberOfTicketTypes, count($availableTicketTypes)));

            foreach ($selectedTicketTypes as $ticketType) {
                $this->createPurchasedTicket($manager, $purchase, $ticketType);
            }
        }

        $manager->flush();
    }

    private function getNumberOfTicketTypes(): int
    {
        $rand = mt_rand(1, 100);

        if ($rand <= 70) {
            return 1; // 70% have 1 ticket type
        } elseif ($rand <= 90) {
            return 2; // 20% have 2 ticket types
        } else {
            return 3; // 10% have 3 ticket types
        }
    }

    private function createPurchasedTicket(ObjectManager $manager, Purchase $purchase, TicketType $ticketType): void
    {
        $ticketTypeName = $ticketType->getName();

        // Get quantity range for this ticket type
        $quantityRule = self::QUANTITY_RULES[$ticketTypeName] ?? ['min' => 1, 'max' => 2];
        $quantity = mt_rand($quantityRule['min'], $quantityRule['max']);

        // Calculate tickets used based on usage rate
        $usageRate = self::USAGE_RATES[$ticketTypeName] ?? ['min' => 70, 'max' => 90];
        $usagePercentage = mt_rand($usageRate['min'], $usageRate['max']) / 100;
        $ticketsUsed = max(0, intval($quantity * $usagePercentage));

        // Ensure tickets_used is never greater than quantity
        $ticketsUsed = min($ticketsUsed, $quantity);

        // Generate validity dates
        $validityDates = $this->generateValidityDates($purchase, $ticketTypeName);

        $purchasedTicket = new PurchasedTicket();
        $purchasedTicket->setPurchase($purchase);
        $purchasedTicket->setTicketType($ticketType);
        $purchasedTicket->setQuantity($quantity);
        $purchasedTicket->setTicketsUsed($ticketsUsed);
        $purchasedTicket->setValidStarting($validityDates['valid_starting']);
        $purchasedTicket->setExpiresAt($validityDates['expires_at']);

        $manager->persist($purchasedTicket);
    }

    private function generateValidityDates(Purchase $purchase, string $ticketTypeName): array
    {
        $purchaseDate = $purchase->getPurchaseDate();

        // Generate festival date (assuming it's in the future from purchase date)
        $festivalStartDate = clone $purchaseDate;
        $festivalStartDate->modify('+' . mt_rand(1, 120) . ' days');

        $validStarting = null;
        $expiresAt = null;

        switch ($ticketTypeName) {
            case 'Single Day Pass':
                // Valid for one specific day
                $validStarting = clone $festivalStartDate;
                $validStarting->modify('+' . mt_rand(0, 3) . ' days'); // Random day during festival
                $expiresAt = clone $validStarting;
                $expiresAt->modify('+1 day');
                break;

            case 'Weekend Pass':
            case 'General Admission':
            case 'VIP Experience':
            case 'Platinum VIP':
                // Valid for entire festival duration
                $validStarting = clone $festivalStartDate;
                $expiresAt = clone $festivalStartDate;
                $expiresAt->modify('+' . mt_rand(2, 5) . ' days'); // Festival duration
                break;

            case 'Early Bird Special':
                // Valid for entire festival, but purchased early
                $validStarting = clone $festivalStartDate;
                $expiresAt = clone $festivalStartDate;
                $expiresAt->modify('+' . mt_rand(2, 4) . ' days');
                break;

            case 'Late Night After-Party':
                // Valid for specific late night events
                $validStarting = clone $festivalStartDate;
                $validStarting->modify('+' . mt_rand(0, 3) . ' days');
                $validStarting->setTime(22, 0); // Starts at 10 PM
                $expiresAt = clone $validStarting;
                $expiresAt->modify('+6 hours'); // Until 4 AM
                break;

            case 'Day Parking Pass':
                // Valid for one day
                $validStarting = clone $festivalStartDate;
                $validStarting->modify('+' . mt_rand(0, 3) . ' days');
                $validStarting->setTime(6, 0); // Parking opens early
                $expiresAt = clone $validStarting;
                $expiresAt->setTime(23, 59); // Until end of day
                break;

            case 'Camping Add-On':
            case 'Glamping Experience':
                // Valid from day before festival to day after
                $validStarting = clone $festivalStartDate;
                $validStarting->modify('-1 day');
                $expiresAt = clone $festivalStartDate;
                $expiresAt->modify('+' . mt_rand(3, 6) . ' days');
                break;

            case 'Press/Media Pass':
            case 'Artist/Industry Pass':
                // Valid for extended period including setup days
                $validStarting = clone $festivalStartDate;
                $validStarting->modify('-2 days'); // Access to set up days
                $expiresAt = clone $festivalStartDate;
                $expiresAt->modify('+' . mt_rand(4, 7) . ' days');
                break;

            default:
                // Default validity period
                $validStarting = clone $festivalStartDate;
                $expiresAt = clone $festivalStartDate;
                $expiresAt->modify('+' . mt_rand(1, 4) . ' days');
        }

        return [
            'valid_starting' => $validStarting,
            'expires_at' => $expiresAt
        ];
    }

    public function getDependencies(): array
    {
        return [
            TicketTypeFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
