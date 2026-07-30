<?php
require_once __DIR__ . '/../includes/DatabaseConnection.php';
require_once __DIR__ . '/../includes/DataBaseFunctions.php';

$accounts = [
    [
        'username' => 'admin',
        'email' => 'admin@example.com',
        'password' => 'admin123',
        'role' => 'ADMIN',
        'display_name' => 'Admin User',
        'bio' => 'Administrator account for managing users and modules.'
    ],
    [
        'username' => 'dghung',
        'email' => 'hungdangmcn@gmail.com',
        'password' => 'dghung123',
        'role' => 'USER',
        'display_name' => 'Dg Hung',
        'bio' => 'Coursework owner and demo student account.'
    ],
    [
        'username' => 'an_nguyen',
        'email' => 'an.nguyen@studentqa.local',
        'password' => 'student123',
        'role' => 'USER',
        'display_name' => 'An Nguyen',
        'bio' => 'First year computing student.'
    ],
    [
        'username' => 'linh_tran',
        'email' => 'linh.tran@studentqa.local',
        'password' => 'student123',
        'role' => 'USER',
        'display_name' => 'Linh Tran',
        'bio' => 'Interested in databases and clean UI design.'
    ],
    [
        'username' => 'minh_pham',
        'email' => 'minh.pham@studentqa.local',
        'password' => 'student123',
        'role' => 'USER',
        'display_name' => 'Minh Pham',
        'bio' => 'Practising PHP, MySQL, and coursework documentation.'
    ]
];

$categories = [
    'COMP1841' => 'Web Programming 1 - PHP and MySQL',
    'COMP1842' => 'Software Development Principles',
    'COMP1843' => 'Database Systems',
    'COMP1755' => 'Programming Fundamentals',
    'COMP1640' => 'Enterprise Web Software Development',
    'COMP1770' => 'Professional Practice in IT'
];

$posts = [
    [
        'author' => 'dghung',
        'title' => 'How should I structure PHP includes for this coursework?',
        'content' => 'I have separate files for the layout, database connection, and helper functions. Is this a good structure for a small PHP coursework project, or should I split it further?',
        'categories' => ['COMP1841'],
        'created_at' => '2026-07-21 09:15:00'
    ],
    [
        'author' => 'linh_tran',
        'title' => 'PDO prepared statement returns no rows even though data exists',
        'content' => 'The SQL works in phpMyAdmin, but my PHP page returns an empty array. I am binding a category id from the query string. What should I check first?',
        'categories' => ['COMP1841', 'COMP1843'],
        'created_at' => '2026-07-21 14:40:00'
    ],
    [
        'author' => 'an_nguyen',
        'title' => 'When should I use a composite primary key?',
        'content' => 'For the post_category table, I see that post_id and category_id are used together as the primary key. Why is this better than adding a separate id column?',
        'categories' => ['COMP1843'],
        'created_at' => '2026-07-22 10:20:00'
    ],
    [
        'author' => 'minh_pham',
        'title' => 'How do I normalize forum data without overcomplicating the schema?',
        'content' => 'I want to explain normalization in my report using accounts, posts, categories, and the post_category bridge table. What is a simple way to describe it?',
        'categories' => ['COMP1842', 'COMP1843'],
        'created_at' => '2026-07-22 16:05:00'
    ],
    [
        'author' => 'dghung',
        'title' => 'Best way to break a coursework task into smaller functions',
        'content' => 'My post page is getting longer as I add filtering and CRUD features. Which parts should be moved into reusable functions?',
        'categories' => ['COMP1755', 'COMP1842'],
        'created_at' => '2026-07-23 11:30:00'
    ],
    [
        'author' => 'an_nguyen',
        'title' => 'How do I plan MVC-style pages in plain PHP?',
        'content' => 'This project is not using a framework, but I still want the code to be clear. Is it okay to keep controller logic in PHP entry files and views in templates?',
        'categories' => ['COMP1841', 'COMP1842', 'COMP1640'],
        'created_at' => '2026-07-24 09:10:00'
    ],
    [
        'author' => 'linh_tran',
        'title' => 'What should a short coursework reflection include?',
        'content' => 'I need to write about what went well, what was difficult, and what could be improved. How much technical detail should be included?',
        'categories' => ['COMP1770'],
        'created_at' => '2026-07-24 15:35:00'
    ],
    [
        'author' => 'minh_pham',
        'title' => 'How can I test role-based access control?',
        'content' => 'The admin area should only be available to ADMIN users. What manual tests should I include as evidence in my report?',
        'categories' => ['COMP1640', 'COMP1842'],
        'created_at' => '2026-07-25 12:00:00'
    ],
    [
        'author' => 'dghung',
        'title' => 'Loop through an array of modules and show selected checkboxes',
        'content' => 'On the edit post page, I want modules that are already assigned to a post to be checked automatically. What is the cleanest approach?',
        'categories' => ['COMP1755', 'COMP1841'],
        'created_at' => '2026-07-26 10:45:00'
    ],
    [
        'author' => 'admin',
        'title' => 'How should security decisions be described in the final report?',
        'content' => 'The project uses password_hash, password_verify, PDO prepared statements, and role checks. Which parts should be highlighted for marking?',
        'categories' => ['COMP1640', 'COMP1770'],
        'created_at' => '2026-07-26 17:20:00'
    ]
];

