
CREATE DATABASE IF NOT EXISTS mini_sweeters;
USE mini_sweeters;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100),
 email VARCHAR(100) UNIQUE,
 password VARCHAR(255)
);

CREATE TABLE tweets (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 content VARCHAR(280),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE likes (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 tweet_id INT
);

CREATE TABLE comments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 tweet_id INT,
 comment TEXT
);

CREATE TABLE follows (
 id INT AUTO_INCREMENT PRIMARY KEY,
 follower_id INT,
 following_id INT
);
