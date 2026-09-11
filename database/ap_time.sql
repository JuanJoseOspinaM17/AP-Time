CREATE DATABASE IF NOT EXISTS ap_time CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ap_time;

CREATE TABLE IF NOT EXISTS admins (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(80) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(160) NOT NULL,
 price DECIMAL(12,2) NOT NULL DEFAULT 0,
 availability ENUM('contra_entrega','encargo') NOT NULL DEFAULT 'contra_entrega',
 status ENUM('active','inactive') NOT NULL DEFAULT 'active',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 INDEX idx_products_status (status),
 INDEX idx_products_availability (availability)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS product_images (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 product_id INT UNSIGNED NOT NULL,
 image VARCHAR(255) NOT NULL,
 is_main TINYINT(1) NOT NULL DEFAULT 0,
 sort_order INT NOT NULL DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_product_images_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
 INDEX idx_images_product (product_id,is_main,sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuario inicial del panel. Puedes cambiarlo después desde la base de datos.
INSERT INTO admins (username,password)
SELECT 'admin', '$2y$12$u2zdFM98XGeuhbfQmukCVu2OZKSqHF0QNiM6EurNMVpZnb5eFGMoS'
WHERE NOT EXISTS (SELECT 1 FROM admins WHERE username='admin');
