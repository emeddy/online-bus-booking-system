-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS codecraft;

-- Switch to the 'codecraft' database
USE codecraft;

-- Create the 'users' table to store user information
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL -- You should use a stronger hashing method in a production environment
);

-- Create the 'seats' table to store parking spot information
CREATE TABLE IF NOT EXISTS seats (
    seat_number INT AUTO_INCREMENT PRIMARY KEY,
    is_available BOOLEAN DEFAULT 1, -- 1 means available, 0 means occupied
    user_id INT, -- Foreign key referencing the 'id' column in the 'users' table
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
