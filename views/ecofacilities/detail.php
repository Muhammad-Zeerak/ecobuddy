<?php
$pageTitle = htmlspecialchars($facility['title']);
include __DIR__ . '/../templates/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><?php echo htmlspecialchars($facility['title']); ?></h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Category:</div>
                    <div class="col-md-9"><?php echo htmlspecialchars($facility['category_name']); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Description:</div>
                    <div class="col-md-9"><?php echo htmlspecialchars($facility['description']); ?></div>
                </div>
                <?php if (!empty($facility['photo'])): ?>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <img src="<?php echo SITE_URL; ?>/assets/images/facilities/<?php echo htmlspecialchars($facility['photo']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($facility['title']); ?>">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Address:</div>
                    <div class="col-md-9">
                        <?php
                        $address = [];
                        if (!empty($facility['housenumber'])) $address[] = htmlspecialchars($facility['housenumber']);
                        if (!empty($facility['streetname'])) $address[] = htmlspecialchars($facility['streetname']);
                        if (!empty($facility['town'])) $address[] = htmlspecialchars($facility['town']);
                        if (!empty($facility['county'])) $address[] = htmlspecialchars($facility['county']);
                        if (!empty($facility['postcode'])) $address[] = htmlspecialchars($facility['postcode']);
                        echo implode(', ', $address);
                        ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">GPS Coordinates:</div>
                    <div class="col-md-9">
                        Latitude: <?php echo $facility['lat']; ?>, Longitude: <?php echo $facility['lng']; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 fw-bold">Added by:</div>
                    <div class="col-md-9"><?php echo htmlspecialchars($facility['contributor_name']); ?></div>
                </div>

                <?php if (isLoggedIn()): ?>
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo SITE_URL; ?>/ecofacilities/add-status/<?php echo $facility['id']; ?>" class="btn btn-primary">Add Status Update</a>
                        <?php if (isManager()): ?>
                            <div>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/edit/<?php echo $facility['id']; ?>" class="btn btn-warning">Edit Facility</a>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/delete/<?php echo $facility['id']; ?>" class="btn btn-danger">Delete Facility</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Status Updates</h5>
            </div>
            <div class="card-body">
                <?php if (count($statuses) > 0): ?>
                    <?php foreach ($statuses as $status): ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <p class="card-text"><?php echo htmlspecialchars($status['statuscomment']); ?></p>
                                <p class="card-text"><small class="text-muted">Updated by: <?php echo htmlspecialchars($status['username']); ?></small></p>
                                <?php if (isManager()): ?>
                                    <a href="<?php echo SITE_URL; ?>/ecofacilities/delete-status/<?php echo $status['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No status updates yet.</p>
                <?php endif; ?>

                <?php if (isLoggedIn()): ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo SITE_URL; ?>/ecofacilities/add-status/<?php echo $facility['id']; ?>" class="btn btn-outline-success">Add Status Update</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

    <div class="mt-4">
        <a href="<?php echo SITE_URL; ?>/ecofacilities/manage" class="btn btn-secondary">Back to List</a>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>