<?php
namespace App\DataFixtures\traits;

use Doctrine\Persistence\ObjectManager;

trait AppGeneralConstants
{
    private const TOTAL_EXISTING_USERS = 20;
    private const TOTAL_EXISTING_FESTIVALS = 10;
    private const MAX_FESTIVAL_EDITIONS = 5;
    private const MAX_ROLLS_PER_USER = 4;
    private const MAX_REVIEWS_PER_EDITION = 3;
    private const MAX_AMENITIES_PER_EDITION = 4;

    private const MIN_ARTISTS_PER_EDITION = 2;
    private const MAX_ARTISTS_PER_EDITION = 5;
    private const MIN_PERFORMANCE_DURATION = 50; // minutes
    private const MAX_PERFORMANCE_DURATION = 120; // minutes
    private const PERFORMANCE_BUFFER = 15; // minutes between performances

    private const MAX_PURCHASES_PER_EDITION = 10;

    private const MAX_TICKET_TYPES_PER_EDITION = 7;

    // Percentage of purchases that include amenities
    private const AMENITY_PURCHASE_RATE = 25; // 35% of purchases include amenities

    // Average number of different amenities per purchase
    private const MAX_AMENITIES_PER_PURCHASE = 4;


}
