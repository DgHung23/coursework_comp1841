<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <title><?=$title ?? 'Student Q&A Forum'?></title>
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="brand">Student Q&A Forum</a>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="posts.php">Questions</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="post_action.php">Ask Question</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
                        <li><a href="admin/index.php">Admin Area</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php">Logout (<?=$_SESSION['username']?>)</a></li>
                <?php else: ?>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php" class="btn-primary">Sign Up</a></li>
                <?php endif; ?>
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
        <p>&copy; <?=date('Y')?> Student Q&A Forum. Built for COMP1841.</p>
    </footer>
</body>
</html>