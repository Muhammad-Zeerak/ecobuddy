<?php
$pageTitle = 'Welcome to ecoBuddy';
include __DIR__ . '/templates/header.php';
?>

    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="display-4 fw-bold"><i class="fa-solid fa-leaf text-success me-2"></i>Welcome to ecoBuddy</h1>
            <p class="lead">Your go-to platform for finding eco-friendly facilities in your area.</p>
            <p class="mb-4">From recycling bins to electric vehicle charging stations, ecoBuddy helps you discover and contribute to local ecological resources.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                <a href="<?php echo SITE_URL; ?>/ecofacilities" class="btn btn-success btn-lg px-4 me-md-2"><i class="fa-solid fa-list me-2"></i>Browse Facilities</a>
                <a href="<?php echo SITE_URL; ?>/ecofacilities/search" class="btn btn-outline-success btn-lg px-4"><i class="fa-solid fa-magnifying-glass me-2"></i>Search</a>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="mb-4"><i class="fa-solid fa-star text-success me-2"></i>Featured Categories</h2>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-recycle fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Recycling Facilities</h5>
                    <p class="card-text">Find local recycling points for paper, plastic, glass, and more.</p>
                    <a href="<?php echo SITE_URL; ?>/ecofacilities/search?category=1" class="btn btn-outline-success"><i class="fa-solid fa-arrow-right me-2"></i>Explore</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-charging-station fa-3x text-success mb-3"></i>
                    <h5 class="card-title">EV Charging Stations</h5>
                    <p class="card-text">Locate nearby electric vehicle charging points.</p>
                    <a href="<?php echo SITE_URL; ?>/ecofacilities/search?category=4" class="btn btn-outline-success"><i class="fa-solid fa-arrow-right me-2"></i>Explore</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fa-solid fa-bicycle fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Bike Share Stations</h5>
                    <p class="card-text">Find bike sharing stations for eco-friendly transportation.</p>
                    <a href="<?php echo SITE_URL; ?>/ecofacilities/search?category=3" class="btn btn-outline-success"><i class="fa-solid fa-arrow-right me-2"></i>Explore</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row bg-light py-5 rounded mb-5">
        <div class="col-md-6 mb-4 mb-md-0">
            <h3><i class="fa-solid fa-circle-info text-success me-2"></i>How ecoBuddy Works</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-magnifying-glass text-success me-2"></i> <strong>Browse & Search</strong> - Explore eco-facilities around you</li>
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-chart-line text-success me-2"></i> <strong>Stay Updated</strong> - Check real-time status of facilities</li>
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-pen-to-square text-success me-2"></i> <strong>Contribute</strong> - Log in to provide status updates</li>
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-seedling text-success me-2"></i> <strong>Promote Sustainability</strong> - Help others find eco-friendly options</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h3><i class="fa-solid fa-hands-helping text-success me-2"></i>Get Involved</h3>
            <p>ecoBuddy is a community-driven platform. Here's how you can help:</p>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-comment-dots text-success me-2"></i> <strong>Share Updates</strong> - Let others know about facility conditions</li>
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-star text-success me-2"></i> <strong>Rate Facilities</strong> - Coming in the next version - Trimester 2</li>
                <li class="list-group-item bg-transparent"><i class="fa-solid fa-map-marked-alt text-success me-2"></i> <strong>Interactive Maps</strong> - Look for our mapping feature in Trimester 2</li>
            </ul>
            <?php if (!isLoggedIn()): ?>
                <div class="mt-3">
                    <a href="<?php echo SITE_URL; ?>/user/login" class="btn btn-success"><i class="fa-solid fa-sign-in-alt me-2"></i>Login to Contribute</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php include __DIR__ . '/templates/footer.php'; ?>