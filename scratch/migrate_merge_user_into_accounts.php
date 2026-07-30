<?php
require_once __DIR__ . '/../includes/DatabaseConnection.php';

function migrationValue($pdo, $sql, $parameters = []) {
    $statement = $pdo->prepare($sql);
    $statement->execute($parameters);
    return $statement->fetchColumn();
}

function migrationRows($pdo, $sql, $parameters = []) {
    $statement = $pdo->prepare($sql);
    $statement->execute($parameters);
    return $statement->fetchAll(PDO::FETCH_COLUMN);
}

function tableExists($pdo, $table) {
    return (int)migrationValue(
        $pdo,
        'SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table',
        [':table' => $table]
    ) > 0;
}

function columnExists($pdo, $table, $column) {
    return (int)migrationValue(
        $pdo,
        'SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table AND COLUMN_NAME = :column',
        [':table' => $table, ':column' => $column]
    ) > 0;
}

function foreignKeysToTable($pdo, $table, $referencedTable) {
    return migrationRows(
        $pdo,
        'SELECT DISTINCT CONSTRAINT_NAME
           FROM information_schema.KEY_COLUMN_USAGE
          WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = :table
            AND REFERENCED_TABLE_NAME = :referenced_table',
        [':table' => $table, ':referenced_table' => $referencedTable]
    );
}

function foreignKeyToColumnExists($pdo, $table, $column, $referencedTable, $referencedColumn) {
    return (int)migrationValue(
        $pdo,
        'SELECT COUNT(*)
           FROM information_schema.KEY_COLUMN_USAGE
          WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = :table
            AND COLUMN_NAME = :column
            AND REFERENCED_TABLE_NAME = :referenced_table
            AND REFERENCED_COLUMN_NAME = :referenced_column',
        [
            ':table' => $table,
            ':column' => $column,
            ':referenced_table' => $referencedTable,
            ':referenced_column' => $referencedColumn
        ]
    ) > 0;
}

function runStep($pdo, $sql, $message) {
    $pdo->exec($sql);
    echo $message . "\n";
}

try {
    if (!columnExists($pdo, 'accounts', 'display_name')) {
        runStep($pdo, 'ALTER TABLE accounts ADD COLUMN display_name varchar(100) DEFAULT NULL AFTER role', 'Added accounts.display_name');
    } else {
        echo "accounts.display_name already exists\n";
    }

    if (!columnExists($pdo, 'accounts', 'bio')) {
        runStep($pdo, 'ALTER TABLE accounts ADD COLUMN bio varchar(255) DEFAULT NULL AFTER display_name', 'Added accounts.bio');
    } else {
        echo "accounts.bio already exists\n";
    }

    if (tableExists($pdo, 'user')) {
        runStep(
            $pdo,
            'UPDATE accounts a
                INNER JOIN `user` u ON u.account_id = a.id
                 SET a.display_name = COALESCE(NULLIF(u.display_name, \'\'), a.display_name, a.username),
                     a.bio = COALESCE(NULLIF(u.bio, \'\'), a.bio)',
            'Copied profile data from user into accounts'
        );

        foreach (foreignKeysToTable($pdo, 'post', 'user') as $foreignKey) {
            runStep($pdo, 'ALTER TABLE post DROP FOREIGN KEY `' . str_replace('`', '``', $foreignKey) . '`', 'Dropped old post foreign key ' . $foreignKey);
        }

        runStep(
            $pdo,
            'UPDATE post p
                INNER JOIN `user` u ON p.author_id = u.id
                   SET p.author_id = u.account_id',
            'Repointed post.author_id values to accounts.id'
        );
    } else {
        echo "user table already removed\n";
    }

    runStep(
        $pdo,
        'UPDATE accounts SET display_name = username WHERE display_name IS NULL OR display_name = \'\'',
        'Filled missing display names from usernames'
    );

    $orphanPosts = (int)migrationValue(
        $pdo,
        'SELECT COUNT(*)
           FROM post p
           LEFT JOIN accounts a ON p.author_id = a.id
          WHERE a.id IS NULL'
    );
    if ($orphanPosts > 0) {
        throw new RuntimeException('Cannot add accounts foreign key because ' . $orphanPosts . ' post(s) have no matching account author.');
    }

    if (!foreignKeyToColumnExists($pdo, 'post', 'author_id', 'accounts', 'id')) {
        runStep(
            $pdo,
            'ALTER TABLE post ADD CONSTRAINT fk_post_author FOREIGN KEY (author_id) REFERENCES accounts (id) ON DELETE CASCADE ON UPDATE CASCADE',
            'Added post.author_id foreign key to accounts.id'
        );
    } else {
        echo "post.author_id already references accounts.id\n";
    }

    if (tableExists($pdo, 'user')) {
        runStep($pdo, 'DROP TABLE `user`', 'Dropped old user table');
    }

    echo "Migration complete.\n";
} catch (Throwable $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
