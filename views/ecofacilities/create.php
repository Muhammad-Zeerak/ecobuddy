<?php
$pageTitle = 'Add New Facility';
include __DIR__ . '/../templates/header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Add New Eco Facility</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo SITE_URL; ?>/ecofacilities/create" method="post" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-6">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="housenumber" class="form-label">House/Building Number</label>
                                <input type="text" class="form-control" id="housenumber" name="housenumber">
                            </div>
                            <div class="col-md-6">
                                <label for="streetname" class="form-label">Street Name</label>
                                <input type="text" class="form-control" id="streetname" name="streetname">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="town" class="form-label">Town</label>
                                <input type="text" class="form-control" id="town" name="town">
                            </div>
                            <div class="col-md-4">
                                <label for="county" class="form-label">County</label>
                                <input type="text" class="form-control" id="county" name="county">
                            </div>
                            <div class="col-md-4">
                                <label for="postcode" class="form-label">Postcode *</label>
                                <input type="text" class="form-control" id="postcode" name="postcode" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="lat" class="form-label">Latitude *</label>
                                <input type="number" class="form-control" id="lat" name="lat" step="0.0000001" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lng" class="form-label">Longitude *</label>
                                <input type="number" class="form-control" id="lng" name="lng" step="0.0000001" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label"><i class="fa-solid fa-image me-2"></i>Facility Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Upload an image of the facility. Recommended size: 800x600px or less.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success">Create Facility</button>
                            <a href="<?php echo SITE_URL; ?>/ecofacilities/manage" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/../templates/footer.php'; ?>