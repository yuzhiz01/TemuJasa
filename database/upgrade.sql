USE `TemuJasa`;

ALTER TABLE orders DROP FOREIGN KEY orders_provider_id_foreign;
ALTER TABLE orders MODIFY provider_id BIGINT UNSIGNED NOT NULL;
ALTER TABLE orders ADD CONSTRAINT orders_provider_id_foreign FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE reviews
  ADD COLUMN service_id BIGINT UNSIGNED NULL AFTER provider_id,
  ADD UNIQUE KEY reviews_order_id_unique (order_id),
  ADD CONSTRAINT reviews_service_id_foreign FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL;

CREATE TABLE payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id BIGINT UNSIGNED NOT NULL,
  method VARCHAR(50) NOT NULL DEFAULT 'transfer',
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  amount INT UNSIGNED NOT NULL,
  proof VARCHAR(255) NULL,
  paid_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX payments_order_id_status_index (order_id, status),
  CONSTRAINT payments_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favorites (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id BIGINT UNSIGNED NOT NULL,
  service_id BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY favorites_customer_service_unique (customer_id, service_id),
  CONSTRAINT favorites_customer_id_foreign FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT favorites_service_id_foreign FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE provider_profiles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  logo VARCHAR(255) NULL,
  description TEXT NULL,
  operating_hours VARCHAR(100) NULL,
  bank_name VARCHAR(100) NULL,
  bank_account VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY provider_profiles_user_id_unique (user_id),
  CONSTRAINT provider_profiles_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE notifications (
  id CHAR(36) PRIMARY KEY,
  type VARCHAR(255) NOT NULL,
  notifiable_type VARCHAR(255) NOT NULL,
  notifiable_id BIGINT UNSIGNED NOT NULL,
  data TEXT NOT NULL,
  read_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX notifications_notifiable_index (notifiable_type, notifiable_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
