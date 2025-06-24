-- =============================================
-- ROLES TABLE (5 queries)
-- =============================================
INSERT INTO roles (naming) VALUES ('ADMIN');
INSERT INTO roles (naming) VALUES ('MANAGER');
INSERT INTO roles (naming) VALUES ('STAFF');
INSERT INTO roles (naming) VALUES ('VOLUNTEER');
INSERT INTO roles (naming) VALUES ('REGULAR');

-- =============================================
-- USERS TABLE (15 queries)
-- =============================================
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('admin@festival.com', 'hashed_password_1', 'John', 'Admin', 35, '1234567890');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('manager@festival.com', 'hashed_password_2', 'Sarah', 'Manager', 42, '1234567891');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('alice.smith@email.com', 'hashed_password_3', 'Alice', 'Smith', 28, '1234567892');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('bob.jones@email.com', 'hashed_password_4', 'Bob', 'Jones', 31, '1234567893');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('carol.white@email.com', 'hashed_password_5', 'Carol', 'White', 25, '1234567894');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('david.brown@email.com', 'hashed_password_6', 'David', 'Brown', 33, '1234567895');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('emma.davis@email.com', 'hashed_password_7', 'Emma', 'Davis', 27, '1234567896');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('frank.wilson@email.com', 'hashed_password_8', 'Frank', 'Wilson', 45, '1234567897');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('grace.taylor@email.com', 'hashed_password_9', 'Grace', 'Taylor', 29, '1234567898');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('henry.moore@email.com', 'hashed_password_10', 'Henry', 'Moore', 38, '1234567899');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('ivy.clark@email.com', 'hashed_password_11', 'Ivy', 'Clark', 24, '2234567890');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('jack.lewis@email.com', 'hashed_password_12', 'Jack', 'Lewis', 36, '2234567891');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('kate.hall@email.com', 'hashed_password_13', 'Kate', 'Hall', 32, '2234567892');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('liam.young@email.com', 'hashed_password_14', 'Liam', 'Young', 26, '2234567893');
INSERT INTO users (email, password_, first_name, last_name, age, phone_nr) VALUES ('mia.king@email.com', 'hashed_password_15', 'Mia', 'King', 30, '2234567894');

-- =============================================
-- USER_ROLE TABLE (15 queries)
-- =============================================
INSERT INTO user_role (user_id, role_id) VALUES (1, 1);
INSERT INTO user_role (user_id, role_id) VALUES (2, 2);
INSERT INTO user_role (user_id, role_id) VALUES (3, 5);
INSERT INTO user_role (user_id, role_id) VALUES (4, 5);
INSERT INTO user_role (user_id, role_id) VALUES (5, 5);
INSERT INTO user_role (user_id, role_id) VALUES (6, 3);
INSERT INTO user_role (user_id, role_id) VALUES (7, 4);
INSERT INTO user_role (user_id, role_id) VALUES (8, 5);
INSERT INTO user_role (user_id, role_id) VALUES (9, 5);
INSERT INTO user_role (user_id, role_id) VALUES (10, 5);
INSERT INTO user_role (user_id, role_id) VALUES (11, 5);
INSERT INTO user_role (user_id, role_id) VALUES (12, 4);
INSERT INTO user_role (user_id, role_id) VALUES (13, 5);
INSERT INTO user_role (user_id, role_id) VALUES (14, 5);
INSERT INTO user_role (user_id, role_id) VALUES (15, 5);

-- =============================================
-- TICKET_STANDARD_PRICE TABLE (6 queries)
-- =============================================
INSERT INTO ticket_standard_price (ticket_type, standard_price) VALUES ('VIP', 299.99);
INSERT INTO ticket_standard_price (ticket_type, standard_price) VALUES ('Regular', 89.99);
INSERT INTO ticket_standard_price (ticket_type, standard_price) VALUES ('EarlyBird', 69.99);
INSERT INTO ticket_standard_price (ticket_type, standard_price) VALUES ('Parent', 79.99);
INSERT INTO ticket_standard_price (ticket_type, standard_price) VALUES ('Elderly', 59.99);
INSERT INTO ticket_standard_price (ticket_type, standard_price) VALUES ('Couple', 159.99);

