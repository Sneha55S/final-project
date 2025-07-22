<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Movie Details</h1>
            </div>
        </div>
    </div>

    <!-- Display any flash messages from the session -->
    <?php
    if (isset($data['message'])):
        $message = $data['message'];
    ?>
        <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : ($message['type'] === 'error' ? 'danger' : 'warning'); ?> alert-dismissible fade show" role="alert">
            <strong><?php echo ucfirst($message['type']); ?>!</strong>
            <?php echo htmlspecialchars($message['text']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($data['movie']) && $data['movie']['Response'] == 'True'): ?>
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo htmlspecialchars($data['movie']['Poster'] !== 'N/A' ? $data['movie']['Poster'] : 'https://placehold.co/300x450/cccccc/333333?text=No+Poster'); ?>"
                     class="img-fluid rounded shadow-sm mb-3" alt="<?php echo htmlspecialchars($data['movie']['Title']); ?> Poster">
                <h2 class="h3"><?php echo htmlspecialchars($data['movie']['Title']); ?> (<?php echo htmlspecialchars($data['movie']['Year']); ?>)</h2>
                <p class="text-muted"><?php echo htmlspecialchars($data['movie']['Rated']); ?> | <?php echo htmlspecialchars($data['movie']['Runtime']); ?></p>
                <p>Your User ID: <code><?php echo htmlspecialchars($data['user_identifier']); ?></code></p>
            </div>
            <div class="col-md-8">
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($data['movie']['Genre']); ?></p>
                <p><strong>Director:</strong> <?php echo htmlspecialchars($data['movie']['Director']); ?></p>
                <p><strong>Actors:</strong> <?php echo htmlspecialchars($data['movie']['Actors']); ?></p>
                <p><strong>Plot:</strong> <?php echo htmlspecialchars($data['movie']['Plot']); ?></p>
                <hr>

                <!-- Average Rating Display -->
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Community Ratings</h5>
                        <p class="card-text">
                            Average: <strong><?php echo htmlspecialchars($data['average_rating']['average_rating']); ?>/5</strong>
                            (based on <?php echo htmlspecialchars($data['average_rating']['rating_count']); ?> ratings)
                        </p>
                    </div>
                </div>

                <!-- Give a Rating Form with Stars -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Give Your Rating (1-5 Stars)</h5>
                        <form method="POST" action="/movie/search?movie=<?php echo urlencode($data['movie']['Title']); ?>" class="row g-3 align-items-center">
                            <input type="hidden" name="imdb_id" value="<?php echo htmlspecialchars($data['movie']['imdbID']); ?>">
                            <input type="hidden" name="movie_title" value="<?php echo htmlspecialchars($data['movie']['Title']); ?>">
                            <input type="hidden" name="poster_url" value="<?php echo htmlspecialchars($data['movie']['Poster'] !== 'N/A' ? $data['movie']['Poster'] : ''); ?>">
                            <input type="hidden" name="movie_plot" value="<?php echo htmlspecialchars($data['movie']['Plot']); ?>"> <!-- Pass plot for AI -->

                            <div class="col-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                                    <label class="form-check-label" for="rating1">⭐</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                    <label class="form-check-label" for="rating2">⭐⭐</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                    <label class="form-check-label" for="rating3">⭐⭐⭐</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4">
                                    <label class="form-check-label" for="rating4">⭐⭐⭐⭐</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5">
                                    <label class="form-check-label" for="rating5">⭐⭐⭐⭐⭐</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" name="submit_rating" class="btn btn-success">Submit Rating & Get Review</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- AI Review Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">AI-Generated Review</h5>
                        <?php if (isset($data['ai_review'])): ?>
                            <div class="alert alert-secondary" role="alert">
                                <p><?php echo nl2br(htmlspecialchars($data['ai_review'])); ?></p>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Submit a rating to get an AI-generated review!</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="/movie" class="btn btn-secondary">&larr; Search Another Movie</a>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Movie data not available. Please try searching again.
        </div>
    <?php endif; ?>
</main>
<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
