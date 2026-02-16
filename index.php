<?php
include 'includes/config.php';
include 'includes/functions.php';
$pageTitle = "Home";
include 'includes/header.php';
?>

<div class="hero">
    <div class="container">
        <h1>Find Local Workers Near You</h1>
        <p>Connect with skilled professionals - Plumbers, Electricians, Carpenters, and more!</p>
        
        <?php if (!isLoggedIn()): ?>
            <div class="cta-buttons">
                <a href="browse-workers.php" class="btn">Need a Worker?</a>
                <a href="register.php?type=worker" class="btn btn-secondary">Join as Worker</a>
            </div>
        <?php else: ?>
            <div class="cta-buttons">
                <?php if (getUserType() == 'user'): ?>
                    <a href="browse-workers.php" class="btn">Browse Workers</a>
                <?php else: ?>
                    <a href="worker-dashboard.php" class="btn">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="features">
    <div class="container">
        <h2>How It Works</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-search"></i>
                <h3>Search Workers</h3>
                <p>Browse through verified local workers by profession</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-calendar-check"></i>
                <h3>Book Service</h3>
                <p>Select date and time that works for you</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-handshake"></i>
                <h3>Get Hired</h3>
                <p>Workers confirm your booking request</p>
            </div>
        </div>
    </div>
</div>

<style>
.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.hero p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.features {
    padding: 4rem 0;
}

.features h2 {
    text-align: center;
    margin-bottom: 3rem;
    color: #333;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.feature-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.feature-card i {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.feature-card h3 {
    margin-bottom: 0.5rem;
    color: #333;
}
</style>

<?php include 'includes/footer.php'; ?>