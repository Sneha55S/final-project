<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<div class="container">
    <div class="py-4" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-1">Hi, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h1>
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p class="mb-3">Welcome to your movie hub.</p>
            <ul class="list-unstyled mb-4">
                <li><a href="/movie">Search for movies</a> (available to everyone)</li>
                <li>View community ratings and AI-generated reviews (available to everyone)</li>
                <?php if (isset($_SESSION['auth'])): ?>
                    <li>Give your own ratings (1-5 stars)</li>
                    <li>Get personalized AI-generated reviews based on your rating</li>
                <?php else: ?>
                    <li><a href="/login">Log in</a> or <a href="/register">Sign up</a> to give ratings and get personalized AI-generated reviews.</li>
                <?php endif; ?>
            </ul>
            <div class="d-flex gap-2 mt-4">
                <a href="/movie" class="btn btn-primary">Go to Movie Search</a>
                <?php if (isset($_SESSION['auth'])): ?>
                    <a href="/logout" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-secondary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div> 
<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
