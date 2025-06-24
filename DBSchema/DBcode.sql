-- Drop existing procedures
DROP PROCEDURE IF EXISTS create_festival;
DROP PROCEDURE IF EXISTS add_artist_to_festival;
DROP PROCEDURE IF EXISTS purchase_ticket;
DROP PROCEDURE IF EXISTS update_festival_statuses;

-- Drop existing functions
DROP FUNCTION IF EXISTS get_ticket_price;
DROP FUNCTION IF EXISTS is_festival_active;
DROP FUNCTION IF EXISTS get_available_tickets;

-- Drop existing views
DROP VIEW IF EXISTS ticket_type_with_price;

-- Drop existing tables and their constraints
DROP TABLE IF EXISTS ticket_purchase;
DROP TABLE IF EXISTS ticket_type;
DROP TABLE IF EXISTS ticket_standard_price;
DROP TABLE IF EXISTS festival_artist;
DROP TABLE IF EXISTS festival_amenity;
DROP TABLE IF EXISTS festival_review;
DROP TABLE IF EXISTS artist;
DROP TABLE IF EXISTS festival;
DROP TABLE IF EXISTS user_role;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_ VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    age INTEGER NOT NULL CHECK (age >= 0 AND age <= 120),
    phone_nr VARCHAR(20) UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT valid_email CHECK (email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'), 
    CONSTRAINT valid_phone CHECK (phone_nr REGEXP '^[0-9]{10}$')
);

CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naming ENUM ('ADMIN', 'MANAGER', 'STAFF', 'VOLUNTEER', 'REGULAR') NOT NULL
);

CREATE TABLE user_role (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

CREATE TABLE festival (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naming VARCHAR(255) NOT NULL,
    about TEXT NOT NULL,
    people_capacity INT NOT NULL CHECK (people_capacity > 0),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    country VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    street_name VARCHAR(255) NOT NULL,
    street_nr INT NOT NULL CHECK (street_nr > 0),
    current_status ENUM ('published', 'ongoing', 'ended', 'cancelled') NOT NULL,
    festival_contact_email VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    terms_conditions TEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT valid_dates CHECK (end_date >= start_date),
    CONSTRAINT valid_website CHECK (website REGEXP '^https?://.*$'),
    CONSTRAINT valid_festival_email CHECK (
        festival_contact_email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'
    )
);

-- Add a trigger for inser/update cases to validate dates (against current date)
DELIMITER //

CREATE TRIGGER before_festival_insert 
BEFORE INSERT ON festival
FOR EACH ROW 
BEGIN
    IF NEW.start_date >= CURDATE() AND NEW.end_date >= CURDATE() THEN
        SET NEW.start_date = NEW.start_date;
    END IF;
END//

CREATE TRIGGER before_festival_update
BEFORE UPDATE ON festival
FOR EACH ROW 
BEGIN
    IF NEW.start_date >= CURDATE() AND NEW.end_date >= CURDATE() THEN
        SET NEW.start_date = NEW.start_date;
    END IF;
END//

DELIMITER ;

CREATE TABLE festival_review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    festival_id INT NOT NULL,
    user_id INT NOT NULL,
    rating_stars INT NOT NULL CHECK (rating_stars >= 1 AND rating_stars <= 5),
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (festival_id) REFERENCES festival(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE festival_amenity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    festival_id INT NOT NULL,
    amenity_type ENUM ('parking', 'camping', 'food_court', 'medical', 'outdoor_games') NOT NULL,
    price DECIMAL(10,2) CHECK (price >= 0),
    capacity INT CHECK (capacity > 0),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (festival_id) REFERENCES festival(id) ON DELETE CASCADE
);

CREATE TABLE artist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    real_name VARCHAR(255) NOT NULL,
    stage_name VARCHAR(255) NOT NULL UNIQUE,
    music_genre VARCHAR(100) NOT NULL,
    instagram_account VARCHAR(255) NOT NULL,
    image_url VARCHAR(512) NOT NULL, 

    CONSTRAINT valid_image_url CHECK (
        image_url REGEXP '^https?://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,}(/\S*)?$'
    ),
    CONSTRAINT valid_instagram CHECK (
        instagram_account REGEXP '^@[A-Za-z0-9._]{1,30}$'
    )
);

CREATE TABLE festival_artist (
    festival_id INT NOT NULL,
    artist_id INT NOT NULL,
    is_headliner BOOLEAN DEFAULT FALSE,
    performance_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    PRIMARY KEY (festival_id, artist_id, performance_date),
    FOREIGN KEY (festival_id) REFERENCES festival(id) ON DELETE CASCADE,
    FOREIGN KEY (artist_id) REFERENCES artist(id) ON DELETE CASCADE,

    CONSTRAINT valid_performance_time CHECK (end_time > start_time)
);
DELIMITER //

CREATE TRIGGER before_festival_artist_insert
BEFORE INSERT ON festival_artist
FOR EACH ROW 
BEGIN
    DECLARE festival_start DATE;
    DECLARE festival_end DATE;
    
    SELECT start_date, end_date 
    INTO festival_start, festival_end
    FROM festival 
    WHERE id = NEW.festival_id;
    
    IF NOT (NEW.performance_date >= CURDATE() 
        AND NEW.performance_date >= festival_start 
        AND NEW.performance_date <= festival_end) THEN
        SET NEW.performance_date = NULL;
    END IF;
