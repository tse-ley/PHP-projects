CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
UPDATE users SET is_admin = TRUE WHERE username = 'root';

);

CREATE TABLE complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in progress', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE complaints ADD COLUMN feedback TEXT;
ALTER TABLE complaints ADD COLUMN feedback_date TIMESTAMP;

