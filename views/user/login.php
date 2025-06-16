<?php
$pageTitle = 'Login';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fa-solid fa-sign-in-alt me-2"></i>Login</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo SITE_URL; ?>/user/login" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label"><i class="fa-solid fa-user me-2"></i>Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"><i class="fa-solid fa-lock me-2"></i>Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-sign-in-alt me-2"></i>Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>