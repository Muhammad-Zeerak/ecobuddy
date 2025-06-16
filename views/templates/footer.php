</main>

<footer class="bg-success text-white py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="fa-solid fa-leaf me-2"></i><?php echo SITE_NAME; ?></h5>
                <p>Helping you find eco-friendly facilities in your area.</p>
            </div>
            <div class="col-md-3">
                <h5><i class="fa-solid fa-link me-2"></i>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a class="text-white" href="<?php echo SITE_URL; ?>/"><i class="fa-solid fa-home me-2"></i>Home</a></li>
                    <li><a class="text-white" href="<?php echo SITE_URL; ?>/ecofacilities"><i class="fa-solid fa-list me-2"></i>Browse Facilities</a></li>
                    <li><a class="text-white" href="<?php echo SITE_URL; ?>/ecofacilities/search"><i class="fa-solid fa-magnifying-glass me-2"></i>Search</a></li>
                    <li><a class="nav-link" href="<?php echo SITE_URL; ?>/ecofacilities/map"><i class="fa-solid fa-map-location-dot me-2"></i>Map</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5><i class="fa-solid fa-user me-2"></i>Account</h5>
                <ul class="list-unstyled">
                    <?php if (isLoggedIn()): ?>
                        <li><a class="text-white" href="<?php echo SITE_URL; ?>/user/profile"><i class="fa-solid fa-id-card me-2"></i>Profile</a></li>
                        <li><a class="text-white" href="<?php echo SITE_URL; ?>/user/logout"><i class="fa-solid fa-sign-out-alt me-2"></i>Logout</a></li>
                    <?php else: ?>
                        <li><a class="text-white" href="<?php echo SITE_URL; ?>/user/login"><i class="fa-solid fa-sign-in-alt me-2"></i>Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>