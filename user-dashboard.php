<?php
include 'includes/config.php';
include 'includes/functions.php';

if (!isLoggedIn() || getUserType() != 'user') {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$pageTitle = "User Dashboard";
include 'includes/header.php';

// Get user info
$user_query = "SELECT * FROM users WHERE user_id='$user_id'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Get pending requests count
$pending_query = "SELECT COUNT(*) as count FROM job_requests WHERE user_id='$user_id' AND status='pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_count = mysqli_fetch_assoc($pending_result)['count'];

// Get recent requests
$requests_query = "SELECT jr.*, w.full_name as worker_name, w.profession 
                   FROM job_requests jr 
                   JOIN workers w ON jr.worker_id = w.worker_id 
                   WHERE jr.user_id='$user_id' 
                   ORDER BY jr.created_at DESC 
                   LIMIT 5";
$requests_result = mysqli_query($conn, $requests_query);
?>

<div class="dashboard-container">
    <div class="sidebar">
        <h3>Dashboard Menu</h3>
        <ul>
            <li><a href="user-dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="browse-workers.php"><i class="fas fa-search"></i> Browse Workers</a></li>
            <li><a href="manage-requests.php"><i class="fas fa-tasks"></i> My Requests</a></li>
            <li><a href="user-profile.php"><i class="fas fa-user"></i> My Profile</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>Welcome, <?php echo $user['full_name']; ?>!</h1>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?php echo $pending_count; ?></h3>
                <p>Pending Requests</p>
            </div>
        </div>
        
        <div class="card">
            <h2>Recent Job Requests</h2>
            <?php if (mysqli_num_rows($requests_result) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Worker</th>
                            <th>Profession</th>
                            <th>Job Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($request = mysqli_fetch_assoc($requests_result)): ?>
                        <tr>
                            <td><?php echo $request['worker_name']; ?></td>
                            <td><?php echo $request['profession']; ?></td>
                            <td><?php echo date('M d, Y', strtotime($request['job_date'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $request['status']; ?>">
                                    <?php echo ucfirst($request['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="worker-profile.php?id=<?php echo $request['worker_id']; ?>" class="btn btn-secondary btn-sm">View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No job requests yet. <a href="browse-workers.php">Browse workers</a> to get started.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.dashboard-stats {
    display: flex;
    gap: 1rem;
    margin: 2rem 0;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    min-width: 150px;
}

.stat-card h3 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.status-accepted {
    background-color: #d4edda;
    color: #155724;
}

.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<?php include 'includes/footer.php'; ?>