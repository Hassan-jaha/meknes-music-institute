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
                        <div style="margin-top:0.5rem; font-size:0.9rem;">
                <a href="index.php" style="color:#ecf0f1;opacity:0.8; margin-right:10px;"><?php echo __('nav_home'); ?></a>
                <a href="actualites.php" style="color:#ecf0f1;opacity:0.8; margin-right:10px;"><?php echo __('nav_news'); ?></a>
                <a href="annonces.php" style="color:#ecf0f1;opacity:0.8; margin-right:10px;"><?php echo __('nav_announcements'); ?></a>
                <a href="galerie.php" style="color:#ecf0f1;opacity:0.8; margin-right:10px;"><?php echo __('nav_gallery'); ?></a>
            </div>
        </div>
        <div style="flex:1;min-width:200px;">
            <h4 style="margin-bottom:0.5rem;"><?php echo __('footer_contact'); ?></h4>
            <p style="font-size:0.9rem;opacity:0.85;">📍 <?php echo __('contact_address_value'); ?></p>
            <p style="font-size:0.9rem;opacity:0.85;">📧 contact@institut-musique-meknes.ma</p>
            <h5 style="margin-top:0.5rem;"><?php echo __('footer_hours'); ?></h5>
            <p style="font-size:0.8rem;opacity:0.7;"><?php echo __('footer_days'); ?> : 09:00 - 18:00</p>
        </div>
        <!-- Location map column -->
        <div style="flex:1;min-width:200px;">
            <h4 style="margin-bottom:0.5rem;">Location</h4>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3402.1234567890123!2d-5.123456!3d34.567890!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xabcdef123456789!2sInstitut%20de%20Musique%20de%20Mekn%C3%A8s!5e0!3m2!1sen!2sma!4v1700000000000" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    <div style="margin-top:2rem;text-align:center;font-size:0.8rem;opacity:0.6;">
        &copy; <?php echo date('Y'); ?> <?php echo __('site_name'); ?>. <?php echo __('footer_all_rights'); ?>
    </div>
</footer>
<style>
    .site-footer a:hover {color: var(--color-red-primary) !important; text-decoration:underline;}
</style>
