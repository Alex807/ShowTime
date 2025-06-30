<?php

namespace App\DataFixtures\traits\hardcodedData;

trait FestivalData
{
    private const FESTIVAL_NAMES = [
        'Coachella Valley',
        'Glastonbury',
        'Tomorrowland',
        'Burning Man',
        'Lollapalooza',
        'Bonnaroo Music',
        'Arts Festival',
        'Electric Daisy ',
        'Ultra Music Festival',
        'Woodstock Revival',
        'Summer Sonic Festival',
        'Rock am Ring',
        'Download Festival',
        'Primavera Sound',
        'SXSW',
        'Roskilde Festival',
        'Sziget Festival',
        'Untold Festival',
        'Electric Castle',
        'Neversea',
        'Jazz in the Park',
        'George Enescu Festival',
        'Sunwaves Festival',
        'Beach Please Festival',
        'Awake Festival',
        'Afterhills Festival'
    ];

    private const COUNTRIES_CITIES = [
        'United States' => ['Los Angeles', 'Austin', 'Chicago', 'Miami', 'New York', 'Las Vegas'],
        'United Kingdom' => ['London', 'Manchester', 'Bristol', 'Edinburgh', 'Birmingham'],
        'Germany' => ['Berlin', 'Munich', 'Hamburg', 'Cologne', 'Frankfurt'],
        'Belgium' => ['Brussels', 'Antwerp', 'Ghent', 'Bruges'],
        'Spain' => ['Barcelona', 'Madrid', 'Valencia', 'Seville'],
        'France' => ['Paris', 'Lyon', 'Marseille', 'Nice', 'Toulouse'],
        'Netherlands' => ['Amsterdam', 'Rotterdam', 'Utrecht', 'The Hague'],
        'Romania' => ['Bucharest', 'Cluj-Napoca', 'Timisoara', 'Iasi', 'Constanta', 'Turceni'],
        'Hungary' => ['Budapest', 'Debrecen', 'Szeged'],
        'Denmark' => ['Copenhagen', 'Aarhus', 'Odense'],
        'Japan' => ['Tokyo', 'Osaka', 'Kyoto', 'Yokohama'],
        'Australia' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth']
    ];

    private const STREET_NAMES = [
        'Main Street', 'Park Avenue', 'Festival Boulevard', 'Music Lane', 'Concert Drive',
        'Harmony Road', 'Melody Street', 'Rhythm Avenue', 'Beat Boulevard', 'Sound Street',
        'Arena Way', 'Stage Road', 'Performance Plaza', 'Entertainment Drive', 'Cultural Avenue',
        'Arts Street', 'Creative Boulevard', 'Venue Road', 'Event Plaza', 'Celebration Street'
    ];

    private const CONTACT_DOMAINS = [
        '@festivalinfo.ro', '@musicfest.org', '@eventcontact.net', '@festivalworld.com',
        '@musicevents.org', '@concertinfo.ro', '@festivalmanagement.net', '@eventorganizers.com'
    ];

    private const WEBSITE_DOMAINS = [
        'festival.com', 'musicfest.org', 'eventworld.net', 'concertinfo.com',
        'festivalguide.org', 'musicevents.net', 'livemusic.com', 'festivallife.org'
    ];

    private const LOGO_PLACEHOLDER_STYLES = [
        'abstract', 'nature', 'tech', 'people', 'animals', 'business', 'fashion', 'food'
    ];

    private const VENUE_TYPES = [
        'Arena', 'Stadium', 'Park', 'Beach', 'Amphitheater', 'Convention Center',
        'Outdoor Stage', 'Concert Hall', 'Festival Grounds', 'Open Air Theater',
        'Sports Complex', 'Exhibition Center', 'Cultural Center', 'Music Hall',
        'Fairgrounds', 'Racecourse', 'Castle Grounds', 'Historic Site'
    ];

    private const VENUE_NAMES = [
        'Central Park', 'Olympic Stadium', 'Royal Arena', 'Sunset Beach', 'Grand Amphitheater',
        'Metropolitan Center', 'Riverside Grounds', 'Golden Gate Park', 'Crystal Palace',
        'Phoenix Stadium', 'Liberty Square', 'Heritage Gardens', 'Marina Bay',
        'Thunder Valley', 'Moonlight Theater', 'Diamond Arena', 'Emerald Fields',
        'Silver Lake', 'Copper Canyon', 'Iron Mountain'
    ];

    private const EDITION_DESCRIPTIONS = [
        'An unforgettable musical journey featuring world-class artists and immersive experiences.',
        'Three days of non-stop entertainment with multiple stages and diverse musical genres.',
        'The ultimate celebration of music, art, and culture in a stunning outdoor setting.',
        'A premium festival experience combining legendary performers with emerging talent.',
        'An epic gathering of music lovers from around the world in a breathtaking venue.',
        'The definitive music festival featuring cutting-edge production and stellar lineups.',
        'A transformative experience blending music, technology, and artistic expression.',
        'The most anticipated music event of the year with exclusive performances.',
        'A multi-sensory festival experience featuring interactive art installations.',
        'The perfect fusion of music, food, and culture in an iconic location.'
    ];

    private const POSSIBLE_STATUSES = [
        'completed', 'upcoming', 'cancelled', 'postponed', 'sold_out'
    ];

    private const TERMS_CONDITIONS_TEMPLATES = [
        'All attendees must be 18+ with valid ID. No outside food or beverages allowed. Festival reserves the right to search bags and refuse entry.',
        'Tickets are non-refundable and non-transferable. Camping facilities available with separate ticket purchase. No glass containers permitted.',
        'Entry subject to security screening. Professional cameras prohibited without media accreditation. Medical facilities available on-site.',
        'Weather-dependent event - no refunds for weather-related cancellations. Designated smoking areas only. Lost wristbands cannot be replaced.',
        'Age restrictions apply for certain areas. No re-entry policy in effect. Emergency evacuation procedures will be announced if necessary.'
    ];

    private const VENUE_CAPACITY_RANGES = [
        'Arena' => [5000, 20000],
        'Stadium' => [30000, 80000],
        'Park' => [10000, 50000],
        'Beach' => [5000, 25000],
        'Amphitheater' => [3000, 15000]
    ];
}
