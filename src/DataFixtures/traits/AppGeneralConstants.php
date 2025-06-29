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
}
