-- COMP1841 Student Q&A Forum schema after merging user profile data into accounts.
-- Use this for a fresh database. For an older database that still has a user table,
-- run scratch/migrate_merge_user_into_accounts.php instead.

CREATE TABLE IF NOT EXISTS accounts (
    id int(11) NOT NULL AUTO_INCREMENT,
    username varchar(50) NOT NULL,
    email varchar(100) NOT NULL,
    hashed_password varchar(255) NOT NULL,
    role enum('USER','ADMIN') DEFAULT 'USER',
    display_name varchar(100) DEFAULT NULL,
    bio varchar(255) DEFAULT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (id),
    UNIQUE KEY username (username),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS category (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(100) NOT NULL,
    description text DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS post (
    id int(11) NOT NULL AUTO_INCREMENT,
    author_id int(11) NOT NULL,
    title varchar(255) NOT NULL,
    content varchar(5000) NOT NULL,
    image varchar(255) DEFAULT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (id),
    KEY idx_post_author (author_id),
    CONSTRAINT fk_post_author FOREIGN KEY (author_id) REFERENCES accounts (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS post_category (
    post_id int(11) NOT NULL,
    category_id int(11) NOT NULL,
    PRIMARY KEY (post_id, category_id),
    KEY idx_post (post_id),
    KEY idx_category (category_id),
    CONSTRAINT fk_pc_post FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pc_category FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