-- =============================================
-- FESTIVAL TABLE (8 queries)
-- =============================================
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Summer Beats Festival', 'The ultimate summer music experience with top artists from around the world', 50000, '2025-07-15', '2025-07-17', 'USA', 'Los Angeles', 'Sunset Boulevard', 1234, 'published', 'info@summerbeats.com', 'https://summerbeats.com', 'Standard festival terms and conditions apply');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Rock Mountain Fest', 'Three days of pure rock music in the mountains', 30000, '2025-08-20', '2025-08-22', 'USA', 'Denver', 'Mountain View Drive', 567, 'published', 'contact@rockmountain.com', 'https://rockmountain.com', 'Rock festival terms and conditions');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Electronic Dreams', 'Electronic music festival featuring the best DJs worldwide', 40000, '2025-09-10', '2025-09-12', 'Germany', 'Berlin', 'Techno Street', 89, 'published', 'info@electronicdreams.de', 'https://electronicdreams.de', 'Electronic festival terms');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Jazz & Blues Weekend', 'Intimate jazz and blues performances', 15000, '2025-10-05', '2025-10-07', 'France', 'Paris', 'Rue de la Musique', 456, 'published', 'contact@jazzblues.fr', 'https://jazzblues.fr', 'Jazz festival terms');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Country Roads Festival', 'Country music celebration with top Nashville artists', 25000, '2025-11-15', '2025-11-17', 'USA', 'Nashville', 'Music Row', 123, 'published', 'info@countryroads.com', 'https://countryroads.com', 'Country festival terms');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Indie Vibes', 'Independent artists showcase', 20000, '2025-12-01', '2025-12-03', 'UK', 'London', 'Camden High Street', 789, 'published', 'hello@indievibes.co.uk', 'https://indievibes.co.uk', 'Indie festival terms');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('World Music Fusion', 'Celebrating global music traditions', 35000, '2026-01-20', '2026-01-22', 'Australia', 'Sydney', 'Harbour Bridge Road', 321, 'published', 'info@worldfusion.com.au', 'https://worldfusion.com.au', 'World music festival terms');
INSERT INTO festival (naming, about, people_capacity, start_date, end_date, country, city, street_name, street_nr, current_status, festival_contact_email, website, terms_conditions) VALUES ('Hip Hop Nation', 'The biggest hip hop festival of the year', 45000, '2026-02-14', '2026-02-16', 'USA', 'Atlanta', 'Peachtree Street', 654, 'published', 'contact@hiphopnation.com', 'https://hiphopnation.com', 'Hip hop festival terms');

-- =============================================
-- ARTIST TABLE (12 queries)
-- =============================================
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Taylor Swift', 'Taylor Swift', 'Pop', '@taylorswift', 'https://example.com/images/taylor_swift.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Robert Plant', 'Led Zeppelin', 'Rock', '@ledzeppelin', 'https://example.com/images/led_zeppelin.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Calvin Harris', 'Calvin Harris', 'Electronic', '@calvinharris', 'https://example.com/images/calvin_harris.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Miles Davis Jr', 'Miles Jr', 'Jazz', '@milesjr', 'https://example.com/images/miles_jr.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Keith Urban', 'Keith Urban', 'Country', '@keithurban', 'https://example.com/images/keith_urban.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Florence Welch', 'Florence Machine', 'Indie', '@florencemachine', 'https://example.com/images/florence.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Ravi Shankar Jr', 'Ravi World', 'World', '@raviworld', 'https://example.com/images/ravi_world.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Kendrick Lamar', 'K Dot', 'Hip Hop', '@kdot', 'https://example.com/images/kdot.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('David Guetta', 'David Guetta', 'Electronic', '@davidguetta', 'https://example.com/images/david_guetta.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('John Mayer', 'John Mayer', 'Blues', '@johnmayer', 'https://example.com/images/john_mayer.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('Billie Eilish', 'Billie Eilish', 'Pop', '@billieeilish', 'https://example.com/images/billie_eilish.jpg');
INSERT INTO artist (real_name, stage_name, music_genre, instagram_account, image_url) VALUES ('The Weeknd', 'The Weeknd', 'R&B', '@theweeknd', 'https://example.com/images/weeknd.jpg');

