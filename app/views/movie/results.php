<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<main role="main" class="container">
    <div class="py-4" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-3">Movie Details</h1>
            </div>
        </div>
    </div>

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
        <div class="row mb-5">
            <div class="col-md-4 text-center text-md-start mb-4 mb-md-0">
                <img src="<?php echo htmlspecialchars($data['movie']['Poster'] !== 'N/A' ? $data['movie']['Poster'] : 'https://placehold.co/300x450/cccccc/333333?text=No+Poster'); ?>"
                     class="img-fluid rounded shadow-sm" alt="<?php echo htmlspecialchars($data['movie']['Title']); ?> Poster" style="max-width: 280px;">
            </div>
            <div class="col-md-8">
                <h2 class="h3 mb-2"><?php echo htmlspecialchars($data['movie']['Title']); ?> (<?php echo htmlspecialchars($data['movie']['Year']); ?>)</h2>
                <p class="text-muted mb-3"><?php echo htmlspecialchars($data['movie']['Rated']); ?> | <?php echo htmlspecialchars($data['movie']['Runtime']); ?></p>
                
                <p class="mb-2"><small class="text-muted">Genre:</small> <strong><?php echo htmlspecialchars($data['movie']['Genre']); ?></strong></p>
                <p class="mb-2"><small class="text-muted">Director:</small> <strong><?php echo htmlspecialchars($data['movie']['Director']); ?></strong></p>
                <p class="mb-4"><small class="text-muted">Actors:</small> <strong><?php echo htmlspecialchars($data['movie']['Actors']); ?></strong></p>
                
                <p class="lead mb-4"><?php echo htmlspecialchars($data['movie']['Plot']); ?></p>
                
                <?php if (isset($_SESSION['auth'])): ?>
                    <p class="text-muted small">Your User ID: <code><?php echo htmlspecialchars($_SESSION['username']); ?></code></p>
                <?php else: ?>
                    <p class="text-muted small">You are browsing as a guest.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <hr class="my-4">

                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Community Ratings</h5>
                        <p class="card-text fs-5">
                            Average: <strong><?php echo htmlspecialchars($data['average_rating']['average_rating']); ?>/5</strong>
                            (based on <?php echo htmlspecialchars($data['average_rating']['rating_count']); ?> ratings)
                        </p>
                    </div>
                </div>

                <?php if (isset($_SESSION['auth'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Your Rating & Review</h5>
                        <form method="POST" action="/movie/search?movie=<?php echo urlencode($data['movie']['Title']); ?>" class="row g-3">
                            <input type="hidden" name="imdb_id" value="<?php echo htmlspecialchars($data['movie']['imdbID']); ?>">
                            <input type="hidden" name="movie_title" value="<?php echo htmlspecialchars($data['movie']['Title']); ?>">
                            <input type="hidden" name="poster_url" value="<?php echo htmlspecialchars($data['movie']['Poster'] !== 'N/A' ? $data['movie']['Poster'] : ''); ?>">
                            <input type="hidden" name="movie_plot" value="<?php echo htmlspecialchars($data['movie']['Plot']); ?>">

                            <div class="col-12 mb-3">
                                <label class="form-label d-block">Your Rating:</label>
                                <?php $user_current_rating = $data['user_rating']['rating'] ?? 0; ?>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating<?php echo $i; ?>" value="<?php echo $i; ?>"
                                            <?php echo ($user_current_rating == $i) ? 'checked' : ''; ?> required>
                                        <label class="form-check-label" for="rating<?php echo $i; ?>"><?php echo str_repeat('⭐', $i); ?></label>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            
                            <div class="col-12 d-flex flex-column gap-2 mb-3"> <!-- Button moved up -->
                                <button type="submit" name="submit_get_ai_review" id="submit_get_ai_review_button" class="btn btn-success"
                                    <?php echo (empty($user_current_rating) && empty($data['ai_review'])) ? 'disabled' : ''; ?>
                                >
                                    Submit Rating & Get AI Review
                                </button>
                            </div>

                            <?php 
                            // Only show review textarea and Post Review button if a rating has been submitted
                            // or if an AI review was just generated/user has a saved review
                            if (!empty($user_current_rating) || !empty($data['ai_review']) || !empty($data['user_rating']['review_text'])): 
                            ?>
                            <div class="col-12 mb-3">
                                <label for="review_text" class="form-label">Your Review:</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="5" placeholder="Edit your review here..."><?php echo htmlspecialchars($data['user_rating']['review_text'] ?? $data['ai_review'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" name="post_review" id="post_review_button" class="btn btn-primary">
                                    Post Review
                                </button>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-info mb-4" role="alert">
                        Please <a href="/login" class="alert-link">log in</a> to submit a rating and write a review.
                    </div>
                <?php endif; ?>

                <!-- Section to display all user reviews -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">User Reviews</h5>
                        <?php if (!empty($data['all_reviews'])): ?>
                            <?php foreach ($data['all_reviews'] as $review): ?>
                                <div class="card mb-2 bg-white">
                                    <div class="card-body p-3">
                                        <h6 class="card-subtitle mb-1 text-muted small">
                                            <strong><?php echo htmlspecialchars($review['user_identifier']); ?></strong> rated
                                            <?php echo str_repeat('⭐', $review['rating']); ?>
                                            on <?php echo date('M j, Y', strtotime($review['created_at'])); ?>
                                        </h6>
                                        <?php if (!empty($review['review_text'])): ?>
                                            <p class="card-text mb-0"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                        <?php else: ?>
                                            <p class="card-text text-muted mb-0">No text review provided.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No user reviews yet. Be the first to add one!</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- End User Reviews Section -->

            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="/movie" class="btn btn-secondary">Search Another Movie</a>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Movie data not available. Please try searching again.
        </div>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingRadios = document.querySelectorAll('input[name="rating"]');
    const getAiReviewButton = document.getElementById('submit_get_ai_review_button');

    // Function to update button state
    function updateGetAiReviewButtonState() {
        let isRatingSelected = false;
        ratingRadios.forEach(radio => {
            if (radio.checked) {
                isRatingSelected = true;
            }
        });

        if (isRatingSelected) {
            getAiReviewButton.removeAttribute('disabled');
        } else {
            getAiReviewButton.setAttribute('disabled', 'disabled');
        }
    }

    // Add event listeners to rating radios
    ratingRadios.forEach(radio => {
        radio.addEventListener('change', updateGetAiReviewButtonState);
    });

    // Initial UI update on page load
    updateGetAiReviewButtonState();
});
</script>

<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
