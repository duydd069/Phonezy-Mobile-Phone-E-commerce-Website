
-- phonezy_seed.sql
-- Bootstrap schema + sample data for phonezy_db (MySQL 8+)
-- Engine: InnoDB, Charset: utf8mb4

DROP DATABASE IF EXISTS phonezy_db;
CREATE DATABASE phonezy_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE phonezy_db;

-- -----------------------------
-- Core reference tables
-- -----------------------------

CREATE TABLE roles (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(30),
  role_id BIGINT NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE categories (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE brands (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  logo VARCHAR(255),
  slug VARCHAR(150) NOT NULL UNIQUE,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Warehouses & Inventory
CREATE TABLE warehouses (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  location VARCHAR(255),
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Products & variants
CREATE TABLE products (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL,
  image VARCHAR(255),
  price DECIMAL(12,0) NOT NULL,
  slug VARCHAR(200) NOT NULL UNIQUE,
  description LONGTEXT,
  gender ENUM('male','female','unisex') DEFAULT 'unisex',
  category_id BIGINT NOT NULL,
  brand_id BIGINT NOT NULL,
  views BIGINT DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id),
  CONSTRAINT fk_products_brand FOREIGN KEY (brand_id) REFERENCES brands(id)
) ENGINE=InnoDB;

CREATE TABLE product_variants (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  product_id BIGINT NOT NULL,
  price DECIMAL(12,0) NOT NULL,
  price_sale DECIMAL(12,0),
  stock INT NOT NULL DEFAULT 0,
  sold INT NOT NULL DEFAULT 0,
  sku VARCHAR(100) NOT NULL UNIQUE,
  barcode VARCHAR(100),
  description TEXT,
  status ENUM('available','out_of_stock','discontinued') DEFAULT 'available',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_variants_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE product_images (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  product_id BIGINT NOT NULL,
  image_url VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_images_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- Warehouse stock for variants
CREATE TABLE warehouse_stock (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  warehouse_id BIGINT NOT NULL,
  product_variant_id BIGINT NOT NULL,
  stock_quantity INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_wstock_wh FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
  CONSTRAINT fk_wstock_variant FOREIGN KEY (product_variant_id) REFERENCES product_variants(id),
  UNIQUE KEY uq_wh_variant (warehouse_id, product_variant_id)
) ENGINE=InnoDB;

CREATE TABLE inventory_logs (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  warehouse_id BIGINT NOT NULL,
  product_variant_id BIGINT NOT NULL,
  quantity_change INT NOT NULL, -- positive=in, negative=out
  type ENUM('stock_in','stock_out') NOT NULL,
  reason TEXT,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_il_wh FOREIGN KEY (warehouse_id) REFERENCES warehouses(id),
  CONSTRAINT fk_il_variant FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
) ENGINE=InnoDB;

-- Carts
CREATE TABLE carts (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  status ENUM('active','abandoned','converted') DEFAULT 'active',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE cart_items (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  cart_id BIGINT NOT NULL,
  product_variant_id BIGINT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_citems_cart FOREIGN KEY (cart_id) REFERENCES carts(id),
  CONSTRAINT fk_citems_variant FOREIGN KEY (product_variant_id) REFERENCES product_variants(id),
  UNIQUE KEY uq_cart_variant (cart_id, product_variant_id)
) ENGINE=InnoDB;

-- Orders
CREATE TABLE coupons (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(50) NOT NULL UNIQUE,
  discount_type ENUM('percent','fixed') NOT NULL,
  discount_value DECIMAL(10,2) NOT NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE orders (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  user_id BIGINT NOT NULL,
  coupon_id BIGINT NULL,
  total_price DECIMAL(12,2) NOT NULL,
  status ENUM('pending','processing','completed','cancelled') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_orders_coupon FOREIGN KEY (coupon_id) REFERENCES coupons(id)
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  product_variant_id BIGINT NOT NULL,
  quantity INT NOT NULL,
  price_each DECIMAL(12,2) NOT NULL,
  variant_sku VARCHAR(100),
  variant_volume_ml INT,
  variant_description TEXT,
  variant_status ENUM('available','out_of_stock','discontinued'),
  variant_name VARCHAR(200),
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_oitems_order FOREIGN KEY (order_id) REFERENCES orders(id),
  CONSTRAINT fk_oitems_variant FOREIGN KEY (product_variant_id) REFERENCES product_variants(id)
) ENGINE=InnoDB;

CREATE TABLE shipping_tracking (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  status ENUM('pending','shipped','in_transit','delivered','failed') DEFAULT 'pending',
  tracking_number VARCHAR(100),
  shipped_at TIMESTAMP NULL,
  delivered_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_ship_order FOREIGN KEY (order_id) REFERENCES orders(id)
) ENGINE=InnoDB;

CREATE TABLE order_returns (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_item_id BIGINT NOT NULL,
  reason TEXT,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_returns_item FOREIGN KEY (order_item_id) REFERENCES order_items(id)
) ENGINE=InnoDB;

CREATE TABLE payments (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  payment_method ENUM('cash','momo','vnpay') NOT NULL,
  payment_status ENUM('pending','paid','failed') DEFAULT 'pending',
  paid_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_pay_order FOREIGN KEY (order_id) REFERENCES orders(id)
) ENGINE=InnoDB;

-- Indexes for search
CREATE INDEX idx_products_name ON products (name);
CREATE INDEX idx_products_slug ON products (slug);
CREATE INDEX idx_variants_sku ON product_variants (sku);
CREATE INDEX idx_orders_status ON orders (status);

-- -----------------------------
-- Seed data
-- -----------------------------

-- roles
INSERT INTO roles (name) VALUES ('admin'), ('customer');

-- users (passwords are placeholders/hashed elsewhere)
INSERT INTO users (name,email,password_hash,phone,role_id) VALUES
('Admin', 'admin@phonezy.local', '$2y$10$hash_admin', '0900000000', 1),
('Nguyen Van A', 'a@example.com', '$2y$10$hash_user_a', '0911111111', 2);

-- categories
INSERT INTO categories (name, slug) VALUES
('Điện thoại', 'dien-thoai'),
('Máy tính bảng', 'may-tinh-bang'),
('Phụ kiện', 'phu-kien'),
('Âm thanh', 'am-thanh'),
('Đồng hồ', 'dong-ho');

-- brands
INSERT INTO brands (name, logo, slug) VALUES
('Apple', 'https://example.com/apple.png', 'apple'),
('Samsung', 'https://example.com/samsung.png', 'samsung'),
('Xiaomi', 'https://example.com/xiaomi.png', 'xiaomi'),
('OPPO', 'https://example.com/oppo.png', 'oppo');

-- warehouses
INSERT INTO warehouses (name, location) VALUES
('Kho Tổng HCM', 'Quận 7, TP.HCM'),
('Kho Hà Nội', 'Cầu Giấy, Hà Nội');

-- products
INSERT INTO products (name,image,price,slug,description,gender,category_id,brand_id,views) VALUES
('iPhone 15 Pro 256GB', 'https://example.com/ip15pro.jpg', 27990000, 'iphone-15-pro-256gb', 'Flagship Apple', 'unisex', 1, 1, 120),
('Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', 20990000, 'samsung-galaxy-s24-256gb', 'Flagship Samsung', 'unisex', 1, 2, 90),
('Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 5990000, 'xiaomi-redmi-note-13', 'Giá tốt', 'unisex', 1, 3, 80),
('OPPO Reno 12', 'https://example.com/reno12.jpg', 12990000, 'oppo-reno-12', 'Thiết kế đẹp', 'unisex', 1, 4, 65),
('iPad Air M2 11"', 'https://example.com/ipadair-m2.jpg', 16990000, 'ipad-air-m2-11', 'iPad Air chip M2', 'unisex', 2, 1, 40),
('AirPods Pro 2 USB‑C', 'https://example.com/airpodspro2.jpg', 5290000, 'airpods-pro-2', 'Tai nghe chống ồn', 'unisex', 4, 1, 150);

-- product_variants
INSERT INTO product_variants (product_id,price,price_sale,stock,sold,sku,barcode,description,status) VALUES
(1, 27990000, 26990000, 20, 5, 'IP15P-256-NAT', '1111111111111', 'Titan tự nhiên 256GB', 'available'),
(1, 27990000, NULL, 15, 2, 'IP15P-256-BLK', '1111111111112', 'Titan đen 256GB', 'available'),
(2, 20990000, 19990000, 30, 10, 'S24-256-BLK', '2222222222221', 'Đen 256GB', 'available'),
(2, 20990000, NULL, 25, 6, 'S24-256-VIO', '2222222222222', 'Tím 256GB', 'available'),
(3, 5990000, 5490000, 40, 20, 'RN13-128-BLU', '3333333333331', 'Xanh 128GB', 'available'),
(4, 12990000, NULL, 18, 4, 'RENO12-256-SLV', '4444444444441', 'Bạc 256GB', 'available'),
(5, 16990000, NULL, 10, 1, 'IPAD-AIR-M2-11-128', '5555555555551', 'M2 11-inch 128GB', 'available'),
(6, 5290000, 4990000, 50, 35, 'APPRO2-USBC', '6666666666661', 'AirPods Pro 2 USB-C', 'available');

-- product_images
INSERT INTO product_images (product_id, image_url) VALUES
(1, 'https://example.com/ip15pro_1.jpg'),
(1, 'https://example.com/ip15pro_2.jpg'),
(2, 'https://example.com/s24_1.jpg'),
(3, 'https://example.com/rn13_1.jpg'),
(4, 'https://example.com/reno12_1.jpg'),
(5, 'https://example.com/ipadair_1.jpg'),
(6, 'https://example.com/airpodspro2_1.jpg');

-- warehouse_stock
INSERT INTO warehouse_stock (warehouse_id, product_variant_id, stock_quantity) VALUES
(1, 1, 10), (1, 2, 8), (1, 3, 15), (1, 5, 20), (1, 8, 25),
(2, 1, 10), (2, 4, 12), (2, 6, 9),  (2, 7, 6),  (2, 8, 25);

-- inventory_logs
INSERT INTO inventory_logs (warehouse_id, product_variant_id, quantity_change, type, reason) VALUES
(1, 1, 10, 'stock_in', 'Nhập đầu kỳ'),
(1, 2, 8,  'stock_in', 'Nhập đầu kỳ'),
(1, 3, 15, 'stock_in', 'Nhập đầu kỳ'),
(2, 4, 12, 'stock_in', 'Nhập đầu kỳ'),
(2, 6, 9,  'stock_in', 'Nhập đầu kỳ'),
(1, 1, -2, 'stock_out', 'Bán lẻ đơn #1');

-- coupons
INSERT INTO coupons (code, discount_type, discount_value, expires_at) VALUES
('NEW10', 'percent', 10.00, DATE_ADD(NOW(), INTERVAL 30 DAY)),
('SALE100K', 'fixed', 100000.00, DATE_ADD(NOW(), INTERVAL 15 DAY));

-- carts + items
INSERT INTO carts (user_id, status) VALUES (2, 'active');

INSERT INTO cart_items (cart_id, product_variant_id, quantity) VALUES
(1, 5, 2), -- Redmi Note 13
(1, 8, 1); -- AirPods Pro 2

-- orders + items
INSERT INTO orders (user_id, coupon_id, total_price, status, notes) VALUES
(2, 1,  (2*5490000 + 4990000) * 0.9, 'processing', 'Giao trong giờ hành chính');

INSERT INTO order_items (order_id, product_variant_id, quantity, price_each, variant_sku, variant_description)
SELECT 1, 5, 2, 5490000, 'RN13-128-BLU', 'Xanh 128GB'
UNION ALL
SELECT 1, 8, 1, 4990000, 'APPRO2-USBC', 'AirPods Pro 2 USB-C';

INSERT INTO shipping_tracking (order_id, status, tracking_number, shipped_at)
VALUES (1, 'shipped', 'VNPOST-ABC123', NOW());

INSERT INTO payments (order_id, payment_method, payment_status, paid_at)
VALUES (1, 'momo', 'paid', NOW());

-- returns (example pending request)
INSERT INTO order_returns (order_item_id, reason, status) VALUES
(2, 'Hàng lỗi mic', 'pending');

-- Convenience Views (optional)
CREATE OR REPLACE VIEW v_product_stock AS
SELECT
  pv.id AS variant_id,
  p.name AS product_name,
  pv.sku,
  SUM(ws.stock_quantity) AS total_stock
FROM product_variants pv
JOIN products p ON p.id = pv.product_id
LEFT JOIN warehouse_stock ws ON ws.product_variant_id = pv.id
GROUP BY pv.id, p.name, pv.sku;

-- Finished.
