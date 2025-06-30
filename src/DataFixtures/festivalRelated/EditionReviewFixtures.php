<?php

namespace App\DataFixtures\festivalRelated;

use App\DataFixtures\traits\hardcodedData\EditionReviewData;
use App\Entity\EditionReview;
use App\Entity\FestivalEdition;
use App\Entity\UserAccount;
use App\DataFixtures\traits\AppGeneralConstants;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EditionReviewFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{

    use AppGeneralConstants, EditionReviewData; //for using constants
    public function load(ObjectManager $manager): void
    {
        // Get all existing festival editions and users
        $festivalEditions = $manager->getRepository(FestivalEdition::class)->findAll();
        $userAccounts = $manager->getRepository(UserAccount::class)->findAll();

        if (empty($festivalEditions) || empty($userAccounts)) {
            return; // Skip if no data available
        }

        foreach ($festivalEditions as $edition) {
            if ($edition->getStartDate() > new \DateTime()) {
                continue; // skip future editions(People don’t review an event before it happens)
            }

            // Each edition gets random number of reviews
            $numberOfReviews = mt_rand(0, self::MAX_REVIEWS_PER_EDITION);
            $usedUsers = []; // Track users who already reviewed this edition

            for ($i = 0; $i < $numberOfReviews; $i++) {
                // Get a random user who hasn't reviewed this edition yet
                $attempts = 0;
                do {
                    $randomUser = $userAccounts[array_rand($userAccounts)];
                    $attempts++;
                    // Prevent infinite loop if we run out of unique users
                    if ($attempts > 50) {
                        break;
                    }
                } while (in_array($randomUser, $usedUsers));

                // Skip if we couldn't find a unique user
                if (in_array($randomUser, $usedUsers)) {
                    continue;
                }

                $usedUsers[] = $randomUser;

                $review = new EditionReview();
                $review->setEdition($edition);
                $review->setUser($randomUser);

                // Generate rating (1-5 stars) with realistic distribution
                $rating = self::RATING_DISTRIBUTION[array_rand(self::RATING_DISTRIBUTION)];
                $review->setRatingStars($rating);

                // Select comment based on rating
                $comment = $this->getCommentByRating($rating);
                $review->setComment($comment);

                $now = new \DateTime();
                $startDate = $edition->getStartDate();

                // Calculate the maximum interval (in days) between start date and now
                $interval = $now->diff($startDate)->days;

                // Ensure the start date is not in the future
                if ($startDate > $now) {
                    $postedAt = $now; // fallback to now if start date is in future
                } else {
                    // Random number of days, hours, and minutes to add after the start date
                    $maxDays = min(365, $interval); // limit range to last year
                    $randomDays = mt_rand(0, $maxDays);
                    $randomHours = mt_rand(0, 23);
                    $randomMinutes = mt_rand(0, 59);

                    $postedAt = clone $startDate;
                    $postedAt->modify("+{$randomDays} days");
                    $postedAt->modify("+{$randomHours} hours");
                    $postedAt->modify("+{$randomMinutes} minutes");

                    // Ensure it doesn't go into the future(cases with some extra minutes)
                    if ($postedAt > $now) {
                        $postedAt = $now;
                    }
                }

                $review->setPostedAt($postedAt);

                $manager->persist($review);
            }
        }

        $manager->flush();
    }

    private function getCommentByRating(int $rating): string
    {
        return match($rating) {
            5, 4 => self::POSITIVE_COMMENTS[array_rand(self::POSITIVE_COMMENTS)],
            2, 1 => self::NEGATIVE_COMMENTS[array_rand(self::NEGATIVE_COMMENTS)],
            default => self::NEUTRAL_COMMENTS[array_rand(self::NEUTRAL_COMMENTS)]  //case for 3 stars rating
        };
    }

    public function getDependencies(): array
    {
        return [
            FestivalEditionFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['festivalRelated'];
    }
}
