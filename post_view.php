<?php
session_start();
require_once 'includes/DatabaseConnection.php';
require_once 'includes/DataBaseFunctions.php';

if (!isset($_GET['id'])) {
    header('Location: posts.php');
    exit;
}

$post = getPost($pdo, $_GET['id']);
if (!$post) {
    $_SESSION['error'] = 'Question not found.';
    header('Location: posts.php');
    exit;
}

$title = htmlspecialchars($post['title']) . ' - Student Q&A Forum';

ob_start();
?>
<div class="glass-card">
    <div class="post-header" style="margin-bottom: 2rem;">
        <div>
            <h1 class="post-title" style="font-size: 2rem; margin-bottom: 0.5rem;"><?=htmlspecialchars($post['title'])?></h1>
            <div class="post-meta" style="font-size: 1rem;">
                Asked by <?=htmlspecialchars($post['author_name'])?> on <?=date('F j, Y g:i A', strtotime($post['created_at']))?>
            </div>
        </div>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['author_id'] || $_SESSION['role'] === 'ADMIN')): ?>
            <div>
                <a href="post_action.php?id=<?=$post['id']?>" class="btn-primary">Edit</a>
            </div>
        <?php endif; ?>
    </div>

    <div style="margin-bottom: 2rem;">
        <?php foreach($post['categories'] as $cat): ?>
            <span class="badge"><?=htmlspecialchars($cat['name'])?></span>
        <?php endforeach; ?>
    </div>

    <div class="post-content" style="font-size: 1.1rem; line-height: 1.8; color: #fff;">
        <?=nl2br(htmlspecialchars($post['content']))?>
    </div>

    <?php if (!empty($post['image'])): ?>
        <div style="margin-top: 2rem;">
            <img src="uploads/<?=htmlspecialchars($post['image'])?>" alt="Post Image" style="max-width: 100%; border-radius: 8px;">
        </div>
    <?php endif; ?>
</div>

<div class="glass-card" style="margin-top: 2rem;">
    <h3>Answers (Coming Soon)</h3>
    <p class="post-meta">This is a prototype system. The functionality to reply to questions will be built in future iterations.</p>
</div>
<?php
$output = ob_get_clean();
include 'templates/layout.html.php';
