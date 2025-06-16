<?php
$pageTitle = 'Edit User';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Edit User: <?php echo htmlspecialchars($user['username']); ?></h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo SITE_URL; ?>/user/edit/<?php echo $user['id']; ?>" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">
                                Leave blank to keep current password. If changing, password must be at least 12 characters long
                                and include uppercase letters, lowercase letters, numbers, and special characters.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="user_type" class="form-label">User Type</label>
                            <select class="form-select" id="user_type" name="user_type" required>
                                <?php foreach ($userTypes as $type): ?>
                                    <option value="<?php echo $type['id']; ?>" <?php echo ($user['usertype'] == $type['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Update User</button>
                        <a href="<?php echo SITE_URL; ?>/user/manage" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>