-- =============================================
-- FESTIVAL_ARTIST TABLE (13 queries)
-- =============================================
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (1, 1, TRUE, '2025-07-15', '21:00:00', '23:00:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (1, 11, TRUE, '2025-07-16', '21:30:00', '23:30:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (1, 12, FALSE, '2025-07-17', '19:00:00', '20:30:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (2, 2, TRUE, '2025-08-20', '20:00:00', '22:00:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (2, 10, FALSE, '2025-08-21', '18:00:00', '19:30:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (3, 3, TRUE, '2025-09-10', '22:00:00', '23:59:59');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (3, 9, TRUE, '2025-09-11', '23:00:00', '23:59:59');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (4, 4, TRUE, '2025-10-05', '20:00:00', '21:30:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (4, 10, FALSE, '2025-10-06', '19:00:00', '20:30:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (5, 5, TRUE, '2025-11-15', '21:00:00', '23:00:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (6, 6, TRUE, '2025-12-01', '20:30:00', '22:00:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (7, 7, TRUE, '2026-01-20', '19:30:00', '21:00:00');
INSERT INTO festival_artist (festival_id, artist_id, is_headliner, performance_date, start_time, end_time) VALUES (8, 8, TRUE, '2026-02-14', '21:00:00', '23:00:00');

-- =============================================
-- FESTIVAL_AMENITY TABLE (15 queries)
-- =============================================
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (1, 'parking', 25.00, 5000);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (1, 'camping', 75.00, 2000);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (1, 'food_court', 0.00, 1000);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (1, 'medical', 0.00, 50);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (2, 'parking', 20.00, 3000);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (2, 'camping', 60.00, 1500);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (2, 'outdoor_games', 15.00, 500);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (3, 'parking', 30.00, 4000);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (3, 'food_court', 0.00, 800);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (3, 'medical', 0.00, 40);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (4, 'parking', 15.00, 1500);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (4, 'food_court', 0.00, 300);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (5, 'parking', 20.00, 2500);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (5, 'camping', 50.00, 1000);
INSERT INTO festival_amenity (festival_id, amenity_type, price, capacity) VALUES (5, 'outdoor_games', 10.00, 300);

-- =============================================
-- TICKET_TYPE TABLE (16 queries)
-- =============================================
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (1, 'VIP', 'VIP access with backstage passes and premium viewing area', 500, '2025-03-01 00:00:00', '2025-07-14 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (1, 'Regular', 'Standard festival access', 5000, '2025-03-01 00:00:00', '2025-07-14 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (1, 'EarlyBird', 'Early bird special pricing', 1000, '2025-01-01 00:00:00', '2025-04-30 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (1, 'Couple', 'Special couple package', 800, '2025-02-14 00:00:00', '2025-07-14 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (2, 'VIP', 'VIP mountain experience', 300, '2025-04-01 00:00:00', '2025-08-19 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (2, 'Regular', 'Standard rock festival access', 3000, '2025-04-01 00:00:00', '2025-08-19 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (2, 'EarlyBird', 'Early bird rock tickets', 500, '2025-02-01 00:00:00', '2025-05-31 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (3, 'VIP', 'VIP electronic experience with DJ meet & greet', 400, '2025-05-01 00:00:00', '2025-09-09 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (3, 'Regular', 'Standard electronic festival access', 4000, '2025-05-01 00:00:00', '2025-09-09 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (3, 'Couple', 'Electronic couple experience', 600, '2025-05-01 00:00:00', '2025-09-09 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (4, 'VIP', 'Premium jazz experience', 200, '2025-06-01 00:00:00', '2025-10-04 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (4, 'Regular', 'Standard jazz festival access', 1500, '2025-06-01 00:00:00', '2025-10-04 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (4, 'Elderly', 'Senior citizen discount tickets', 300, '2025-06-01 00:00:00', '2025-10-04 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (5, 'VIP', 'VIP country experience', 250, '2025-07-01 00:00:00', '2025-11-14 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (5, 'Regular', 'Standard country festival access', 2500, '2025-07-01 00:00:00', '2025-11-14 23:59:59');
INSERT INTO ticket_type (festival_id, typeof, about, quantity_available, start_sale_date, end_sale_date) VALUES (5, 'Parent', 'Family-friendly tickets', 400, '2025-07-01 00:00:00', '2025-11-14 23:59:59');

-- =============================================
-- FESTIVAL_REVIEW TABLE (10 queries)
-- =============================================
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (1, 3, 5, 'Amazing festival! Taylor Swift was incredible and the organization was perfect.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (1, 4, 4, 'Great lineup and good facilities. Parking was a bit expensive though.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (1, 5, 5, 'Best festival experience ever! Will definitely come back next year.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (2, 6, 4, 'Solid rock festival with great mountain views. Sound quality was excellent.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (2, 7, 3, 'Good music but camping facilities could be better.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (3, 8, 5, 'Electronic music paradise! Calvin Harris and David Guetta were phenomenal.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (3, 9, 4, 'Great DJs and amazing light shows. Food options were limited.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (4, 10, 5, 'Intimate jazz experience. Miles Jr was absolutely brilliant.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (4, 11, 4, 'Beautiful venue and excellent acoustics for jazz music.');
INSERT INTO festival_review (festival_id, user_id, rating_stars, comment) VALUES (5, 12, 4, 'Great country music festival. Keith Urban was fantastic!');

-- =============================================
-- TICKET_PURCHASE TABLE (10 queries)
-- =============================================
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (1, 3, 'card', 'confirmed', 'TIX-20250301-0001', '2025-07-15 08:00:00', '2025-07-17 23:59:59', 'QR001');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (2, 4, 'transfer', 'confirmed', 'TIX-20250301-0002', '2025-07-15 08:00:00', '2025-07-17 23:59:59', 'QR002');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (3, 5, 'card', 'confirmed', 'TIX-20250301-0003', '2025-07-15 08:00:00', '2025-07-17 23:59:59', 'QR003');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (4, 8, 'card', 'confirmed', 'TIX-20250301-0004', '2025-07-15 08:00:00', '2025-07-17 23:59:59', 'QR004');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (5, 6, 'cash', 'pending', 'TIX-20250401-0005', '2025-08-20 08:00:00', '2025-08-22 23:59:59', 'QR005');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (6, 7, 'card', 'confirmed', 'TIX-20250401-0006', '2025-08-20 08:00:00', '2025-08-22 23:59:59', 'QR006');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (8, 9, 'transfer', 'confirmed', 'TIX-20250501-0007', '2025-09-10 08:00:00', '2025-09-12 23:59:59', 'QR007');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (9, 10, 'card', 'confirmed', 'TIX-20250501-0008', '2025-09-10 08:00:00', '2025-09-12 23:59:59', 'QR008');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (11, 11, 'card', 'confirmed', 'TIX-20250601-0009', '2025-10-05 08:00:00', '2025-10-07 23:59:59', 'QR009');
INSERT INTO ticket_purchase (ticket_type_id, user_id, payment_method_used, current_status, order_number, check_in_time, check_out_time, qr_code) VALUES (13, 12, 'transfer', 'cancelled', 'TIX-20250601-0010', '2025-10-05 08:00:00', '2025-10-07 23:59:59', 'QR010');

-- =============================================
-- TOTAL: 125 INSERT QUERIES
-- =============================================