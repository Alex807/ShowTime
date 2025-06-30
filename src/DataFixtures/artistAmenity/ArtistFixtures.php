<?php

namespace App\DataFixtures\artistAmenity;

use App\DataFixtures\traits\hardcodedData\ArtistData;
use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    use ArtistData;
    public function load(ObjectManager $manager): void
    {
        $numberOfArtists = count(self::ARTISTS_DATA);

        for ($i = 0; $i < $numberOfArtists; $i++) {
            $artistData = self::ARTISTS_DATA[$i];

            $artist = new Artist();
            $artist->setRealName($artistData['real_name']);
            $artist->setStageName($artistData['stage_name']);
            $artist->setMusicGenre($artistData['genre']);
            $artist->setInstagramAccount($artistData['instagram']);

            // Generate placeholder image URL
            $imageStyle = self::IMAGE_STYLES[array_rand(self::IMAGE_STYLES)];
            $imageSize = mt_rand(300, 1080);
            $artist->setImageUrl("https://placehold.com/{$artist->getRealName()}/{$imageSize}x{$imageSize}/{$imageStyle}");

            // 85% chance of having a manager email
            if (mt_rand(1, 100) <= 85) {
                $artist->setManagerEmail($artistData['manager_email']);
            }

            $manager->persist($artist);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EditionAmenityFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['artistAmenity'];
    }
}
