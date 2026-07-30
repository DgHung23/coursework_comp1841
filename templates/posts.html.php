<div class="glass-card">
    <div class="posts-toolbar">
        <h2>
            <?=isset($selectedCategory) && $selectedCategory ? 'Questions in ' . htmlspecialchars($selectedCategory['name']) : 'All Questions'?>
            (<?=$totalPosts?>)
        </h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="post_action.php" class="btn-primary">Ask a Question</a>
        <?php endif; ?>
    </div>

    <form action="posts.php" method="GET" class="filter-form">
        <div class="form-group">
            <label for="category_id">Filter by Module / Category</label>
            <select id="category_id" name="category_id" class="form-control">
                <option value="">All modules</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?=$category['id']?>" <?=isset($selectedCategoryId) && (int)$selectedCategoryId === (int)$category['id'] ? 'selected' : ''?>>
                        <?=htmlspecialchars($category['name'])?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn-primary">Apply Filter</button>
        <?php if (!empty($selectedCategoryId)): ?>
            <a href="posts.php" class="btn-secondary">Clear</a>
        <?php endif; ?>
    </form>

    <?php if (empty($posts)): ?>
        <p><?=!empty($selectedCategoryId) ? 'No questions match this module yet.' : 'No questions have been posted yet.'?></p>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
            <div class="glass-card" style="background: rgba(255,255,255,0.02); margin-bottom: 1rem;">
                <div class="post-header">
                    <div>
                        <a href="post_view.php?id=<?=$post['id']?>" class="post-title"><?=htmlspecialchars($post['title'])?></a>
                        <div class="post-meta">
                            Asked by <?=htmlspecialchars($post['author_name'])?> (<?=htmlspecialchars($post['author_username'])?>) on <?=date('F j, Y', strtotime($post['created_at']))?>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['author_id'] || $_SESSION['role'] === 'ADMIN')): ?>
                        <div>
                            <a href="post_action.php?id=<?=$post['id']?>" class="btn-primary" style="padding: 0.3rem 0.8rem; font-size: 0.9rem;">Edit</a>
                            <form action="post_delete.php" method="POST" style="display: inline-block;">
                                <input type="hidden" name="id" value="<?=$post['id']?>">
                                <button type="submit" class="btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.9rem;" onclick="return confirm('Are you sure you want to delete this question?');">Delete</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div>
                    <?php foreach($post['categories'] as $cat): ?>
                        <span class="badge"><?=htmlspecialchars($cat['name'])?></span>
                    <?php endforeach; ?>
                </div>
                
                <div class="post-content" style="margin-top: 1rem;">
                    <?=nl2br(htmlspecialchars(substr($post['content'], 0, 200)))?><?=strlen($post['content']) > 200 ? '...' : ''?>
                </div>

                <?php if (!empty($post['image'])): ?>
                    <img src="uploads/<?=htmlspecialchars($post['image'])?>" alt="Post attachment" class="post-image" style="max-height: 200px; object-fit: cover;">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
