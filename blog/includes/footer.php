    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo e(SITE_TITLE); ?></h3>
                    <p><?php echo e(SITE_DESCRIPTION); ?></p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>?page=about">About</a></li>
                        <li><a href="<?php echo SITE_URL; ?>?page=contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contact</h4>
                    <p>Email: <?php echo e(SITE_EMAIL); ?></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo e(SITE_TITLE); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
    
</body>
</html>
