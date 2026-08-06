<div class="glass-card auth-container" style="max-width: 600px;">
    <h2>Contact Administrator</h2>
    <p style="margin-bottom: 1.5rem; color: var(--text-muted);">Have a question or issue with the platform? Send us a message.</p>
    
    <form action="https://api.web3forms.com/submit" method="POST">
        <input type="hidden" name="access_key" value="YOUR_WEB3FORMS_ACCESS_KEY_HERE">
        <input type="hidden" name="subject" value="New contact message from Student Q&A Forum">
        <input type="hidden" name="from_name" value="Student Q&A Forum Contact Form">
        <input type="checkbox" name="botcheck" style="display: none;">

        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" class="form-control" required value="<?=isset($_SESSION['display_name']) ? htmlspecialchars($_SESSION['display_name']) : ''?>">
        </div>
        
        <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?=isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''?>">
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn-primary">Send Message</button>
    </form>
</div>
