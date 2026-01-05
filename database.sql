-- database set up

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    featured_image VARCHAR(255),
    category_id INT NOT NULL,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE CASCADE
);

--------------------------------------------------
-- create sample data
INSERT INTO categories (name, slug)
VALUES ('Japan', 'japan');

INSERT INTO posts (title, slug, content, category_id, status)
VALUES (
  'My First Trip to Japan',
  'my-first-trip-to-japan',
  'This is my first travel post content.',
  1,
  'published'
);

INSERT INTO posts (title, slug, content, category_id, status)
VALUES (
  'My First Trip to Cebu',
  'my-first-trip-to-cebu',
  'This is my second travel post content.',
  1,
  'published'
);