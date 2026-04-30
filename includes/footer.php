    </main> <!-- Fin main du header -->

    <footer class="site-footer">
        <div class="container">
            <div class="footer-col">
                <a href="index.php" class="footer-logo"><?= __('site_name') ?></a>
                <p><?= __('footer_about') ?></p>
            </div>
            
            <div class="footer-col">
                <h3><?= __('footer_links') ?></h3>
                <ul>
                    <li><a href="index.php"><?= __('nav_home') ?></a></li>
                    <li><a href="actualites.php"><?= __('nav_news') ?></a></li>
                    <li><a href="annonces.php"><?= __('nav_announcements') ?></a></li>
                    <li><a href="galerie.php"><?= __('nav_gallery') ?></a></li>
                    <li><a href="contact.php"><?= __('nav_contact') ?></a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h3><?= __('footer_contact') ?></h3>
                <ul>
                    <li><strong><?= __('contact_address_label') ?> :</strong><br>123 Avenue de l'Héritage, Meknès</li>
                    <li style="margin-top: 10px;"><strong><?= __('contact_email_label') ?> :</strong><br>contact@institut-musique.ma</li>
                    <li style="margin-top: 10px;"><strong><?= __('contact_phone_label') ?> :</strong><br>+212 5 35 XX XX XX</li>
                    <li style="margin-top: 15px;">
                        <strong><?= __('footer_hours') ?> :</strong><br>
                        <?= __('footer_days') ?> : 09:00 - 18:30
                    </li>
                </ul>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= __('site_name') ?> - <?= __('footer_all_rights') ?></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="public/js/main.js"></script>
</body>
</html>
