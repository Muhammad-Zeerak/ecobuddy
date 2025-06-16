<?php
$pageTitle = 'Manage Facilities';
include __DIR__ . '/../templates/header.php';
?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-cogs text-success me-2"></i>Manage Eco Facilities</h2>
        <a href="<?php echo SITE_URL; ?>/ecofacilities/create" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Add New Facility</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th><i class="fa-solid fa-hashtag me-1"></i>ID</th>
                        <th><i class="fa-solid fa-heading me-1"></i>Title</th>
                        <th><i class="fa-solid fa-tag me-1"></i>Category</th>
                        <th><i class="fa-solid fa-map-marker-alt me-1"></i>Location</th>
                        <th><i class="fa-solid fa-cog me-1"></i>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($facilities as $facility): ?>
                        <tr>
                            <td><?php echo $facility['id']; ?></td>
                            <td><?php echo htmlspecialchars($facility['title']); ?></td>
                            <td><?php echo htmlspecialchars($facility['category_name']); ?></td>
                            <td>
                                <?php
                                $location = [];
                                if (!empty($facility['town'])) $location[] = htmlspecialchars($facility['town']);
                                if (!empty($facility['postcode'])) $location[] = htmlspecialchars($facility['postcode']);
                                echo implode(', ', $location);
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/view/<?php echo $facility['id']; ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/edit/<?php echo $facility['id']; ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i></a>
                                <a href="<?php echo SITE_URL; ?>/ecofacilities/delete/<?php echo $facility['id']; ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo SITE_URL; ?>/ecofacilities/manage?page=<?php echo $i; ?>&order=<?php echo isset($_GET['order']) ? $_GET['order'] : 'id'; ?>&dir=<?php echo isset($_GET['dir']) ? $_GET['dir'] : 'ASC'; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

<?php include __DIR__ . '/../templates/footer.php'; ?>