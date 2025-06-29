<?php
namespace App\DataFixtures\traits;

use Doctrine\Persistence\ObjectManager;

trait AppGeneralConstants
{
    private const TOTAL_EXISTING_USERS = 50;
    private const TOTAL_EXISTING_FESTIVALS = 30;
    private const MAX_FESTIVAL_EDITIONS = 7;
    private const MAX_ROLLS_PER_USER = 4;
}
