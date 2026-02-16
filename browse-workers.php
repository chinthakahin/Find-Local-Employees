<?php
include 'includes/config.php';
include 'includes/functions.php';

$pageTitle = "Browse Workers";
include 'includes/header.php';

$profession = isset($_GET['profession']) ? sanitizeInput($_GET['profession']) : '';
$query = "SELECT * FROM workers";
if ($profession) {
    $query .= " WHERE profession = '$profession'";
}
$result = mysqli_query($conn, $query);
?>

<div class="container" style="padding: 2rem 0;">
    <h2>Find Workers</h2>
    
    <div class="filter-section" style="margin-bottom: 2rem;">
        <form action="" method="GET" style="display: flex; gap: 1rem;">
            <select name="profession" class="form-control" style="max-width: 200px;">
                <option value="">All Professions</option>
                <?php foreach (getProfessionList() as $prof): ?>
                    <option value="<?php echo $prof; ?>" <?php echo $profession == $prof ? 'selected' : ''; ?>><?php echo $prof; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>

    <div class="features-grid">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($worker = mysqli_fetch_assoc($result)): ?>
                <div class="card worker-card">
                    <div class="worker-avatar">
                        <?php echo strtoupper(substr($worker['username'], 0, 1)); ?>
                    </div>
                    <div class="worker-info">
                        <span class="profession-badge"><?php echo htmlspecialchars($worker['profession']); ?></span>
                        <h3><?php echo htmlspecialchars($worker['full_name']); ?></h3>
                        <div class="rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <span>(4.5)</span>
                        </div>
                        <p><strong>Experience:</strong> <?php echo htmlspecialchars($worker['experience_years']); ?> years</p>
                        <p><strong>Rate:</strong> $<?php echo htmlspecialchars($worker['hourly_rate']); ?>/hr</p>
                        <p style="margin-top: 0.5rem;"><?php echo htmlspecialchars(substr($worker['description'], 0, 100)) . '...'; ?></p>
                        <div style="margin-top: 1rem;">
                            <a href="#" class="btn" onclick="alert('Booking feature coming soon!')">Book Now</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No workers found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