END//

CREATE TRIGGER before_festival_artist_update
BEFORE UPDATE ON festival_artist
FOR EACH ROW 
BEGIN
    DECLARE festival_start DATE;
    DECLARE festival_end DATE;
    
    SELECT start_date, end_date 
    INTO festival_start, festival_end
    FROM festival 
    WHERE id = NEW.festival_id;
    
    IF NOT (NEW.performance_date >= CURDATE() 
        AND NEW.performance_date >= festival_start 
        AND NEW.performance_date <= festival_end) THEN
        SET NEW.performance_date = NULL;
    END IF;
END//

DELIMITER ;
CREATE TABLE ticket_standard_price (
    ticket_type ENUM ('VIP', 'Regular', 'EarlyBird', 'Parent', 'Elderly', 'Couple') PRIMARY KEY,
    standard_price DECIMAL(10,2) NOT NULL CHECK (standard_price >= 0)
);

CREATE TABLE ticket_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    festival_id INT NOT NULL,
    typeof ENUM ('VIP', 'Regular', 'EarlyBird', 'Parent', 'Elderly', 'Couple') NOT NULL,
    about TEXT,
    quantity_available INT NOT NULL CHECK (quantity_available >= 0),
    start_sale_date DATETIME NOT NULL,
    end_sale_date DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (festival_id) REFERENCES festival(id) ON DELETE CASCADE,
    FOREIGN KEY (typeof) REFERENCES ticket_standard_price(ticket_type)
); 
DELIMITER //

CREATE TRIGGER before_ticket_type_insert
BEFORE INSERT ON ticket_type
FOR EACH ROW 
BEGIN
    DECLARE festival_start DATE;
    
    SELECT start_date 
    INTO festival_start
    FROM festival 
    WHERE id = NEW.festival_id;
    
    IF NOT (NEW.start_sale_date <= festival_start) THEN
        SET NEW.start_sale_date = NULL;
    END IF;
END//

CREATE TRIGGER before_ticket_type_update
BEFORE UPDATE ON ticket_type
FOR EACH ROW 
BEGIN
    DECLARE festival_start DATE;
    
    SELECT start_date 
    INTO festival_start
    FROM festival 
    WHERE id = NEW.festival_id;
    
    IF NOT (NEW.start_sale_date <= festival_start) THEN
        SET NEW.start_sale_date = NULL;
    END IF;
END//

DELIMITER ;

CREATE TABLE ticket_purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_type_id INT NOT NULL,
    user_id INT NOT NULL,
    payment_method_used ENUM ('card', 'transfer', 'cash') NOT NULL,
    current_status ENUM ('pending', 'confirmed', 'cancelled') NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE, 
    price_paid DECIMAL(10,2),
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    check_in_time DATETIME NOT NULL,
    check_out_time DATETIME NOT NULL,
    qr_code VARCHAR(255),
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_type(id),
    FOREIGN KEY (user_id) REFERENCES users(id),

    CONSTRAINT valid_check_times CHECK (check_out_time >= check_in_time)
);

-- Create a view to easily see ticket types with their prices
CREATE VIEW ticket_type_with_price AS
SELECT 
    tt.*,
    tsp.standard_price as price
FROM 
    ticket_type tt
    JOIN ticket_standard_price tsp ON tt.typeof = tsp.ticket_type;


-- Create a trigger to automatically set price_paid in ticket_purchase
DELIMITER //
CREATE TRIGGER before_ticket_purchase_insert 
BEFORE INSERT ON ticket_purchase
FOR EACH ROW
BEGIN
    SET NEW.price_paid = get_ticket_price(NEW.ticket_type_id);
END//
DELIMITER ;

DELIMITER //
-- Function to get ticket price based on ticket type and festival
CREATE FUNCTION get_ticket_price(ticket_type_id INT) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE price DECIMAL(10,2);
    
    SELECT tsp.standard_price INTO price
    FROM ticket_type tt
    JOIN ticket_standard_price tsp ON tt.typeof = tsp.ticket_type
    WHERE tt.id = ticket_type_id;
    
    RETURN price;
END//

-- Procedure to create a new festival
CREATE PROCEDURE create_festival(
    IN p_name VARCHAR(255),
    IN p_description TEXT,
    IN p_capacity INT,
    IN p_start_date DATE,
    IN p_end_date DATE,
    IN p_country VARCHAR(100),
    IN p_city VARCHAR(100),
    IN p_street_name VARCHAR(255),
    IN p_street_nr INT,
    IN p_email VARCHAR(255),
    IN p_website VARCHAR(255),
    IN p_terms TEXT,
    OUT p_festival_id INT
)
BEGIN
    INSERT INTO festival (
        naming, about, people_capacity, start_date, end_date,
        country, city, street_name, street_nr, current_status,
        festival_contact_email, website, terms_conditions
    ) VALUES (
        p_name, p_description, p_capacity, p_start_date, p_end_date,
        p_country, p_city, p_street_name, p_street_nr, 'published',
        p_email, p_website, p_terms
    );
    
    SET p_festival_id = LAST_INSERT_ID();
