<?php
function query($pdo, $sql, $parameters = []) {
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
    return $query;
}

// ---- Accounts & Authentication ----

function registerAccount($pdo, $username, $email, $password, $role = 'USER', $display_name = null, $bio = '') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $display_name = trim($display_name ?? '');
    if ($display_name === '') {
        $display_name = $username;
    }

    $parameters = [
        ':username' => $username,
        ':email' => $email,
        ':password' => $hash,
        ':role' => $role,
        ':display_name' => $display_name,
        ':bio' => $bio
    ];
    query($pdo, 'INSERT INTO accounts (username, email, hashed_password, role, display_name, bio) VALUES (:username, :email, :password, :role, :display_name, :bio)', $parameters);
    return $pdo->lastInsertId();
}

function getAccountByEmail($pdo, $email) {
    $query = query($pdo, 'SELECT * FROM accounts WHERE email = :email', [':email' => $email]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getAccountById($pdo, $id) {
    $query = query($pdo, 'SELECT * FROM accounts WHERE id = :id', [':id' => $id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function allAccounts($pdo) {
    $query = query($pdo, 'SELECT * FROM accounts ORDER BY created_at DESC');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function deleteAccount($pdo, $id) {
    query($pdo, 'DELETE FROM accounts WHERE id = :id', [':id' => $id]);
}

function updateAccountRole($pdo, $id, $role) {
    query($pdo, 'UPDATE accounts SET role = :role WHERE id = :id', [':id' => $id, ':role' => $role]);
}

function updateAccountProfile($pdo, $id, $display_name, $bio = '') {
    $parameters = [
        ':id' => $id,
        ':display_name' => $display_name,
        ':bio' => $bio
    ];
    query($pdo, 'UPDATE accounts SET display_name = :display_name, bio = :bio WHERE id = :id', $parameters);
}

// ---- Categories (Modules) ----

function allCategories($pdo) {
    $query = query($pdo, 'SELECT * FROM category ORDER BY name ASC');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function getCategory($pdo, $id) {
    $query = query($pdo, 'SELECT * FROM category WHERE id = :id', [':id' => $id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

function insertCategory($pdo, $name, $description = '') {
    $parameters = [
        ':name' => $name,
        ':description' => $description
    ];
    query($pdo, 'INSERT INTO category (name, description) VALUES (:name, :description)', $parameters);
}

function updateCategory($pdo, $id, $name, $description = '') {
    $parameters = [
        ':id' => $id,
        ':name' => $name,
        ':description' => $description
    ];
    query($pdo, 'UPDATE category SET name = :name, description = :description WHERE id = :id', $parameters);
}

function deleteCategory($pdo, $id) {
    query($pdo, 'DELETE FROM category WHERE id = :id', [':id' => $id]);
}

// ---- Posts (Questions) ----

function allPosts($pdo, $category_id = null) {
    $parameters = [];
    $sql = 'SELECT DISTINCT p.*, COALESCE(NULLIF(a.display_name, \'\'), a.username) as author_name, a.username as author_username 
            FROM post p 
            INNER JOIN accounts a ON p.author_id = a.id';

    if (!empty($category_id)) {
        $sql .= ' INNER JOIN post_category pc_filter ON p.id = pc_filter.post_id AND pc_filter.category_id = :category_id';
        $parameters[':category_id'] = $category_id;
    }

    $sql .= '
            ORDER BY p.created_at DESC';
    $query = query($pdo, $sql, $parameters);
    $posts = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch categories for posts
    foreach ($posts as &$post) {
        $post['categories'] = getPostCategories($pdo, $post['id']);
    }
    return $posts;
}

function getPostCategories($pdo, $post_id) {
    $sql = 'SELECT c.* FROM category c 
            INNER JOIN post_category pc ON c.id = pc.category_id 
            WHERE pc.post_id = :post_id';
    $query = query($pdo, $sql, [':post_id' => $post_id]);
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function totalPosts($pdo, $category_id = null) {
    $parameters = [];
    $sql = 'SELECT COUNT(DISTINCT p.id) FROM post p';

    if (!empty($category_id)) {
        $sql .= ' INNER JOIN post_category pc_filter ON p.id = pc_filter.post_id AND pc_filter.category_id = :category_id';
        $parameters[':category_id'] = $category_id;
    }

    $query = query($pdo, $sql, $parameters);
    $row = $query->fetch();
    return $row[0];
}

function getPost($pdo, $id) {
    $sql = 'SELECT p.*, COALESCE(NULLIF(a.display_name, \'\'), a.username) as author_name, a.username as author_username 
            FROM post p 
            INNER JOIN accounts a ON p.author_id = a.id
            WHERE p.id = :id';
    $query = query($pdo, $sql, [':id' => $id]);
    $post = $query->fetch(PDO::FETCH_ASSOC);
    if ($post) {
        $post['categories'] = getPostCategories($pdo, $post['id']);
    }
    return $post;
}

function insertPost($pdo, $author_id, $title, $content, $image, $category_ids = []) {
    $parameters = [
        ':author_id' => $author_id,
        ':title' => $title,
        ':content' => $content,
        ':image' => $image
    ];
    query($pdo, 'INSERT INTO post (author_id, title, content, image) VALUES (:author_id, :title, :content, :image)', $parameters);
    $post_id = $pdo->lastInsertId();
    
    foreach ($category_ids as $cat_id) {
        if (!empty($cat_id)) {
            query($pdo, 'INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)', [
                ':post_id' => $post_id,
                ':category_id' => $cat_id
            ]);
        }
    }
    return $post_id;
}

function updatePost($pdo, $id, $title, $content, $image, $category_ids = []) {
    $parameters = [
        ':id' => $id,
        ':title' => $title,
        ':content' => $content
    ];
    
    $sql = 'UPDATE post SET title = :title, content = :content';
    if ($image !== null) {
        $sql .= ', image = :image';
        $parameters[':image'] = $image;
    }
    $sql .= ' WHERE id = :id';
    
    query($pdo, $sql, $parameters);
    
    // Update categories: clear and re-insert
    query($pdo, 'DELETE FROM post_category WHERE post_id = :post_id', [':post_id' => $id]);
    foreach ($category_ids as $cat_id) {
        if (!empty($cat_id)) {
            query($pdo, 'INSERT INTO post_category (post_id, category_id) VALUES (:post_id, :category_id)', [
                ':post_id' => $id,
                ':category_id' => $cat_id
            ]);
        }
    }
}

function deletePost($pdo, $id) {
    query($pdo, 'DELETE FROM post WHERE id = :id', [':id' => $id]);
}
