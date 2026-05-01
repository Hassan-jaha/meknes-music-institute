<?php
// includes/footer.php
?>
</main>

<footer class="site-footer" style="background: var(--color-blue-deep); color: white; padding: 4rem 0 2rem; margin-top: 4rem;">
    <div class="container">
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
            <!-- About -->
            <div>
                <h3 style="color: var(--color-gold-primary); margin-bottom: 1.5rem;"><?= __('site_name') ?></h3>
                <p style="font-size: 0.9rem; line-height: 1.6; opacity: 0.8;"><?= __('footer_about') ?></p>
            </div>
            
            <!-- Links -->
            <div>
                <h3 style="color: var(--color-gold-primary); margin-bottom: 1.5rem;"><?= __('footer_links') ?></h3>
                <ul style="list-style: none; padding: 0; font-size: 0.9rem;">
                    <li style="margin-bottom: 10px;"><a href="index.php" style="color: white; opacity: 0.8;"><?= __('nav_home') ?></a></li>
                    <li style="margin-bottom: 10px;"><a href="actualites.php" style="color: white; opacity: 0.8;"><?= __('nav_news') ?></a></li>
                    <li style="margin-bottom: 10px;"><a href="annonces.php" style="color: white; opacity: 0.8;"><?= __('nav_announcements') ?></a></li>
                    <li style="margin-bottom: 10px;"><a href="galerie.php" style="color: white; opacity: 0.8;"><?= __('nav_gallery') ?></a></li>
                </ul>
            </div>
            
            <!-- Contact & Hours -->
            <div>
                <h3 style="color: var(--color-gold-primary); margin-bottom: 1.5rem;"><?= __('footer_contact') ?></h3>
                <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 10px;">📍 <?= __('contact_address_value') ?></p>
                <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 10px;">📧 contact@institut-musique-meknes.ma</p>
                
                <div style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px;">
                    <h4 style="margin-bottom: 5px; font-size: 0.9rem;"><?= __('footer_hours') ?></h4>
                    <p style="font-size: 0.8rem; opacity: 0.7;"><?= __('footer_days') ?> : 09:00 - 18:00</p>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1); text-align: center; font-size: 0.8rem; opacity: 0.6;">
            &copy; <?= date('Y') ?> <?= __('site_name') ?>. <?= __('footer_all_rights') ?>
        </div>
    </div>
</footer>

<style>
    .site-footer a:hover { color: var(--color-gold-primary) !important; text-decoration: underline; }
</style>

</body>
</html>
