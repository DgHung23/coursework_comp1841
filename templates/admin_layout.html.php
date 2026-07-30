<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../style.css">
    <title><?=$title ?? 'Admin Area'?></title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="brand">Admin Dashboard</a>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="users.php">Manage Users</a></li>
                <li><a href="categories.php">Manage Modules</a></li>
                <li><a href="../index.php">Public Site</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-msg"><?=$_SESSION['success']?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-msg"><?=$_SESSION['error']?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?=$output ?? ''?>
    </main>
    <footer>
        <p>&copy; <?=date('Y')?> Admin Area. Student Q&A Forum.</p>
    </footer>
</body>
</html>
