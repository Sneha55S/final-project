<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <!-- Display "Hi, [Username]" -->
                <h1>Hi, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h1>
                <p class="lead"> <?= date("F jS, Y"); ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p>Welcome to your movie hub. You can now:</p>
            <ul>
                <li><a href="/movie">Search for movies</a></li>
                <li>Give ratings (1-5 stars)</li>
                <li>Get AI-generated reviews based on your rating</li>
            </ul>
            <p class="mt-4">
                <a href="/movie" class="btn btn-primary">Go to Movie Search</a>
            </p>
            <p class="mt-2">
                <a href="/logout" class="btn btn-secondary">Logout</a>
            </p>
        </div>
    </div>

  <?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
</div>
