<?php

namespace App\DataFixtures\traits\hardcodedData;

trait PurchaseData
{
    // Purchase status distribution (realistic percentages)
    private const PURCHASE_STATUSES = [
        'completed' => 75,      // 75% completed purchases
        'pending' => 8,         // 8% pending payments
        'cancelled' => 10,      // 10% cancelled by user
        'refunded' => 4,        // 4% refunded
        'failed' => 3           // 3% failed payments
    ];

    // Quantity ranges for different amenity types
    private const QUANTITY_RULES = [
        // High-capacity amenities (can have multiple quantities)
        'high_capacity' => [
            'names' => [
                'VIP Lounge Access', 'Premium Camping Package', 'Glamping Experience',
                'Festival Shuttle Service', 'Gourmet Catering Service', 'Artist Meal Package',
                'Exclusive Merchandise Package', 'VIP Dining Experience'
            ],
            'min_qty' => 1,
            'max_qty' => 8
        ],
        // Medium-capacity amenities
        'medium_capacity' => [
            'names' => [
                'VIP Backstage Access', 'Artist Green Room', 'Private Bar Service',
                'Artist Meet & Greet', 'Exclusive Workshop Access', 'VIP Photo Shoot',
                'Artist Spa Package', 'Medical Support Service'
            ],
            'min_qty' => 1,
            'max_qty' => 4
        ],
        // Low-capacity/exclusive amenities (usually single quantity)
        'low_capacity' => [
            'names' => [
                'Private Hospitality Suite', 'Artist Hotel Suite', 'Luxury Transportation',
                'Helicopter Transfer', 'Private Jet Charter', 'Security Detail',
                'Personal Assistant Service', 'Professional Sound Equipment'
            ],
            'min_qty' => 1,
            'max_qty' => 2
        ],
        // Service-based amenities (single or few quantities)
        'service_based' => [
            'names' => [
                'Stage Lighting Package', 'Video Production Service', 'Technical Crew Support',
                'Artist Trailer Rental'
            ],
            'min_qty' => 1,
            'max_qty' => 3
        ]
    ];

    // Amenities that are commonly purchased together
    private const AMENITY_BUNDLES = [
        'vip_experience' => [
            'VIP Lounge Access', 'VIP Backstage Access', 'Private Bar Service', 'Exclusive Merchandise Package'
        ],
        'luxury_package' => [
            'Private Hospitality Suite', 'Luxury Transportation', 'Gourmet Catering Service', 'Personal Assistant Service'
        ],
        'camping_bundle' => [
            'Premium Camping Package', 'Festival Shuttle Service', 'Artist Meal Package', 'Medical Support Service'
        ],
        'artist_experience' => [
            'Artist Meet & Greet', 'VIP Photo Shoot', 'Exclusive Workshop Access', 'Exclusive Merchandise Package'
        ],
        'technical_package' => [
            'Professional Sound Equipment', 'Stage Lighting Package', 'Video Production Service', 'Technical Crew Support'
        ]
    ];
}
