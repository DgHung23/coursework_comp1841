<div class="glass-card auth-container" style="max-width: 600px;">
    <h2><?=isset($post['id']) ? 'Edit Question' : 'Ask a Question'?></h2>
    
    <form action="" method="POST" enctype="multipart/form-data">
        <?php if(isset($post['id'])): ?>
            <input type="hidden" name="post_id" value="<?=$post['id']?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Title <span style="color:red">*</span></label>
            <input type="text" id="title" name="title" class="form-control" required value="<?=isset($post['title']) ? htmlspecialchars($post['title']) : ''?>">
        </div>

        <div class="form-group">
            <label for="content">Description <span style="color:red">*</span></label>
            <textarea id="content" name="content" class="form-control" required><?=isset($post['content']) ? htmlspecialchars($post['content']) : ''?></textarea>
        </div>

        <?php
        $selectedCategoryIds = [];
        if (isset($post['categories']) && is_array($post['categories'])) {
            $selectedCategoryIds = array_column($post['categories'], 'id');
        }
        ?>
        <div class="form-group">
            <label>Modules / Categories (Optional - Select multiple)</label>
            <div class="categories-checkbox-group" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.6rem; margin-top: 0.4rem; padding: 0.8rem; background: #f9f9f9; border: 1px solid #ccc; border-radius: 4px; max-height: 200px; overflow-y: auto;">
                <?php if (empty($categories)): ?>
                    <p style="margin: 0; color: #777;">No modules available.</p>
                <?php else: ?>
                    <?php foreach($categories as $category): ?>
                        <label style="font-weight: normal; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; margin: 0;">
                            <input type="checkbox" name="category_ids[]" value="<?=$category['id']?>"
                                <?=in_array($category['id'], $selectedCategoryIds) ? 'checked' : ''?>>
                            <span><?=htmlspecialchars($category['name'])?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="image">Upload Screenshot (Optional)</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/png, image/jpeg, image/gif">
            <?php if(isset($post['image']) && $post['image']): ?>
                <p style="margin-top: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">Current image: <?=htmlspecialchars($post['image'])?></p>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn-primary"><?=isset($post['id']) ? 'Update Question' : 'Post Question'?></button>
    </form>
</div>
