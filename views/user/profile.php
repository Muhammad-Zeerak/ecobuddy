<?php
$pageTitle = 'Profile';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Your Profile</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Username:</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($user['username']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Account Type:</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($user['user_type_name']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">Account ID:</div>
                        <div class="col-md-9"><?php echo htmlspecialchars($user['id']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>