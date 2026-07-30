<div class="glass-card auth-container">
    <h2 style="text-align: center;"><?=$action === 'login' ? 'Welcome Back' : 'Create an Account'?></h2>
    
    <form action="" method="POST">
        <?php if($action === 'signup'): ?>
            <div class="form-group">
                <label for="username">Username <span style="color:red">*</span></label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="display_name">Display Name (Optional)</label>
                <input type="text" id="display_name" name="display_name" class="form-control">
            </div>
            <div class="form-group">
                <label for="bio">Bio (Optional)</label>
                <textarea id="bio" name="bio" class="form-control" style="min-height: 90px;"></textarea>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="email">Email Address <span style="color:red">*</span></label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password <span style="color:red">*</span></label>
            <input type="password" id="password" name="password" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">
            <?=$action === 'login' ? 'Login' : 'Sign Up'?>
        </button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <?php if($action === 'login'): ?>
            <p>Don't have an account? <a href="signup.php" style="color: var(--secondary);">Sign up here</a></p>
        <?php else: ?>
            <p>Already have an account? <a href="login.php" style="color: var(--secondary);">Login here</a></p>
        <?php endif; ?>
    </div>
</div>
