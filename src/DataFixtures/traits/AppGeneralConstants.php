<?php
namespace App\DataFixtures\traits;

use Doctrine\Persistence\ObjectManager;

trait AppGeneralConstants
{
    private const TOTAL_EXISTING_USERS = 20;
    private const MAX_ROLLS_PER_USER = 4;

    private const TOTAL_EXISTING_FESTIVALS = 25;
    private const MAX_FESTIVAL_EDITIONS = 3;
    private const MAX_REVIEWS_PER_EDITION = 3;


    private const MAX_AMENITIES_PER_EDITION = 4;
    private const MAX_ARTISTS_PER_EDITION = 5;
    private const MAX_PERFORMANCE_MINUTES = 120;
    private const PERFORMANCE_MINUTES_BUFFER = 15; // minutes between performances

    private const MAX_PURCHASES_PER_EDITION = 10;
    private const MAX_TICKET_TYPES_PER_EDITION = 7;
    private const AMENITY_PURCHASE_RATE = 25; //  purchases that include amenities
    private const MAX_AMENITIES_PER_PURCHASE = 4;


}
