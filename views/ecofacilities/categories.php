<?php
$pageTitle = 'Manage Categories';
include __DIR__ . '/../templates/header.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Categories</h2>
        <a href="<?php echo SITE_URL; ?>/ecofacilities/create-category" class="btn btn-success">Add New Category</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['id']; ?></td>
                            <td><?php echo htmlspecialchars($category['name']); ?></td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/edit-category/<?php echo $category['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/delete-category/<?php echo $category['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>