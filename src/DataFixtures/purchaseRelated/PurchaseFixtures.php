<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\PurchaseData;
use App\Entity\Purchase;
use App\Entity\FestivalEdition;
use App\Entity\UserAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PurchaseFixtures extends Fixture implements FixtureGroupInterface
{

    use AppGeneralConstants, PurchaseData;
    public function load(ObjectManager $manager): void
    {
        // Get all existing festival editions and user accounts
        $festivalEditions = $manager->getRepository(FestivalEdition::class)->findAll();
        $userAccounts = $manager->getRepository(UserAccount::class)->findAll();

        if (empty($festivalEditions) || empty($userAccounts)) {
            return; // Skip if no data available
        }

        // Create weighted status array for realistic distribution
        $weightedStatuses = $this->createWeightedStatusArray();

        foreach ($festivalEditions as $edition) {
            // Each edition gets random number of purchases
            $numberOfPurchases = mt_rand(3, self::MAX_PURCHASES_PER_EDITION);

            // Generate festival date for purchase timing logic
            $festivalDate = $edition->getStartDate();

            for ($i = 0; $i < $numberOfPurchases; $i++) {
                // Select random user (a user can have multiple purchases, not checking if already was picked)
                $user = $userAccounts[array_rand($userAccounts)];

                // Select random status based on weighted distribution
                $status = $weightedStatuses[array_rand($weightedStatuses)];

                // Generate purchase date based on status and festival timing
                $purchaseDate = $this->generatePurchaseDate($status, $festivalDate);

                // Generate total amount based on purchase type and timing
                $totalAmount = 0; //we call another fixture to update this fields based on related table(purchase_ticket and purchase_amenity)

                $purchase = new Purchase();
                $purchase->setEdition($edition);
                $purchase->setUser($user);
                $purchase->setStatus($status);
                $purchase->setTotalAmount($totalAmount);
                $purchase->setPurchaseDate($purchaseDate);

                $manager->persist($purchase);
            }
        }

        $manager->flush();
    }

    private function createWeightedStatusArray(): array
    {
        $weightedArray = [];

        foreach (self::PURCHASE_STATUSES as $status => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $weightedArray[] = $status; //we create an array with 100 elements total to simulate status distribution
            }
        }

        return $weightedArray;
    }

    private function generatePurchaseDate(string $status, \DateTime $festivalDate): \DateTime
    {
        $purchaseDate = new \DateTime();

        switch ($status) {
            case 'completed':
                // Completed purchases: 1-120 days before festival
                $daysBeforeFestival = mt_rand(1, 120);
                $purchaseDate = clone $festivalDate;
                $purchaseDate->modify('-' . $daysBeforeFestival . ' days');
                break;

            case 'pending':
                // Pending purchases: recent (1-7 days ago)
                $daysAgo = mt_rand(1, 7);
                $purchaseDate->modify('-' . $daysAgo . ' days');
                break;

            case 'cancelled':
                // Cancelled purchases: 2-90 days before festival
                $daysBeforeFestival = mt_rand(2, 90);
                $purchaseDate = clone $festivalDate;
                $purchaseDate->modify('-' . $daysBeforeFestival . ' days');
                break;

            case 'refunded':
                // Refunded purchases: 5-60 days before festival
                $daysBeforeFestival = mt_rand(5, 60);
                $purchaseDate = clone $festivalDate;
                $purchaseDate->modify('-' . $daysBeforeFestival . ' days');
                break;

            case 'failed':
                // Failed purchases: recent attempts (1-14 days ago)
                $daysAgo = mt_rand(1, 14);
                $purchaseDate->modify('-' . $daysAgo . ' days');
                break;

            default:
                // Default: random date in the past 60 days
                $daysAgo = mt_rand(1, 60);
                $purchaseDate->modify('-' . $daysAgo . ' days');
        }

        // Add random time of day
        $hour = mt_rand(8, 23); // Purchase hours 8 AM to 11 PM
        $minute = mt_rand(0, 59);
        $second = mt_rand(0, 59);
        $purchaseDate->setTime($hour, $minute, $second);

        return $purchaseDate;
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
