<?php

namespace App\DataFixtures\traits\hardcodedData;

trait EditionReviewData
{
    private const RATING_DISTRIBUTION = [   1, 1,
                                            2, 2, 2, 2,
                                            3, 3, 3, 3,
                                            4, 4, 4, 4, 4,
                                            5, 5, 5, 5, 5, 5
                                        ];

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
}
