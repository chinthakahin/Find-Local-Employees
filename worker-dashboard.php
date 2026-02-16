<?php
include 'includes/config.php';
include 'includes/functions.php';

if (!isLoggedIn() || getUserType() != 'worker') {
    redirect('login.php');
}

$worker_id = $_SESSION['worker_id'];
$pageTitle = "Worker Dashboard";
include 'includes/header.php';

// Get worker info
$worker_query = "SELECT * FROM workers WHERE worker_id='$worker_id'";
$worker_result = mysqli_query($conn, $worker_query);
$worker = mysqli_fetch_assoc($worker_result);

// Get pending requests count
$pending_query = "SELECT COUNT(*) as count FROM job_requests WHERE worker_id='$worker_id' AND status='pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_count = mysqli_fetch_assoc($pending_result)['count'];

// Get recent requests
$requests_query = "SELECT jr.*, u.full_name as user_name, u.phone as user_phone 
                   FROM job_requests jr 
                   JOIN users u ON jr.user_id = u.user_id 
                   WHERE jr.worker_id='$worker_id' 
                   ORDER BY jr.created_at DESC 
                   LIMIT 5";
$requests_result = mysqli_query($conn, $requests_query);
?>

<div class="dashboard-container">
    <div class="sidebar">
        <h3>Dashboard Menu</h3>
        <ul>
            <li><a href="worker-dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="manage-requests.php"><i class="fas fa-tasks"></i> Job Requests</a></li>
            <li><a href="worker-profile-edit.php"><i class="fas fa-user"></i> My Profile</a></li>
        </ul>
    </div>
    
    <div class="main-content">
        <h1>Welcome, <?php echo $worker['full_name']; ?>!</h1>
        <p class="profession-badge"><?php echo $worker['profession']; ?></p>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?php echo $pending_count; ?></h3>
                <p>Pending Requests</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $worker['rating']; ?></