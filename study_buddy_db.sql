-- ============================================================
-- Database StudyBuddy
-- Cara pakai: phpMyAdmin -> tab Import -> pilih file ini -> Go
-- (file ini otomatis membuat database study_buddy_db)
-- ============================================================

CREATE DATABASE IF NOT EXISTS study_buddy_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE study_buddy_db;

-- ------------------------------------------------------------
-- Tabel users
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,           -- hasil password_hash()
  avatar     VARCHAR(500) DEFAULT NULL,
  major      VARCHAR(100) DEFAULT NULL,       -- jurusan
  semester   INT          DEFAULT NULL,
  bio        TEXT         DEFAULT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel study_groups
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS study_groups (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  group_name  VARCHAR(150) NOT NULL,
  description TEXT         DEFAULT NULL,
  created_by  INT          NOT NULL,          -- id user pembuat (admin grup)
  created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_group_creator
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel group_members
-- status: 'pending' (menunggu di-acc) atau 'active' (sudah anggota)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS group_members (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  group_id  INT NOT NULL,
  user_id   INT NOT NULL,
  status    ENUM('pending','active') NOT NULL DEFAULT 'pending',
  joined_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_member_group
    FOREIGN KEY (group_id) REFERENCES study_groups(id) ON DELETE CASCADE,
  CONSTRAINT fk_member_user
    FOREIGN KEY (user_id)  REFERENCES users(id)        ON DELETE CASCADE,
  UNIQUE KEY uq_group_user (group_id, user_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel messages (chat)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS messages (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  group_id INT NOT NULL,
  user_id  INT NOT NULL,
  message  TEXT NOT NULL,
  sent_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_msg_group
    FOREIGN KEY (group_id) REFERENCES study_groups(id) ON DELETE CASCADE,
  CONSTRAINT fk_msg_user
    FOREIGN KEY (user_id)  REFERENCES users(id)        ON DELETE CASCADE
) ENGINE=InnoDB;
