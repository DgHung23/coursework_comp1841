<div class="hero">
    <h1>Welcome to Student Q&A Forum</h1>
    <p>A simple, powerful place for students to ask questions, share knowledge, and collaborate on coursework.</p>
    <a href="posts.php" class="btn-primary">Browse Questions</a>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="signup.php" class="btn-primary" style="background: var(--secondary); margin-left: 1rem;">Join the Community</a>
    <?php endif; ?>
</div>

<div class="glass-card">
    <h2>Recent Activity</h2>
    <p>Check out the latest questions in the <a href="posts.php" style="color: var(--secondary);">Questions section</a>.</p>
</div>