<?php
namespace App\DataFixtures\traits;

use Doctrine\Persistence\ObjectManager;

trait AppGeneralConstants
{
    private const TOTAL_EXISTING_USERS = 30;
    private const TOTAL_EXISTING_FESTIVALS = 15;
    private const MAX_FESTIVAL_EDITIONS = 5;
    private const MAX_ROLLS_PER_USER = 4;
    private const MAX_REVIEWS_PER_EDITION = 3;
    private const MAX_AMENITIES_PER_EDITION = 8;

    private const MIN_ARTISTS_PER_EDITION = 2;
    private const MAX_ARTISTS_PER_EDITION = 5;
    private const MIN_PERFORMANCE_DURATION = 45; // minutes
    private const MAX_PERFORMANCE_DURATION = 120; // minutes
    private const PERFORMANCE_BUFFER = 15; // minutes between performances

}
