<?php

namespace App\DataFixtures\traits\hardcodedData;

trait TicketData
{
    private const ESSENTIAL_TYPES = [   'General Access', 'Weekend Pass',
                                        'VIP Experience', 'Single Day Pass'
                                    ];

    private const TICKET_TYPES_DATA = [
        [
            'name' => 'General Access',
            'benefits' => 'Access to main festival grounds, all outdoor stages, food courts, and merchandise stands. Standing room only.',
            'base_price' => [45.00, 85.00]
        ],
        [
            'name' => 'Single Day Pass',
            'benefits' => 'Full access to festival for one day only. Choose your preferred day during checkout. All stages and activities included.',
            'base_price' => [35.00, 65.00]
        ],
        [
            'name' => 'Weekend Pass',
            'benefits' => 'Complete festival experience for all days. Access to all stages, activities, and special events. Best value for multi-day festivals.',
            'base_price' => [120.00, 220.00]
        ],
        [
            'name' => 'VIP Experience',
            'benefits' => 'Premium viewing areas, dedicated VIP entrance, complimentary drinks, exclusive restrooms, VIP lounge access, and meet & greet opportunities.',
            'base_price' => [250.00, 450.00]
        ],
        [
            'name' => 'Platinum VIP',
            'benefits' => 'Ultimate festival experience with backstage access, artist meet & greets, premium catering, private bar, luxury restrooms, and exclusive merchandise.',
            'base_price' => [500.00, 900.00]
        ],
        [
            'name' => 'Student Discount',
            'benefits' => 'Special pricing for students with valid ID. Same access as General Admission with discounted rate. Limited quantity available.',
            'base_price' => [30.00, 55.00]
        ],
        [
            'name' => 'Early Bird Special',
            'benefits' => 'Limited time offer for early purchasers. Full festival access at reduced price. Same benefits as Weekend Pass but with significant savings.',
            'base_price' => [80.00, 150.00]
        ],
        [
            'name' => 'Group Package (4+)',
            'benefits' => 'Special rate for groups of 4 or more people. Includes group check-in, reserved seating area, and group photo opportunity.',
            'base_price' => [160.00, 300.00]
        ],
        [
            'name' => 'Family Pass (2 Adults + 2 Kids)',
            'benefits' => 'Perfect for families with children under 12. Includes kids activities area, family restrooms, and priority entry for family-friendly shows.',
            'base_price' => [180.00, 320.00]
        ],
        [
            'name' => 'Senior Citizen (65+)',
            'benefits' => 'Discounted admission for seniors with comfortable seating areas, easy access paths, and dedicated customer service.',
            'base_price' => [35.00, 65.00]
        ],
        [
            'name' => 'Press/Media Pass',
            'benefits' => 'Professional media access with photo pit privileges, press conference access, and media center facilities. Credential verification required.',
            'base_price' => [0.00, 50.00]
        ],
        [
            'name' => 'Artist/Industry Pass',
            'benefits' => 'Industry professional access with backstage areas, artist lounges, networking events, and industry mixer invitations.',
            'base_price' => [100.00, 200.00]
        ],
        [
            'name' => 'Camping Add-On',
            'benefits' => 'Festival ticket plus camping access. Includes designated camping area, shower facilities, security, and shuttle service to main venue.',
            'base_price' => [200.00, 350.00]
        ],
        [
            'name' => 'Glamping Experience',
            'benefits' => 'Luxury camping with pre-pitched tents, comfortable beds, private bathrooms, daily housekeeping, and gourmet meal service.',
            'base_price' => [400.00, 700.00]
        ],
        [
            'name' => 'Day Parking Pass',
            'benefits' => 'Guaranteed parking spot for single day. Includes shuttle service to main entrance and priority exit lanes.',
            'base_price' => [15.00, 35.00]
        ],
        [
            'name' => 'Premium Parking',
            'benefits' => 'VIP parking closest to main entrance with covered spots, security monitoring, and complimentary car wash service.',
            'base_price' => [50.00, 100.00]
        ],
        [
            'name' => 'Accessibility Pass',
            'benefits' => 'Special accommodations for guests with disabilities. Includes accessible viewing areas, priority seating, and dedicated assistance.',
            'base_price' => [40.00, 75.00]
        ],
        [
            'name' => 'Late Night After-Party',
            'benefits' => 'Exclusive access to after-hours events, late night DJ sets, premium bar service, and extended festival experience until 4 AM.',
            'base_price' => [75.00, 150.00]
        ],
        [
            'name' => 'Food & Beverage Package',
            'benefits' => 'Festival admission plus meal vouchers, unlimited soft drinks, and access to premium food vendors. Great value for food lovers.',
            'base_price' => [120.00, 200.00]
        ],
        [
            'name' => 'Photography Pass',
            'benefits' => 'Special access for photography enthusiasts with photo pit access, golden hour sessions, and professional photography workshops.',
            'base_price' => [80.00, 140.00]
        ]
    ];

    // Quantity ranges for different ticket types
    private const QUANTITY_RULES = [ //predefined quantities that maps ticket type (limits/ticket_type)
        'General Admission' => ['min' => 1, 'max' => 6],
        'Single Day Pass' => ['min' => 1, 'max' => 4],
        'Weekend Pass' => ['min' => 1, 'max' => 4],
        'VIP Experience' => ['min' => 1, 'max' => 3],
        'Platinum VIP' => ['min' => 1, 'max' => 2],
        'Student Discount' => ['min' => 1, 'max' => 2],
        'Early Bird Special' => ['min' => 1, 'max' => 5],
        'Group Package (4+)' => ['min' => 4, 'max' => 12],
        'Family Pass (2 Adults + 2 Kids)' => ['min' => 1, 'max' => 2], // Each pass covers 4 people
        'Senior Citizen (65+)' => ['min' => 1, 'max' => 2],
        'Press/Media Pass' => ['min' => 1, 'max' => 1],
        'Artist/Industry Pass' => ['min' => 1, 'max' => 2],
        'Camping Add-On' => ['min' => 1, 'max' => 4],
        'Glamping Experience' => ['min' => 1, 'max' => 2],
        'Day Parking Pass' => ['min' => 1, 'max' => 3],
        'Premium Parking' => ['min' => 1, 'max' => 2],
        'Accessibility Pass' => ['min' => 1, 'max' => 2],
        'Late Night After-Party' => ['min' => 1, 'max' => 4],
        'Food & Beverage Package' => ['min' => 1, 'max' => 6],
        'Photography Pass' => ['min' => 1, 'max' => 1]
    ];
}
