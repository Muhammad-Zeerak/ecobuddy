<?php
$pageTitle = 'Delete Facility';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Confirm Deletion</h4>
                </div>
                <div class="card-body">
                    <p>Are you sure you want to delete the facility: <strong><?php echo htmlspecialchars($facility['title']); ?></strong>?</p>
                    <p>This action cannot be undone. All related status updates will also be deleted.</p>

                    <form action="<?php echo SITE_URL; ?>/ecofacilities/delete/<?php echo $facility['id']; ?>" method="post">
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                            <a href="<?php echo SITE_URL; ?>/ecofacilities/manage" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>