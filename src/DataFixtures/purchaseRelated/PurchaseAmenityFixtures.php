<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\DataFixtures\traits\hardcodedData\PurchaseData;
use App\Entity\PurchaseAmenity;
use App\Entity\Purchase;
use App\Entity\Amenity;
use App\Entity\EditionAmenity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PurchaseAmenityFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use AppGeneralConstants, PurchaseData;
    public function load(ObjectManager $manager): void
    {
        // Get all existing purchases
        $purchases = $manager->getRepository(Purchase::class)->findAll();
        $amenities = $manager->getRepository(Amenity::class)->findAll();
        $editionAmenities = $manager->getRepository(EditionAmenity::class)->findAll();

        if (empty($purchases) || empty($amenities) || empty($editionAmenities)) {
            return; // Skip if no data available
        }

        // Create amenity lookup for easier access (hashmap)
        $amenityLookup = [];
        foreach ($amenities as $amenity) {
            $amenityLookup[$amenity->getName()] = $amenity;
        }

        // Create edition amenity lookup by edition and amenity (uses a compose key)
        $editionAmenityLookup = [];
        foreach ($editionAmenities as $editionAmenity) {
            $editionId = $editionAmenity->getEdition()->getId();
            $amenityId = $editionAmenity->getAmenity()->getId();
            $editionAmenityLookup[$editionId][$amenityId] = $editionAmenity;
        }

        foreach ($purchases as $purchase) {
            // Only some purchases include amenities
            if (mt_rand(1, 100) > self::AMENITY_PURCHASE_RATE) {
                continue;
            }

            $editionId = $purchase->getEdition()->getId();

            // Check if this edition has available amenities
            if (!isset($editionAmenityLookup[$editionId])) { //id misses from our lookup means that current edition has no amenities
                continue;
            }

            $availableEditionAmenities = $editionAmenityLookup[$editionId];

            // Determine if this will be a bundle purchase or individual amenities
            $isBundlePurchase = mt_rand(1, 100) <= 25; // 25% chance of bundle purchase

            if ($isBundlePurchase) {
                $this->createBundlePurchase($manager, $purchase, $availableEditionAmenities, $amenityLookup);
            } else {
                $this->createIndividualPurchase($manager, $purchase, $availableEditionAmenities);
            }
        }

        $manager->flush();
    }

    private function createBundlePurchase(ObjectManager $manager, Purchase $purchase, array $availableEditionAmenities, array $amenityLookup): void
    {
        // Select random bundle
        $bundleNames = array_keys(self::AMENITY_BUNDLES);
        $selectedBundle = self::AMENITY_BUNDLES[$bundleNames[array_rand($bundleNames)]];

        foreach ($selectedBundle as $amenityName) {
            if (!isset($amenityLookup[$amenityName])) { //amenity misses from our hardcoded amenity table
                continue;
            }

            $amenity = $amenityLookup[$amenityName]; //amenityLookup contains all amenities defined in amenity table, not per an edition
            $amenityId = $amenity->getId();

            // Check if this amenity is available for this edition
            if (!isset($availableEditionAmenities[$amenityId])) {
                continue;
            }

            $editionAmenity = $availableEditionAmenities[$amenityId];
            $quantity = $this->getQuantityForAmenity($amenityName);

            $purchaseAmenity = new PurchaseAmenity();
            $purchaseAmenity->setPurchase($purchase);
            $purchaseAmenity->setAmenity($amenity);
            $purchaseAmenity->setEditionAmenity($editionAmenity);
            $purchaseAmenity->setQuantity($quantity);

            $manager->persist($purchaseAmenity);
        }
    }

    private function createIndividualPurchase(ObjectManager $manager, Purchase $purchase, array $availableEditionAmenities): void
    {
        // Random number of different amenities for this purchase
        $numberOfAmenities = mt_rand(2, self::MAX_AMENITIES_PER_PURCHASE);

        // Get random amenities from available ones
        $availableAmenityIds = array_keys($availableEditionAmenities);
        shuffle($availableAmenityIds);

        $selectedCount = 0;
        foreach ($availableAmenityIds as $amenityId) {
            if ($selectedCount >= $numberOfAmenities) {
                break;
            }

            $editionAmenity = $availableEditionAmenities[$amenityId];
            $amenity = $editionAmenity->getAmenity();
            $amenityName = $amenity->getName();

            $quantity = $this->getQuantityForAmenity($amenityName); //returns a random between hardcoded range

            $purchaseAmenity = new PurchaseAmenity();
            $purchaseAmenity->setPurchase($purchase);
            $purchaseAmenity->setAmenity($amenity);
            $purchaseAmenity->setEditionAmenity($editionAmenity);
            $purchaseAmenity->setQuantity($quantity);

            $manager->persist($purchaseAmenity);

            $selectedCount++;
        }
    }

    private function getQuantityForAmenity(string $amenityName): int
    {
        // Find which category this amenity belongs to
        foreach (self::QUANTITY_RULES as $category => $rules) {
            if (in_array($amenityName, $rules['names'])) {
                return mt_rand($rules['min_qty'], $rules['max_qty']);
            }
        }

        // Default quantity if amenity not found in rules
        return mt_rand(1, 2);
    }

    public function getDependencies(): array
    {
        return [
            PurchaseFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
