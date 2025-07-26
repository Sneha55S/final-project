<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<div class="container my-5">
    <div class="text-center fade-in">
        <h1 class="display-5 fw-bold mb-3">
          Hi, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!
        </h1>
        <p class="lead mb-4">Welcome to <strong>Cine Review</strong>, your personalized movie hub.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-4">
            <a href="/movie" class="btn btn-primary btn-lg px-4 me-sm-3">Movie Search</a>
            <?php if (isset($_SESSION['auth'])): ?>
                <a href="/logout" class="btn btn-outline-secondary btn-lg px-4">Logout</a>
            <?php else: ?>
                <a href="/login" class="btn btn-outline-secondary btn-lg px-4">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row justify-content-center fade-in">
        <div class="col-lg-8">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <i class="bi bi-search"></i> <a href="/movie">Search for movies</a> (available to everyone)
                </li>
                <li class="list-group-item">
                    <i class="bi bi-stars"></i> View community ratings and AI-generated reviews
                </li>
                <?php if (isset($_SESSION['auth'])): ?>
                    <li class="list-group-item">
                        <i class="bi bi-star-half"></i> Give your own ratings (1–5 stars)
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-robot"></i> Get personalized AI reviews based on your rating
                    </li>
                <?php else: ?>
                    <li class="list-group-item">
                        <i class="bi bi-person-plus"></i>
                        <a href="/login">Log in</a> or <a href="/register">Sign up</a> to rate movies and receive AI reviews.
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
