CREATE DATABASE IF NOT EXISTS taskmanager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE taskmanager;

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    created_at DATETIME      DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_settings (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT          NOT NULL UNIQUE,
    theme         VARCHAR(20)  DEFAULT 'light',
    language      VARCHAR(10)  DEFAULT 'nl',
    notifications TINYINT(1)   DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT          NOT NULL,
    name       VARCHAR(100) NOT NULL,
    color      VARCHAR(7)   DEFAULT '#3b82f6',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tasks (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    category_id INT          NULL,
    title       VARCHAR(255) NOT NULL,
    description TEXT         NULL,
    priority    ENUM('laag','normaal','hoog') DEFAULT 'normaal',
    status      ENUM('open','bezig','gedaan') DEFAULT 'open',
    deadline    DATE         NULL,
    created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES users(id)      ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS task_logs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    task_id     INT          NOT NULL,
    old_status  VARCHAR(20)  NOT NULL,
    new_status  VARCHAR(20)  NOT NULL,
    changed_at  DATETIME     DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(50)  NOT NULL,
    color VARCHAR(7)   DEFAULT '#6b7280'
);

CREATE TABLE IF NOT EXISTS task_tag (
    task_id INT NOT NULL,
    tag_id  INT NOT NULL,
    PRIMARY KEY (task_id, tag_id),
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id)  REFERENCES tags(id)  ON DELETE CASCADE
);

INSERT INTO users (name, email, password) VALUES
('Demo Gebruiker', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO user_settings (user_id, theme, language, notifications) VALUES
(1, 'light', 'nl', 1);

INSERT INTO categories (user_id, name, color) VALUES
(1, 'Werk',   '#3b82f6'),
(1, 'Privé',  '#10b981'),
(1, 'School', '#f59e0b');

INSERT INTO tasks (user_id, category_id, title, description, priority, status, deadline) VALUES
(1, 1, 'Project rapport afmaken',  'Eindrapport voor Q2 opleveren', 'hoog',    'bezig', '2025-04-01'),
(1, 2, 'Boodschappen doen',        NULL,                            'laag',    'open',  NULL),
(1, 3, 'PHP opdracht inleveren',   'Taakbeheer applicatie bouwen',  'hoog',    'bezig', '2025-03-28'),
(1, 1, 'E-mails beantwoorden',     NULL,                            'normaal', 'open',  NULL),
(1, 3, 'Database schema tekenen',  'ERD voor schoolproject',        'normaal', 'gedaan',NULL);

INSERT INTO task_logs (task_id, old_status, new_status) VALUES
(1, 'open',  'bezig'),
(3, 'open',  'bezig'),
(5, 'open',  'bezig'),
(5, 'bezig', 'gedaan');

INSERT INTO tags (name, color) VALUES
('urgent',   '#dc2626'),
('school',   '#f59e0b'),
('werk',     '#3b82f6');

INSERT INTO task_tag (task_id, tag_id) VALUES
(1, 1),
(1, 3),
(3, 1),
(3, 2),
(5, 2);