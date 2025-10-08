<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\AppGeneralConstants;
use App\Entity\TicketType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\traits\hardcodedData\TicketData;

class TicketTypeFixtures extends Fixture implements FixtureGroupInterface
{
    use AppGeneralConstants, TicketData;

    public function load(ObjectManager $manager): void
    {
        $usedTypes = [];

        // Always load essential ticket types
        foreach (self::ESSENTIAL_TYPES as $typeName) {
            $data = $this->getTicketTypeDataByName($typeName);
            if ($data) {
                $ticketType = new TicketType();
                $ticketType->setName($data['name']);
                $ticketType->setBenefits($data['benefits']);
                $ticketType->setPrice($this->getRandomPrice($data['base_price']));
                $manager->persist($ticketType);
                $usedTypes[] = $data['name'];
            }
        }

        // Load other random other ticket types
        $otherTypes = array_filter(self::TICKET_TYPES_DATA, fn($data) => !in_array($data['name'], $usedTypes));
        shuffle($otherTypes);

        foreach (array_slice($otherTypes, 0, self::MAX_TICKET_TYPES - count(self::ESSENTIAL_TYPES)) as $data) {
            $ticketType = new TicketType();
            $ticketType->setName($data['name']);
            $ticketType->setBenefits($data['benefits']);
            $ticketType->setPrice($this->getRandomPrice($data['base_price']));
            $manager->persist($ticketType);
        }

        $manager->flush();
    }

    private function getTicketTypeDataByName(string $name): ?array
    {
        foreach (self::TICKET_TYPES_DATA as $type) {
            if ($type['name'] === $name) {
                return $type;
            }
        }
        return null;
    }

    private function getRandomPrice(array $range): float
    {
        return round(mt_rand((int) ($range[0] * 100), (int) ($range[1] * 100)) / 100, 2);
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
