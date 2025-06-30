<?php

namespace App\DataFixtures\purchaseRelated;

use App\Entity\Purchase;
use App\Entity\PurchasedTicket;
use App\Entity\PurchaseAmenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UpdatePurchaseTotalAmountFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $purchases = $manager->getRepository(Purchase::class)->findAll();

        foreach ($purchases as $purchase) {
            $totalAmount = 0;

            // Sum ticket prices for this purchase
            $tickets = $manager->getRepository(PurchasedTicket::class)->findBy([
                'purchase' => $purchase,
            ]);

            foreach ($tickets as $ticket) {
                $totalAmount += $ticket->getPrice();
            }

            // Sum amenity prices for this purchase
            $amenities = $manager->getRepository(PurchaseAmenity::class)->findBy([
                'purchase' => $purchase,
            ]);

            foreach ($amenities as $amenity) {
                $totalAmount += $amenity->getPrice();
            }

            // Update the total amount on the purchase
            $purchase->setTotalAmount($totalAmount);
            $manager->persist($purchase);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TicketUsageFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
