<?php
$pageTitle = 'Logout';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Logout</h4>
                </div>
                <div class="card-body text-center">
                    <p>You have been successfully logged out.</p>
                    <a href="<?php echo SITE_URL; ?>/" class="btn btn-success">Return to Home</a>
                    <a href="<?php echo SITE_URL; ?>/user/login" class="btn btn-outline-success">Login Again</a>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>