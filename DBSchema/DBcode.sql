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
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS user;

-- Drop existing types
DROP TYPE IF EXISTS role_type;
DROP TYPE IF EXISTS festival_status;
DROP TYPE IF EXISTS amenity_type;
DROP TYPE IF EXISTS ticket_type;
DROP TYPE IF EXISTS payment_method;
DROP TYPE IF EXISTS purchase_status;

-- Now create types and tables
CREATE TYPE role_type AS ENUM ('ADMIN', 'MANAGER', 'STAFF', 'VOLUNTEER', 'REGULAR');
CREATE TYPE festival_status AS ENUM ('published', 'ongoing', 'ended', 'cancelled');
CREATE TYPE amenity_type AS ENUM ('parking', 'camping', 'food_court', 'medical', 'outdoor_games');
CREATE TYPE ticket_type AS ENUM ('VIP', 'Regular', 'EarlyBird', 'Parent', 'Elderly', 'Couple');
CREATE TYPE payment_method AS ENUM ('card', 'transfer', 'cash');
CREATE TYPE purchase_status AS ENUM ('pending', 'confirmed', 'cancelled');

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    age INTEGER NOT NULL CHECK (age >= 0 AND age <= 120),
    phone_nr VARCHAR(20) UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT valid_email CHECK (email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'), 
    CONSTRAINT valid_phone CHECK (phone_nr REGEXP '^[0-9]{10}$')
);

CREATE TABLE role (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name role_type NOT NULL
);

CREATE TABLE user_role (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES role(id) ON DELETE CASCADE
);

CREATE TABLE festival (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    people_capacity INT NOT NULL CHECK (people_capacity > 0),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    country VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    street_name VARCHAR(255) NOT NULL,
    street_nr INT NOT NULL CHECK (street_nr > 0),
    status festival_status NOT NULL,
    festival_contact_email VARCHAR(255) NOT NULL,
    website VARCHAR(255),
    terms_conditions TEXT NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT valid_dates CHECK (
        end_date >= start_date 
        AND start_date >= CURRENT_DATE
        AND end_date >= CURRENT_DATE
    ),
    CONSTRAINT valid_website CHECK (website REGEXP '^https?://.*$'),
    CONSTRAINT valid_festival_email CHECK (
        festival_contact_email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'
    )
);

CREATE TABLE festival_review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    festival_id INT NOT NULL,
    user_id INT NOT NULL,
    rating_stars INT NOT NULL CHECK (rating_stars >= 1 AND rating_stars <= 5),
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (festival_id) REFERENCES festival(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE festival_amenity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    festival_id INT NOT NULL,
    amenity_type amenity_type NOT NULL,
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

    CONSTRAINT valid_performance_time CHECK (end_time > start_time),
    CONSTRAINT valid_performance_date CHECK (
        performance_date >= CURRENT_DATE
        AND performance_date >= (
            SELECT start_date 
            FROM festival 
            WHERE id = festival_id
        )
        AND performance_date <= (
            SELECT end_date 
            FROM festival 
            WHERE id = festival_id
        )
    )
);

CREATE TABLE ticket_standard_price (
    ticket_type ticket_type PRIMARY KEY,
    standard_price DECIMAL(10,2) NOT NULL CHECK (standard_price >= 0)
);

CREATE TABLE ticket_type (
    id INT AUTO_INCREMENT PRIMARY KEY,
    festival_id INT NOT NULL,
    type ticket_type NOT NULL,
    description TEXT,
    quantity_available INT NOT NULL CHECK (quantity_available >= 0),
    start_sale_date DATETIME NOT NULL,
    end_sale_date DATETIME NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (festival_id) REFERENCES festival(id) ON DELETE CASCADE,
    FOREIGN KEY (type) REFERENCES ticket_standard_price(ticket_type),
    
    CONSTRAINT valid_sale_dates CHECK (
        end_sale_date >= start_sale_date
        AND start_sale_date <= (
            SELECT start_date 
            FROM festival 
            WHERE id = festival_id
        )
    )
);

CREATE TABLE ticket_purchase (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_type_id INT NOT NULL,
    user_id INT NOT NULL,
    payment_method_used payment_method NOT NULL,
    status purchase_status NOT NULL,
    order_number VARCHAR(50) NOT NULL UNIQUE, 
    price_paid DECIMAL(10,2),
    purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    check_in_time DATETIME NOT NULL,
    check_out_time DATETIME NOT NULL,
    qr_code VARCHAR(255),
    FOREIGN KEY (ticket_type_id) REFERENCES ticket_type(id),
    FOREIGN KEY (user_id) REFERENCES user(id),

    CONSTRAINT valid_check_times CHECK (check_out_time >= check_in_time)
);

-- Create a view to easily see ticket types with their prices
CREATE VIEW ticket_type_with_price AS
SELECT 
    tt.*,
    tsp.standard_price as price
FROM 
    ticket_type tt
    JOIN ticket_standard_price tsp ON tt.type = tsp.ticket_type;


-- Create a trigger to automatically set price_paid in ticket_purchase
DELIMITER //

CREATE TRIGGER before_ticket_purchase_insert 
BEFORE INSERT ON ticket_purchase
FOR EACH ROW
BEGIN
    SET NEW.price_paid = get_ticket_price(NEW.ticket_type_id);
END//

DELIMITER ;

-- Indexes for performance optimization

-- Festival table
CREATE INDEX idx_festival_location_dates ON festival(country, city, start_date, end_date);
CREATE INDEX idx_festival_status ON festival(status);  -- Keep for status filtering

-- Festival Review table
CREATE INDEX idx_festival_review_composite ON festival_review(festival_id, user_id, rating_stars);

-- Festival Artist table
CREATE INDEX idx_festival_artist_composite ON festival_artist(festival_id, performance_date, is_headliner);

-- Ticket Type table
CREATE INDEX idx_ticket_type_composite ON ticket_type(festival_id, type, start_sale_date, end_sale_date);

-- Ticket Purchase table
CREATE INDEX idx_ticket_purchase_composite ON ticket_purchase(user_id, status, purchase_date);
