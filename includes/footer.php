<?php
// includes/footer.php - unified stylized footer with location
?>
<footer class="site-footer" style="background:#2c3e50;color:#ecf0f1;padding:2rem 0;margin-top:2rem;font-family:Arial, sans-serif;">
    <div class="container" style="display:flex;flex-wrap:wrap;gap:20px;justify-content:space-between;">
        <div style="flex:1;min-width:200px;">
                        <h3 style="margin-bottom:0.5rem; color: var(--color-red-primary);"><?php echo __('site_name'); ?></h3>
            <p style="font-size:0.9rem;color:#ecf0f1;opacity:0.9; margin:0.5rem 0;">Premier établissement d'enseignement musical à Meknès, dédié à la formation des futurs talents.</p>
        </div>
        <div style="flex:1;min-width:150px;">
            <div style="margin-top:0.5rem; font-size:0.9rem; display: flex; flex-direction: column; gap: 8px;">
                <a href="index.php" style="color:#ecf0f1;opacity:0.8;"><?php echo __('nav_home'); ?></a>
                <a href="actualites.php" style="color:#ecf0f1;opacity:0.8;"><?php echo __('nav_news'); ?></a>
                <a href="annonces.php" style="color:#ecf0f1;opacity:0.8;"><?php echo __('nav_announcements'); ?></a>
                <a href="galerie.php" style="color:#ecf0f1;opacity:0.8;"><?php echo __('nav_gallery'); ?></a>
                <a href="contact.php" style="color:#ecf0f1;opacity:0.8;"><?php echo __('nav_contact'); ?></a>
            </div>
        </div>
        <div style="flex:1;min-width:200px;">
            <h4 style="margin-bottom:0.5rem;"><?php echo __('footer_contact'); ?></h4>
            <p style="font-size:0.9rem;opacity:0.85; display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                <?php echo __('contact_address_value'); ?>
            </p>
            <p style="font-size:0.9rem;opacity:0.85; display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                contact@institut-musique-meknes.ma
            </p>
            <h5 style="margin-top:0.5rem;"><?php echo __('footer_hours'); ?></h5>
            <p style="font-size:0.8rem;opacity:0.7;"><?php echo __('footer_days'); ?> : 09:00 - 18:00</p>
        </div>
        <!-- Location map column -->
        <div style="flex:1;min-width:200px;">
            <h4 style="margin-bottom:0.5rem;">Location</h4>
            <iframe src= "https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d23974.722878822897!2d-5.547448!3d33.899532!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda044d9690cd955%3A0x3b79f498c2a485d3!2sMusic%20Institute%20of%20Meknes!5e1!3m2!1sfr!2sma!4v1777651238243!5m2!1sfr!2sma"  width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    <div style="margin-top:2rem;text-align:center;font-size:0.8rem;opacity:0.6;">
        &copy; <?php echo date('Y'); ?> <?php echo __('site_name'); ?>. <?php echo __('footer_all_rights'); ?>
    </div>
</footer>
<style>
    .site-footer a:hover {color: var(--color-red-primary) !important; text-decoration:underline;}
</style>
