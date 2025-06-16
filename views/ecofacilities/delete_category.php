<?php
$pageTitle = 'Delete Category';
include __DIR__ . '/../templates/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Confirm Category Deletion</h4>
            </div>
            <div class="card-body">
                <p>Are you sure you want to delete the category: <strong><?php echo htmlspecialchars($category['name']); ?></strong>?</p>
                <p>This may affect facilities that are assigned to this category. It is recommended to reassign facilities first.</p>

                <form action="<?php echo SITE_URL; ?>/ecofacilities/delete-category/<?php echo $category['id']; ?>" method="post">
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <a href="<?php echo SITE_URL; ?>/ecofacilities/categories" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>