function accountIdByUsername($pdo, $username) {
    $statement = $pdo->prepare('SELECT id FROM accounts WHERE username = :username');
    $statement->execute([':username' => $username]);
    return $statement->fetchColumn();
}

function removeUploadFiles($uploadsDir) {
    $resolved = realpath($uploadsDir);
    $workspace = realpath(__DIR__ . '/..');

    if ($resolved === false || $workspace === false || strpos($resolved, $workspace) !== 0) {
        throw new RuntimeException('Uploads directory is not inside the project workspace.');
    }

    foreach (glob($resolved . DIRECTORY_SEPARATOR . '*') as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}

$pdo->beginTransaction();

try {
    query($pdo, 'DELETE FROM post_category');
    query($pdo, 'DELETE FROM post');
    query($pdo, 'DELETE FROM category');

    $keepUsernames = array_column($accounts, 'username');
    $placeholders = implode(',', array_fill(0, count($keepUsernames), '?'));
    $deleteStatement = $pdo->prepare("DELETE FROM accounts WHERE username NOT IN ($placeholders)");
    $deleteStatement->execute($keepUsernames);

    foreach ($accounts as $account) {
        $existingId = accountIdByUsername($pdo, $account['username']);
        $hash = password_hash($account['password'], PASSWORD_DEFAULT);

        if ($existingId) {
            query(
                $pdo,
                'UPDATE accounts
                    SET email = :email,
                        hashed_password = :hashed_password,
                        role = :role,
                        display_name = :display_name,
                        bio = :bio
                  WHERE username = :username',
                [
                    ':username' => $account['username'],
                    ':email' => $account['email'],
                    ':hashed_password' => $hash,
                    ':role' => $account['role'],
                    ':display_name' => $account['display_name'],
                    ':bio' => $account['bio']
                ]
            );
        } else {
            registerAccount(
                $pdo,
                $account['username'],
                $account['email'],
                $account['password'],
                $account['role'],
                $account['display_name'],
                $account['bio']
            );
        }
    }

    $categoryIds = [];
    foreach ($categories as $name => $description) {
        query($pdo, 'INSERT INTO category (name, description) VALUES (:name, :description)', [
            ':name' => $name,
            ':description' => $description
        ]);
        $categoryIds[$name] = $pdo->lastInsertId();
    }

    foreach ($posts as $post) {
        $authorId = accountIdByUsername($pdo, $post['author']);
        query(
            $pdo,
            'INSERT INTO post (author_id, title, content, image, created_at, updated_at)
             VALUES (:author_id, :title, :content, NULL, :created_at, :created_at)',
            [
                ':author_id' => $authorId,
                ':title' => $post['title'],
                ':content' => $post['content'],
                ':created_at' => $post['created_at']
            ]
        );
        $postId = $pdo->lastInsertId();

        foreach ($post['categories'] as $categoryName) {
            query($pdo, 'INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)', [
                ':post_id' => $postId,
                ':category_id' => $categoryIds[$categoryName]
            ]);
        }
    }

    $pdo->commit();
    removeUploadFiles(__DIR__ . '/../uploads');

    echo "Demo database seeded successfully.\n";
    echo "Accounts: " . count($accounts) . "\n";
    echo "Categories: " . count($categories) . "\n";
    echo "Posts: " . count($posts) . "\n";
    echo "Upload files removed.\n";
} catch (Throwable $e) {
    $pdo->rollBack();
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
