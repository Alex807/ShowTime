<?php

namespace App\DataFixtures\purchaseRelated;

use App\DataFixtures\traits\hardcodedData\TicketData;
use App\Entity\TicketUsage;
use App\Entity\PurchasedTicket;
use App\Entity\UserAccount;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TicketUsageFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use TicketData;
    public function load(ObjectManager $manager): void
    {
        // Get all purchased tickets that have been used
        $purchasedTickets = $manager->getRepository(PurchasedTicket::class)->findAll();

        // Get staff members with appropriate roles
        $authorizedStaff = $this->getAuthorizedStaffMembers($manager);

        if (empty($purchasedTickets) || empty($authorizedStaff)) {
            return; // Skip if no data available
        }

        foreach ($purchasedTickets as $purchasedTicket) {
            $ticketsUsed = $purchasedTicket->getTicketsUsed();

            // Skip if no tickets were used
            if ($ticketsUsed <= 0) {
                continue;
            }

            // Create usage records for each used ticket
            for ($i = 0; $i < $ticketsUsed; $i++) {
                $this->createTicketUsage($manager, $purchasedTicket, $authorizedStaff, $i);
            }
        }

        $manager->flush();
    }

    private function getAuthorizedStaffMembers(ObjectManager $manager): array
    { //later you can define here what roles are allowed to scan tickets at the gates
        $users = $manager->getRepository(UserAccount::class)->findAll();

        return $users; //send just the user type objects
    }

    private function createTicketUsage(ObjectManager $manager, PurchasedTicket $purchasedTicket, array $authorizedStaff, int $usageIndex): void
    {
        $ticketType = $purchasedTicket->getTicketType();
        $ticketTypeName = $ticketType->getName();

        // Select appropriate entry gate based on ticket type
        $availableGates = self::GATE_ASSIGNMENTS[$ticketTypeName] ?? self::ENTRY_GATES;
        $entryGate = $availableGates[array_rand($availableGates)];

        // Generate realistic usage time
        $usedAt = $this->generateUsageTime($purchasedTicket, $usageIndex);

        // Select random authorized staff member
        shuffle($authorizedStaff); //for more diverse user_accounts picking
        $staffMember = $authorizedStaff[array_rand($authorizedStaff)];

        // Generate notes (most entries have no notes)
        $notes = self::USAGE_NOTES[array_rand(self::USAGE_NOTES)];

        $ticketUsage = new TicketUsage();
        $ticketUsage->setPurchasedTicket($purchasedTicket);
        $ticketUsage->setUsedAt($usedAt);
        $ticketUsage->setEntryGate($entryGate);
        $ticketUsage->setStaffMember($staffMember);

        if ($notes !== null) {
            $ticketUsage->setNotes($notes);
        }

        $manager->persist($ticketUsage);
    }

    private function generateUsageTime(PurchasedTicket $purchasedTicket, int $usageIndex): \DateTime
    {
        $validStarting = $purchasedTicket->getValidStarting();
        $expiresAt = $purchasedTicket->getExpiresAt();
        $ticketTypeName = $purchasedTicket->getTicketType()->getName();

        // Calculate valid time window
        $startTimestamp = $validStarting->getTimestamp();
        $endTimestamp = $expiresAt->getTimestamp();

        // Generate usage time based on ticket type and usage patterns
        $usageTime = new \DateTime();

        switch ($ticketTypeName) {
            case 'Single Day Pass':
            case 'General Admission':
            case 'Weekend Pass':
                // Most people arrive in afternoon/evening
                $usageTime = $this->generateMainEventUsageTime($validStarting, $expiresAt, $usageIndex);
                break;

            case 'VIP Experience':
            case 'Platinum VIP':
                // VIP guests often arrive earlier for exclusive access
                $usageTime = $this->generateVIPUsageTime($validStarting, $expiresAt, $usageIndex);
                break;

            case 'Late Night After-Party':
                // After-party tickets used late at night
                $usageTime = $this->generateLateNightUsageTime($validStarting, $expiresAt, $usageIndex);
                break;

            case 'Camping Add-On':
            case 'Glamping Experience':
                // Camping check-ins spread throughout the day
                $usageTime = $this->generateCampingUsageTime($validStarting, $expiresAt, $usageIndex);
                break;

            case 'Day Parking Pass':
            case 'Premium Parking':
                // Parking usage peaks before main events
                $usageTime = $this->generateParkingUsageTime($validStarting, $expiresAt, $usageIndex);
                break;

            case 'Press/Media Pass':
            case 'Artist/Industry Pass':
                // Professional passes used throughout the day
                $usageTime = $this->generateProfessionalUsageTime($validStarting, $expiresAt, $usageIndex);
                break;

            default:
                // Default random time within validity period
                $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
                $usageTime->setTimestamp($randomTimestamp);
        }

        return $usageTime;
    }

    private function generateMainEventUsageTime(\DateTime $validStarting, \DateTime $expiresAt, int $usageIndex): \DateTime
    {
        $usageTime = clone $validStarting;

        // Multiple entries spread throughout the day
        if ($usageIndex === 0) {
            // First entry: afternoon arrival (2 PM - 6 PM)
            $usageTime->modify('+' . mt_rand(6, 10) . ' hours');
        } else {
            // Re-entries: evening/night (6 PM - 11 PM)
            $usageTime->modify('+' . mt_rand(10, 15) . ' hours');
        }

        return $usageTime;
    }

    private function generateVIPUsageTime(\DateTime $validStarting, \DateTime $expiresAt, int $usageIndex): \DateTime
    {
        $usageTime = clone $validStarting;

        // VIP early access (12 PM - 4 PM for first entry)
        if ($usageIndex === 0) {
            $usageTime->modify('+' . mt_rand(4, 8) . ' hours');
        } else {
            $usageTime->modify('+' . mt_rand(8, 14) . ' hours');
        }

        return $usageTime;
    }

    private function generateLateNightUsageTime(\DateTime $validStarting, \DateTime $expiresAt, int $usageIndex): \DateTime
    {
        $usageTime = clone $validStarting;

        // Late night entries (10 PM - 2 AM)
        $usageTime->modify('+' . mt_rand(0, 4) . ' hours');

        return $usageTime;
    }

    private function generateCampingUsageTime(\DateTime $validStarting, \DateTime $expiresAt, int $usageIndex): \DateTime
    {
        $usageTime = clone $validStarting;

        // Camping check-ins throughout the day (8 AM - 8 PM)
        $usageTime->modify('+' . mt_rand(2, 14) . ' hours');

        return $usageTime;
    }

    private function generateParkingUsageTime(\DateTime $validStarting, \DateTime $expiresAt, int $usageIndex): \DateTime
    {
        $usageTime = clone $validStarting;

        // Parking usage peaks before events (10 AM - 4 PM)
        $usageTime->modify('+' . mt_rand(4, 10) . ' hours');

        return $usageTime;
    }

    private function generateProfessionalUsageTime(\DateTime $validStarting, \DateTime $expiresAt, int $usageIndex): \DateTime
    {
        $usageTime = clone $validStarting;

        // Professional access throughout the day (6 AM - 10 PM)
        $usageTime->modify('+' . mt_rand(0, 16) . ' hours');

        return $usageTime;
    }

    public function getDependencies(): array
    {
        return [
            PurchasedTicketFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['purchaseRelated'];
    }
}
