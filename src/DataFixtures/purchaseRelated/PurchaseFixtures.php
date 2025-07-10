<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\Entity\Purchase;
use App\Entity\FestivalEdition;
use App\Entity\TicketType;
use App\Entity\UserAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PurchaseFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    use AppGeneralConstants;
    public function load(ObjectManager $manager): void
    {
        // Get all existing festival editions and user accounts
        $festivalEditions = $manager->getRepository(FestivalEdition::class)->findAll();
        $userAccounts = $manager->getRepository(UserAccount::class)->findAll();

        if (empty($festivalEditions) || empty($userAccounts)) {
            return; // Skip if no data available
        }

        foreach ($festivalEditions as $edition) {
            // Each edition gets random number of purchases
            $numberOfPurchases = mt_rand(2, self::MAX_PURCHASES_PER_EDITION);

            // Generate festival date for purchase timing logic
            $festivalDate = $edition->getStartDate();

            // Fetch all persisted ticket types from DB before looping
            $ticketTypes = $manager->getRepository(TicketType::class)->findAll();

            for ($i = 0; $i < $numberOfPurchases; $i++) {
                // Select random user (a user can have multiple purchases, not checking if already was picked)
                $user = $userAccounts[array_rand($userAccounts)];

                // Generate purchase date based on status and festival timing
                $purchaseDate = $this->generatePurchaseDate(mt_rand(1, 6), $festivalDate);

                $purchase = new Purchase();
                $purchase->setEdition($edition);
                $purchase->setUser($user);
                $purchase->setPurchaseDate($purchaseDate);

                $randomTicketType = $ticketTypes[array_rand($ticketTypes)];
                $purchase->setTicketType($randomTicketType);
                $purchase->setQuantity(mt_rand(1, 7));

                $manager->persist($purchase);
            }
        }

        $manager->flush();
    }

    private function generatePurchaseDate(int $option, \DateTime $festivalDate): \DateTime
    {
        $purchaseDate = new \DateTime();

        switch ($option) {
            case 1:
                // Completed purchases: 1-120 days before festival
                $daysBeforeFestival = mt_rand(1, 120);
                $purchaseDate = clone $festivalDate;
                $purchaseDate->modify('-' . $daysBeforeFestival . ' days');
                break;

            case 2:
                // Pending purchases: recent (1-7 days ago)
                $daysAgo = mt_rand(1, 7);
                $purchaseDate->modify('-' . $daysAgo . ' days');
                break;

            case 3:
                // Cancelled purchases: 2-90 days before festival
                $daysBeforeFestival = mt_rand(2, 90);
                $purchaseDate = clone $festivalDate;
                $purchaseDate->modify('-' . $daysBeforeFestival . ' days');
                break;

            case 4:
                // Refunded purchases: 5-60 days before festival
                $daysBeforeFestival = mt_rand(5, 60);
                $purchaseDate = clone $festivalDate;
                $purchaseDate->modify('-' . $daysBeforeFestival . ' days');
                break;

            case 5:
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
