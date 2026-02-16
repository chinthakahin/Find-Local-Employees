<?php
include 'includes/config.php';
include 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$type = $_GET['type'] ?? 'user'; // 'user' or 'worker'
$pageTitle = "Register as " . ucfirst($type);
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);
    $full_name = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    
    // Additional fields for workers
    if ($type == 'worker') {
        $profession = sanitizeInput($_POST['profession']);
        $experience_years = sanitizeInput($_POST['experience_years']);
        $hourly_rate = sanitizeInput($_POST['hourly_rate']);
        $description = sanitizeInput($_POST['description']);
    }
    
    // Validation
    $errors = [];
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    // Check if username or email exists
    if ($type == 'user') {
        $check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    } else {
        $check_query = "SELECT * FROM workers WHERE username='$username' OR email='$email'";
    }
    
    $check_result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        $errors[] = "Username or email already exists";
    }
    
    if (empty($errors)) {
        $hashed_password = hashPassword($password);
        
        if ($type == 'user') {
            $query = "INSERT INTO users (username, email, password, full_name, phone, address) 
                      VALUES ('$username', '$email', '$hashed_password', '$full_name', '$phone', '$address')";
        } else {
            $query = "INSERT INTO workers (username, email, password, full_name, phone, address, profession, experience_years, hourly_rate, description) 
                      VALUES ('$username', '$email', '$hashed_password', '$full_name', '$phone', '$address', '$profession', '$experience_years', '$hourly_rate', '$description')";
        }
        
        if (mysqli_query($conn, $query)) {
            $_SESSION['message'] = "Registration successful! Please login.";
            $_SESSION['message_type'] = 'success';
            redirect('login.php');
        } else {
            $errors[] = "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>

<div class="form-container">
    <h2>Register as <?php echo ucfirst($type); ?></h2>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="address">Address:</label>
            <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
        </div>
        
        <?php if ($type == 'worker'): ?>
            <div class="form-group">
                <label for="profession">Profession:</label>
                <select id="profession" name="profession" class="form-control" required>
                    <option value="">Select Profession</option>
                    <?php foreach (getProfessionList() as $prof): ?>
                        <option value="<?php echo $prof; ?>"><?php echo $prof; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="experience_years">Experience (Years):</label>
                <input type="number" id="experience_years" name="experience_years" class="form-control" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="hourly_rate">Hourly Rate ($):</label>
                <input type="number" id="hourly_rate" name="hourly_rate" class="form-control" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description/Skills:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>
        <?php endif; ?>
        
        <button type="submit" class="btn">Register</button>
        
        <p style="margin-top: 1rem;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
        
        <?php if ($type == 'user'): ?>
            <p>Want to work with us? <a href="register.php?type=worker">Register as Worker</a></p>
        <?php else: ?>
            <p>Need a worker? <a href="register.php?type=user">Register as User</a></p>
        <?php endif; ?>
    </form>
</div>

<?php include 'includes/footer.php'; ?>