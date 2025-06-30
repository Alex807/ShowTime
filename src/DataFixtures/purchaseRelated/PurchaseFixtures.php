<?php

namespace App\DataFixtures\purchaseRelated;

use App\Entity\Purchase;
use App\Entity\FestivalEdition;
use App\Entity\UserAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class PurchaseFixtures extends Fixture implements FixtureGroupInterface
{
    private const MIN_PURCHASES_PER_EDITION = 50;
    private const MAX_PURCHASES_PER_EDITION = 200;

    // Purchase status distribution (realistic percentages)
    private const PURCHASE_STATUSES = [
        'completed' => 75,      // 75% completed purchases
        'pending' => 8,         // 8% pending payments
        'cancelled' => 10,      // 10% cancelled by user
        'refunded' => 4,        // 4% refunded
        'failed' => 3           // 3% failed payments
    ];

    // Price ranges for different purchase types
    private const PRICE_RANGES = [
        'single_ticket' => [50.00, 150.00],
        'weekend_pass' => [120.00, 350.00],
        'vip_package' => [300.00, 800.00],
        'premium_experience' => [500.00, 1500.00],
        'group_discount' => [200.00, 600.00],
        'early_bird' => [40.00, 120.00],
        'last_minute' => [80.00, 200.00]
    ];

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
            $numberOfPurchases = mt_rand(self::MIN_PURCHASES_PER_EDITION, self::MAX_PURCHASES_PER_EDITION);

            // Generate festival date for purchase timing logic
            $festivalDate = new \DateTime();
            $festivalDate->modify('+' . mt_rand(30, 180) . ' days');

            for ($i = 0; $i < $numberOfPurchases; $i++) {
                // Select random user
                $user = $userAccounts[array_rand($userAccounts)];

                // Select random status based on weighted distribution
                $status = $weightedStatuses[array_rand($weightedStatuses)];

                // Generate purchase date based on status and festival timing
                $purchaseDate = $this->generatePurchaseDate($status, $festivalDate);

                // Generate total amount based on purchase type and timing
                $totalAmount = $this->generateTotalAmount($purchaseDate, $festivalDate);

                $purchase = new Purchase();
                $purchase->setEdition($edition);
                $purchase->setUser($user);
                $purchase->setStatus($status);
                $purchase->setTotalAmount(number_format($totalAmount, 2, '.', ''));
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
                $weightedArray[] = $status;
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

    private function generateTotalAmount(\DateTime $purchaseDate, \DateTime $festivalDate): float
    {
        // Calculate days until festival
        $daysUntilFestival = $purchaseDate->diff($festivalDate)->days;

        // Determine purchase type based on timing and random factors
        $purchaseType = $this->determinePurchaseType($daysUntilFestival);

        // Get price range for the purchase type
        $priceRange = self::PRICE_RANGES[$purchaseType];
        $minPrice = $priceRange[0];
        $maxPrice = $priceRange[1];

        // Generate random price within range
        $basePrice = mt_rand($minPrice * 100, $maxPrice * 100) / 100;

        // Apply timing-based modifiers
        if ($daysUntilFestival > 90) {
            // Early bird discount (10-20% off)
            $discount = mt_rand(10, 20) / 100;
            $basePrice *= (1 - $discount);
        } elseif ($daysUntilFestival < 7) {
            // Last minute premium (5-15% extra)
            $premium = mt_rand(5, 15) / 100;
            $basePrice *= (1 + $premium);
        }

        // Random additional fees/discounts (±5%)
        $modifier = mt_rand(-5, 5) / 100;
        $finalPrice = $basePrice * (1 + $modifier);

        // Ensure minimum price
        return max($finalPrice, 25.00);
    }

    private function determinePurchaseType(int $daysUntilFestival): string
    {
        // Early purchases (90+ days) - more likely to be early bird
        if ($daysUntilFestival > 90) {
            $types = ['early_bird', 'weekend_pass', 'single_ticket'];
            return $types[array_rand($types)];
        }

        // Last minute purchases (< 7 days) - more expensive options
        if ($daysUntilFestival < 7) {
            $types = ['last_minute', 'single_ticket', 'vip_package'];
            return $types[array_rand($types)];
        }

        // Regular timing - all options available with different probabilities
        $weightedTypes = [
            'single_ticket', 'single_ticket', 'single_ticket', // 30%
            'weekend_pass', 'weekend_pass', // 20%
            'vip_package', 'vip_package', // 20%
            'premium_experience', // 10%
            'group_discount', // 10%
            'early_bird' // 10%
        ];

        return $weightedTypes[array_rand($weightedTypes)];
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
