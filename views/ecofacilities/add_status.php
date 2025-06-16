<?php
$pageTitle = 'Add Status Update';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Add Status Update for: <?php echo htmlspecialchars($facility['title']); ?></h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo SITE_URL; ?>/ecofacilities/add-status/<?php echo $facility['id']; ?>" method="post">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Status Comment *</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="e.g., 'Not working', 'Bin is full', 'All equipment operational'" required></textarea>
                            <div class="form-text">
                                Please provide a brief status update on the current condition of this facility.
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Submit Status Update</button>
                            <a href="<?php echo SITE_URL; ?>/ecofacilities/view/<?php echo $facility['id']; ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>