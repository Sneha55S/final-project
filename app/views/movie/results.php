<?php require_once VIEWS . DS . 'templates' . DS . 'header.php' ?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Movie Details</h1>
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
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo htmlspecialchars($data['movie']['Poster'] !== 'N/A' ? $data['movie']['Poster'] : 'https://placehold.co/300x450/cccccc/333333?text=No+Poster'); ?>"
                     class="img-fluid rounded shadow-sm mb-3" alt="<?php echo htmlspecialchars($data['movie']['Title']); ?> Poster">
                <h2 class="h3"><?php echo htmlspecialchars($data['movie']['Title']); ?> (<?php echo htmlspecialchars($data['movie']['Year']); ?>)</h2>
                <p class="text-muted"><?php echo htmlspecialchars($data['movie']['Rated']); ?> | <?php echo htmlspecialchars($data['movie']['Runtime']); ?></p>
                <?php if (isset($_SESSION['auth'])): // Only show user ID if logged in ?>
                    <p>Your User ID: <code><?php echo htmlspecialchars($_SESSION['username']); ?></code></p>
                <?php else: ?>
                    <p>You are browsing as a guest.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($data['movie']['Genre']); ?></p>
                <p><strong>Director:</strong> <?php echo htmlspecialchars($data['movie']['Director']); ?></p>
                <p><strong>Actors:</strong> <?php echo htmlspecialchars($data['movie']['Actors']); ?></p>
                <p><strong>Plot:</strong> <?php echo htmlspecialchars($data['movie']['Plot']); ?></p>
                <hr>

                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Community Ratings</h5>
                        <p class="card-text">
                            Average: <strong><?php echo htmlspecialchars($data['average_rating']['average_rating']); ?>/5</strong>
                            (based on <?php echo htmlspecialchars($data['average_rating']['rating_count']); ?> ratings)
                        </p>
                    </div>
                </div>

                <?php if (isset($_SESSION['auth'])): // Only show rating form if logged in ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Your Rating & Review</h5>
                        <form method="POST" action="/movie/search?movie=<?php echo urlencode($data['movie']['Title']); ?>" class="row g-3 align-items-center">
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
                            
                            <div class="col-12 mb-3">
                                <label for="review_text" class="form-label">Your Review:</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="5" placeholder="Write your review here or get an AI-generated one..."
                                    <?php echo (empty($data['user_rating']['rating']) && empty($data['user_rating']['review_text']) && empty($data['ai_review'])) ? 'readonly' : ''; ?>
                                ><?php echo htmlspecialchars($data['user_rating']['review_text'] ?? $data['ai_review'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12 d-flex flex-column gap-2">
                                <button type="submit" name="submit_get_ai_review" id="submit_get_ai_review_button" class="btn btn-success">
                                    Submit Rating & Get AI Review
                                </button>
                                <?php if (!empty($data['user_rating']['rating']) || !empty($data['ai_review'])): // Show Post Review button if rating exists or AI review is generated ?>
                                <button type="submit" name="post_review" id="post_review_button" class="btn btn-primary">
                                    Post Review
                                </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                <?php else: // Message for guests ?>
                    <div class="alert alert-info" role="alert">
                        Please <a href="/login" class="alert-link">log in</a> to submit a rating and write a review.
                    </div>
                <?php endif; ?>

                <!-- Section to display all user reviews -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">User Reviews</h5>
                        <?php if (!empty($data['all_reviews'])): ?>
                            <?php foreach ($data['all_reviews'] as $review): ?>
                                <div class="card mb-2 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-subtitle mb-1 text-muted">
                                            <strong><?php echo htmlspecialchars($review['user_identifier']); ?></strong> rated
                                            <?php echo str_repeat('⭐', $review['rating']); ?>
                                            on <?php echo date('M j, Y', strtotime($review['created_at'])); ?>
                                        </h6>
                                        <?php if (!empty($review['review_text'])): ?>
                                            <p class="card-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                        <?php else: ?>
                                            <p class="card-text text-muted">No text review provided.</p>
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
                <a href="/movie" class="btn btn-secondary">&larr; Search Another Movie</a>
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
    const reviewTextarea = document.getElementById('review_text');
    const getAiReviewButton = document.getElementById('submit_get_ai_review_button');
    const postReviewButton = document.getElementById('post_review_button'); // This might be null initially

    // Function to update textarea and button state
    function updateReviewUI() {
        let isRatingSelected = false;
        ratingRadios.forEach(radio => {
            if (radio.checked) {
                isRatingSelected = true;
            }
        });

        // Enable textarea if a rating is selected OR if it already has content (from saved review or AI)
        if (isRatingSelected || reviewTextarea.value.trim() !== '') {
            reviewTextarea.removeAttribute('readonly');
        } else {
            reviewTextarea.setAttribute('readonly', 'readonly');
        }

        // The "Post Review" button is now controlled by PHP rendering logic
        // based on whether a review exists or AI review was just generated.
        // We only need to ensure the "Get AI Review" button is enabled if a rating is selected.
        if (isRatingSelected) {
            getAiReviewButton.removeAttribute('disabled');
        } else {
            getAiReviewButton.setAttribute('disabled', 'disabled');
        }
    }

    // Add event listeners to rating radios
    ratingRadios.forEach(radio => {
        radio.addEventListener('change', updateReviewUI);
    });

    // Initial UI update on page load
    updateReviewUI();
});
</script>

<?php require_once VIEWS . DS . 'templates' . DS . 'footer.php' ?>
