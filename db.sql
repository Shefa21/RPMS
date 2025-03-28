-- Create the database
CREATE DATABASE IF NOT EXISTS rpms;

-- Use the created database
USE rpms;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('admin', 'researcher', 'viewer') NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Added timestamp for account creation
);

-- Create papers table
CREATE TABLE IF NOT EXISTS papers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    authors TEXT NOT NULL,
    category VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    publication_date DATE NOT NULL,
    abstract TEXT,
    user_id INT NOT NULL,  -- Added user_id for associating papers with users
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Added timestamp for paper creation
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE  -- Ensures papers are deleted if user is deleted
);

