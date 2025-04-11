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
    featured BOOLEAN DEFAULT FALSE,  -- Add a featured column to the papers table
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE  -- Ensures papers are deleted if user is deleted
);

-- Create table for research topic requests
CREATE TABLE IF NOT EXISTS research_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,  -- The ID of the user making the request
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add a researcher score to the users table
ALTER TABLE users ADD COLUMN researcher_score INT DEFAULT 0;

-- Create conferences table
CREATE TABLE IF NOT EXISTS conferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create forums table
CREATE TABLE IF NOT EXISTS forums (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create forum_posts table
CREATE TABLE IF NOT EXISTS forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (forum_id) REFERENCES forums(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table to save papers for users
CREATE TABLE IF NOT EXISTS saved_papers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    paper_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (paper_id) REFERENCES papers(id) ON DELETE CASCADE
);

-- Table to follow researchers
CREATE TABLE IF NOT EXISTS follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    researcher_id INT NOT NULL,  -- Researcher's user ID
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (researcher_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table to recommend papers
CREATE TABLE IF NOT EXISTS recommended_papers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    paper_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (paper_id) REFERENCES papers(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paper_id INT NOT NULL,
    user_id INT NOT NULL,
    feedback TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paper_id) REFERENCES papers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
 CREATE TABLE IF NOT EXISTS citations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paper_id INT NOT NULL,
    doi VARCHAR(255) NOT NULL UNIQUE,
    citation_format TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paper_id) REFERENCES papers(id) ON DELETE CASCADE
);
ALTER TABLE citations MODIFY COLUMN citation_format TEXT DEFAULT 'IEEE';
