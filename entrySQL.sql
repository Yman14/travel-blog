-- database set up

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','editor') DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
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

CREATE TABLE post_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_post_id (post_id),
    CONSTRAINT fk_post_images_post
        FOREIGN KEY (post_id)
        REFERENCES posts(id)
        ON DELETE CASCADE
);

CREATE TABLE settings (
    `key` VARCHAR(50) PRIMARY KEY,
    `value` TEXT NOT NULL
);

CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45),
    attempted_at DATETIME
);

-- Cannot delete category if posts exist
ALTER TABLE posts
ADD CONSTRAINT fk_category
FOREIGN KEY (category_id)
REFERENCES categories(id)
ON DELETE RESTRICT;

-- add for SEO
ALTER TABLE posts
ADD meta_title VARCHAR(255) NULL,
ADD meta_description VARCHAR(255) NULL;


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

INSERT INTO settings (`key`, `value`) VALUES
('hero_title', 'Explore the World'),
('hero_subtitle', 'Stories, guides, and travel inspiration'),
('contact_email', 'hello@example.com'),
('contact_number', '0912 345 6789'),
('facebook', ''),
('instagram', ''),
('twitter', ''),
('website_name', 'Lily'),
('site_author', 'Lily');



--------------------------------------------------
--