<?php

namespace App\DataFixtures\festivalRelated;

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
    private const POSITIVE_COMMENTS = [
        "Amazing festival! The lineup was incredible and the atmosphere was electric.",
        "Best festival experience ever! Great organization and fantastic artists.",
        "Absolutely loved every moment. The sound quality was perfect and the crowd was amazing.",
        "Outstanding festival with top-notch production values. Will definitely come back next year!",
        "Incredible lineup, great food vendors, and perfect weather. Couldn't ask for more!",
        "The best weekend of my life! Every artist delivered an amazing performance.",
        "Fantastic organization, clean facilities, and an unforgettable experience.",
        "Perfect festival atmosphere with great vibes and amazing music throughout.",
        "Exceeded all expectations! The stage production was mind-blowing.",
        "Wonderful experience from start to finish. Great job by the organizers!",
        "Epic festival with incredible energy and world-class performances.",
        "Amazing venue, great sound system, and fantastic crowd. Highly recommend!",
        "One of the best festivals I've ever attended. Everything was perfectly organized.",
        "Incredible experience! The artists were phenomenal and the production was flawless.",
        "Outstanding festival with great diversity in music and amazing atmosphere."
    ];

    private const NEUTRAL_COMMENTS = [
        "Good festival overall, but the food was quite expensive.",
        "Nice experience, though the sound could have been better in some areas.",
        "Decent festival with good artists, but the organization could be improved.",
        "Had a good time, but the queues were quite long for everything.",
        "Enjoyable festival, though some technical issues affected a few performances.",
        "Good lineup and atmosphere, but the venue was a bit crowded.",
        "Nice festival experience, but parking was a nightmare.",
        "Solid festival with good music, but the weather didn't cooperate.",
        "Good artists and decent organization, but overpriced drinks.",
        "Enjoyable weekend, though some stages had sound issues.",
        "Nice festival atmosphere, but could use better crowd management.",
        "Good experience overall, but the camping facilities need improvement.",
        "Decent festival with good vibes, but transportation was challenging.",
        "Had fun, but expected more from some of the headlining acts.",
        "Good festival concept, but execution could be better in some areas."
    ];

    private const NEGATIVE_COMMENTS = [
        "Disappointing experience. Poor organization and overpriced everything.",
        "Not worth the money. Sound issues throughout and terrible crowd management.",
        "Very disorganized festival with long queues and poor facilities.",
        "Expected much more for the ticket price. Many technical problems.",
        "Poor sound quality and overcrowded. Would not recommend.",
        "Terrible organization, dirty facilities, and rude staff.",
        "Overpriced and underwhelming. Many artists didn't show up.",
        "Worst festival experience ever. Complete chaos and no proper planning.",
        "Very disappointing. The venue was unsuitable and poorly managed.",
        "Not impressed at all. Poor value for money and bad organization.",
        "Chaotic event with terrible logistics and unfriendly staff.",
        "Completely disorganized with major sound and lighting issues.",
        "Overpriced disaster with poor facilities and bad crowd control.",
        "Terrible experience from start to finish. Avoid at all costs.",
        "Poorly managed event with numerous problems and no solutions."
    ];

    use AppGeneralConstants; //for using constants
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
                // More likely to get higher ratings (4-5 stars are more common)
                $ratingDistribution = [1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5];
                $rating = $ratingDistribution[array_rand($ratingDistribution)];
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
