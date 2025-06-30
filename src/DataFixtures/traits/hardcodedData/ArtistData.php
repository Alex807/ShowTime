<?php
namespace App\DataFixtures\traits\hardcodedData;

trait ArtistData
{
    private const ARTISTS_DATA = [
        // Romanian Artists
        [
            'real_name' => 'Andrei Rata',
            'stage_name' => 'Andrew Rayel',
            'genre' => 'Trance',
            'instagram' => '@andrewrayel',
            'manager_email' => 'management@andrewrayel.com'
        ],
        [
            'real_name' => 'Eduard Ilie',
            'stage_name' => 'Edward Maya',
            'genre' => 'Electronic Dance',
            'instagram' => '@edwardmayaofficial',
            'manager_email' => 'booking@edwardmaya.ro'
        ],
        [
            'real_name' => 'Radu Sîrbu',
            'stage_name' => 'Radu Sîrbu',
            'genre' => 'Pop',
            'instagram' => '@radusirbuofficial',
            'manager_email' => 'contact@radusirbu.com'
        ],
        [
            'real_name' => 'Inna Danilov',
            'stage_name' => 'INNA',
            'genre' => 'Dance Pop',
            'instagram' => '@inna',
            'manager_email' => 'management@inna.ro'
        ],
        [
            'real_name' => 'Alexandra Stan',
            'stage_name' => 'Alexandra Stan',
            'genre' => 'Dance Pop',
            'instagram' => '@alexandrastanromania',
            'manager_email' => 'booking@alexandrastan.ro'
        ],
        [
            'real_name' => 'Antonia Iacobescu',
            'stage_name' => 'Antonia',
            'genre' => 'Pop',
            'instagram' => '@antonia',
            'manager_email' => 'management@antonia.ro'
        ],
        [
            'real_name' => 'Ștefan Bănică Jr.',
            'stage_name' => 'Ștefan Bănică Jr.',
            'genre' => 'Pop Rock',
            'instagram' => '@stefanbanicajr',
            'manager_email' => 'contact@stefanbanica.ro'
        ],
        [
            'real_name' => 'Loredana Groza',
            'stage_name' => 'Loredana',
            'genre' => 'Pop',
            'instagram' => '@loredanaofficial',
            'manager_email' => 'booking@loredana.ro'
        ],
        [
            'real_name' => 'Andrei Tiberiu Maria',
            'stage_name' => 'Smiley',
            'genre' => 'Pop',
            'instagram' => '@smiley_omul',
            'manager_email' => 'management@smiley.ro'
        ],
        [
            'real_name' => 'Delia Matache',
            'stage_name' => 'Delia',
            'genre' => 'Pop',
            'instagram' => '@delia',
            'manager_email' => 'contact@delia.ro'
        ],

        // International Electronic/EDM Artists
        [
            'real_name' => 'Martijn Gerard Garritsen',
            'stage_name' => 'Martin Garrix',
            'genre' => 'Progressive House',
            'instagram' => '@martingarrix',
            'manager_email' => 'booking@martingarrix.com'
        ],
        [
            'real_name' => 'Adam Richard Wiles',
            'stage_name' => 'Calvin Harris',
            'genre' => 'Electronic Dance',
            'instagram' => '@calvinharris',
            'manager_email' => 'management@calvinharris.com'
        ],
        [
            'real_name' => 'Sonny John Moore',
            'stage_name' => 'Skrillex',
            'genre' => 'Dubstep',
            'instagram' => '@skrillex',
            'manager_email' => 'booking@skrillex.com'
        ],
        [
            'real_name' => 'Thomas Wesley Pentz',
            'stage_name' => 'Diplo',
            'genre' => 'Electronic',
            'instagram' => '@diplo',
            'manager_email' => 'management@diplo.com'
        ],
        [
            'real_name' => 'Armin Jozef Jacobus Daniël van Buuren',
            'stage_name' => 'Armin van Buuren',
            'genre' => 'Trance',
            'instagram' => '@arminvanbuuren',
            'manager_email' => 'booking@arminvanbuuren.com'
        ],

        // Hip-Hop/Rap Artists
        [
            'real_name' => 'Aubrey Drake Graham',
            'stage_name' => 'Drake',
            'genre' => 'Hip Hop',
            'instagram' => '@champagnepapi',
            'manager_email' => 'management@drake.com'
        ],
        [
            'real_name' => 'Kendrick Lamar Duckworth',
            'stage_name' => 'Kendrick Lamar',
            'genre' => 'Hip Hop',
            'instagram' => '@kendricklamar',
            'manager_email' => 'booking@kendricklamar.com'
        ],
        [
            'real_name' => 'Jacques Bermon Webster II',
            'stage_name' => 'Travis Scott',
            'genre' => 'Hip Hop',
            'instagram' => '@travisscott',
            'manager_email' => 'management@travisscott.com'
        ],
        [
            'real_name' => 'Belcalis Marlenis Almánzar',
            'stage_name' => 'Cardi B',
            'genre' => 'Hip Hop',
            'instagram' => '@iamcardib',
            'manager_email' => 'booking@cardib.com'
        ],

        // Pop Artists
        [
            'real_name' => 'Taylor Alison Swift',
            'stage_name' => 'Taylor Swift',
            'genre' => 'Pop',
            'instagram' => '@taylorswift',
            'manager_email' => 'management@taylorswift.com'
        ],
        [
            'real_name' => 'Ariana Grande-Butera',
            'stage_name' => 'Ariana Grande',
            'genre' => 'Pop',
            'instagram' => '@arianagrande',
            'manager_email' => 'booking@arianagrande.com'
        ],
        [
            'real_name' => 'Justin Drew Bieber',
            'stage_name' => 'Justin Bieber',
            'genre' => 'Pop',
            'instagram' => '@justinbieber',
            'manager_email' => 'management@justinbieber.com'
        ],
        [
            'real_name' => 'Billie Eilish Pirate Baird O\'Connell',
            'stage_name' => 'Billie Eilish',
            'genre' => 'Alternative Pop',
            'instagram' => '@billieeilish',
            'manager_email' => 'booking@billieeilish.com'
        ],

        // Rock/Alternative Artists
        [
            'real_name' => 'Dan Reynolds',
            'stage_name' => 'Imagine Dragons',
            'genre' => 'Alternative Rock',
            'instagram' => '@imaginedragons',
            'manager_email' => 'management@imaginedragons.com'
        ],
        [
            'real_name' => 'Chris Martin',
            'stage_name' => 'Coldplay',
            'genre' => 'Alternative Rock',
            'instagram' => '@coldplay',
            'manager_email' => 'booking@coldplay.com'
        ],
        [
            'real_name' => 'Brandon Flowers',
            'stage_name' => 'The Killers',
            'genre' => 'Indie Rock',
            'instagram' => '@thekillers',
            'manager_email' => 'management@thekillers.com'
        ],

        // Latin Artists
        [
            'real_name' => 'Benito Antonio Martínez Ocasio',
            'stage_name' => 'Bad Bunny',
            'genre' => 'Reggaeton',
            'instagram' => '@badbunnypr',
            'manager_email' => 'booking@badbunny.com'
        ],
        [
            'real_name' => 'J Balvin',
            'stage_name' => 'J Balvin',
            'genre' => 'Reggaeton',
            'instagram' => '@jbalvin',
            'manager_email' => 'management@jbalvin.com'
        ],

        // R&B/Soul Artists
        [
            'real_name' => 'Abel Makkonen Tesfaye',
            'stage_name' => 'The Weeknd',
            'genre' => 'R&B',
            'instagram' => '@theweeknd',
            'manager_email' => 'booking@theweeknd.com'
        ],
        [
            'real_name' => 'Solána Imani Rowe',
            'stage_name' => 'SZA',
            'genre' => 'R&B',
            'instagram' => '@sza',
            'manager_email' => 'management@sza.com'
        ],

        // Indie/Alternative Artists
        [
            'real_name' => 'Phoebe Bridgers',
            'stage_name' => 'Phoebe Bridgers',
            'genre' => 'Indie Folk',
            'instagram' => '@phoebebridgers',
            'manager_email' => 'booking@phoebebridgers.com'
        ],
        [
            'real_name' => 'Clairo',
            'stage_name' => 'Clairo',
            'genre' => 'Indie Pop',
            'instagram' => '@clairo',
            'manager_email' => 'management@clairo.com'
        ],

        // Additional Romanian Artists
        [
            'real_name' => 'Florin Salam',
            'stage_name' => 'Florin Salam',
            'genre' => 'Manele',
            'instagram' => '@florinsalamoficial',
            'manager_email' => 'contact@florinsalam.ro'
        ],
        [
            'real_name' => 'Andra Măruță',
            'stage_name' => 'Andra',
            'genre' => 'Pop',
            'instagram' => '@andraofficial',
            'manager_email' => 'booking@andra.ro'
        ],
        [
            'real_name' => 'Carla\'s Dreams',
            'stage_name' => 'Carla\'s Dreams',
            'genre' => 'Alternative Pop',
            'instagram' => '@carlasdreamsofficial',
            'manager_email' => 'management@carlasdreams.com'
        ]
    ];

    private const IMAGE_STYLES = [
        'friendly', 'fashion', 'business', 'nature', 'abstract', 'tech'
    ];
}
