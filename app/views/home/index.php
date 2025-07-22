<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <!-- Display "Hi, [Username]" -->
                <h1 class="mb-1">Hi, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>!</h1>
                <p class="lead mb-4"> <?= date("F jS, Y"); ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p class="mb-2">Welcome to your movie hub. You can now:</p>
            <ul class="mb-4">
                <li><a href="/index.php?url=movie">Search for movies</a></li>
                <li>Give ratings (1-5 stars)</li>
                <li>Get AI-generated reviews based on your rating</li>
            </ul>
            <p class="mt-4">
                <a href="/index.php?url=movie" class="btn btn-primary">Go to Movie Search</a>
            </p>
            <p class="mt-2">
                <a href="/index.php?url=logout" class="btn btn-secondary">Logout</a>
            </p>
        </div>
    </div>

 <?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
</div>
