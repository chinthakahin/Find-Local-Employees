<?php
include 'includes/config.php';
include 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$pageTitle = "Login";
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $user_type = $_POST['user_type'];
    
    if ($user_type == 'user') {
        $query = "SELECT * FROM users WHERE email='$email'";
        $redirect_page = 'user-dashboard.php';
        $id_field = 'user_id';
    } else {
        $query = "SELECT * FROM workers WHERE email='$email'";
        $redirect_page = 'worker-dashboard.php';
        $id_field = 'worker_id';
    }
    
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (verifyPassword($password, $user['password'])) {
            if ($user_type == 'user') {
                $_SESSION['user_id'] = $user[$id_field];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $redirect_page = 'browse-workers.php'; // Redirect users to browse workers
            } else {
                $_SESSION['worker_id'] = $user[$id_field];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['profession'] = $user['profession'];
            }
            
            $_SESSION['message'] = "Login successful!";
            $_SESSION['message_type'] = 'success';
            redirect($redirect_page);
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "No account found with this email";
    }
}
?>

<div class="form-container">
    <h2>Login</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="user_type">Login as:</label>
            <select id="user_type" name="user_type" class="form-control" required>
                <option value="user">User (Need Workers)</option>
                <option value="worker">Worker</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn">Login</button>
        
        <p style="margin-top: 1rem;">
            Don't have an account? 
            <a href="register.php?type=user">Register as User</a> or 
            <a href="register.php?type=worker">Register as Worker</a>
        </p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>