END//

-- Procedure to add artist to festival
CREATE PROCEDURE add_artist_to_festival(
    IN p_festival_id INT,
    IN p_artist_id INT,
    IN p_is_headliner BOOLEAN,
    IN p_performance_date DATE,
    IN p_start_time TIME,
    IN p_end_time TIME
)
BEGIN
    DECLARE festival_start_date DATE;
    DECLARE festival_end_date DATE;
    
    -- Get festival dates
    SELECT start_date, end_date 
    INTO festival_start_date, festival_end_date
    FROM festival 
    WHERE id = p_festival_id;
    
    -- Validate dates
    IF p_performance_date < CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Performance date cannot be in the past';
    END IF;
    
    IF p_performance_date < festival_start_date OR p_performance_date > festival_end_date THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Performance date must be within festival dates';
    END IF;
    
    -- Insert if validation passes
    INSERT INTO festival_artist (
        festival_id, artist_id, is_headliner, 
        performance_date, start_time, end_time
    ) VALUES (
        p_festival_id, p_artist_id, p_is_headliner,
        p_performance_date, p_start_time, p_end_time
    );
END//

-- Procedure to purchase ticket
CREATE PROCEDURE purchase_ticket(
    IN p_ticket_type_id INT,
    IN p_user_id INT,
    IN p_payment_method ENUM('card', 'transfer', 'cash'),
    OUT p_order_number VARCHAR(50)
)
BEGIN
    DECLARE ticket_available INT;
    
    -- Check ticket availability
    SELECT quantity_available INTO ticket_available
    FROM ticket_type
    WHERE id = p_ticket_type_id;
    
    IF ticket_available <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No tickets available';
    END IF;
    
    -- Generate unique order number
    SET p_order_number = CONCAT(
        'TIX-',
        DATE_FORMAT(NOW(), '%Y%m%d'),
        '-',
        LPAD(FLOOR(RAND() * 10000), 4, '0')
    );
    
    -- Create purchase record
    INSERT INTO ticket_purchase (
        ticket_type_id, user_id, payment_method_used,
        current_status, order_number,
        check_in_time, check_out_time
    ) VALUES (
        p_ticket_type_id, p_user_id, p_payment_method,
        'pending', p_order_number,
        (SELECT start_date FROM festival f 
         JOIN ticket_type tt ON f.id = tt.festival_id 
         WHERE tt.id = p_ticket_type_id),
        (SELECT end_date FROM festival f 
         JOIN ticket_type tt ON f.id = tt.festival_id 
         WHERE tt.id = p_ticket_type_id)
    );
    
    -- Update available tickets
    UPDATE ticket_type
    SET quantity_available = quantity_available - 1
    WHERE id = p_ticket_type_id;
END//

-- Function to check if festival is active
CREATE FUNCTION is_festival_active(festival_id INT) 
RETURNS BOOLEAN
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE festival_status ENUM('published', 'ongoing', 'ended', 'cancelled');
    
    SELECT current_status INTO festival_status
    FROM festival
    WHERE id = festival_id;
    
    RETURN festival_status IN ('published', 'ongoing');
END//

-- Procedure to update festival status based on dates
CREATE PROCEDURE update_festival_statuses()
BEGIN
    -- Update to 'ongoing' if started
    UPDATE festival
    SET current_status = 'ongoing'
    WHERE start_date <= CURDATE()
    AND end_date >= CURDATE()
    AND current_status = 'published';
    
    -- Update to 'ended' if finished
    UPDATE festival
    SET current_status = 'ended'
    WHERE end_date < CURDATE()
    AND current_status IN ('published', 'ongoing');
END//

-- Function to get available tickets count
CREATE FUNCTION get_available_tickets(p_festival_id INT, p_ticket_type ENUM('VIP', 'Regular', 'EarlyBird', 'Parent', 'Elderly', 'Couple')) 
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE available INT;
    
    SELECT quantity_available INTO available
    FROM ticket_type
    WHERE festival_id = p_festival_id
    AND typeof = p_ticket_type;
    
    RETURN COALESCE(available, 0);
END//
DELIMITER ;

-- Festival table
CREATE INDEX idx_festival_location_dates ON festival(country, city, start_date, end_date);
CREATE INDEX idx_festival_status ON festival(current_status);  -- Keep for status filtering

-- Festival Review table
CREATE INDEX idx_festival_review_composite ON festival_review(festival_id, user_id, rating_stars);

-- Festival Artist table
CREATE INDEX idx_festival_artist_composite ON festival_artist(festival_id, performance_date, is_headliner);

-- Ticket Type table
CREATE INDEX idx_ticket_type_composite ON ticket_type(festival_id, typeof, start_sale_date, end_sale_date);

-- Ticket Purchase table
CREATE INDEX idx_ticket_purchase_composite ON ticket_purchase(user_id, current_status, purchase_date);