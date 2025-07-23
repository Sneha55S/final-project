<?php

class Movie extends Controller {

		public function index() {		
						$this->view('movie/index');
		}

		public function search() {
						$movie_title = $_REQUEST['movie'] ?? '';

						if (empty($movie_title)) {
										header('Location: /movie'); // Redirect to search page if no movie title
										die;
						}

						$api = $this->model('Api');
						$ratingModel = $this->model('Rating');

						$movie = $api->search_movie($movie_title);

						$aiReview = null;
						$averageRating = ['average_rating' => 0, 'rating_count' => 0];
						$userRating = null; // To store the current user's rating
						$allReviews = []; // To store all reviews for the movie

						// Determine user identifier: username if logged in, IP address if guest
						$userIdentifier = isset($_SESSION['auth']) ? $_SESSION['username'] : ($_SERVER['REMOTE_ADDR'] ?? uniqid('guest_'));

						if (isset($movie['Response']) && $movie['Response'] == 'True') {
										$averageRating = $ratingModel->getAverageRating($movie['imdbID']);
										$allReviews = $ratingModel->getAllReviewsForMovie($movie['imdbID']); // Fetch all reviews

										// Only fetch user's rating if logged in
										if (isset($_SESSION['auth'])) {
												$userRating = $ratingModel->getUserRatingForMovie($userIdentifier, $movie['imdbID']);
										}

										// Only process rating submission if user is authenticated
										if (isset($_SESSION['auth']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
														$ratingValue = (int)($_POST['rating'] ?? 0);
														$reviewText = trim($_POST['review_text'] ?? ''); // Get the new review text
														$imdbId = $movie['imdbID'];
														$posterUrl = $movie['Poster'] !== 'N/A' ? $movie['Poster'] : null;

														if (filter_var($ratingValue, FILTER_VALIDATE_INT) && $ratingValue >= 1 && $ratingValue <= 5) {
																		// Pass reviewText to saveRating
																		if ($ratingModel->saveRating($userIdentifier, $imdbId, $movie_title, $posterUrl, $ratingValue, $reviewText)) {
																						$_SESSION['message'] = ['type' => 'success', 'text' => 'Your rating and review have been saved!'];
																						$averageRating = $ratingModel->getAverageRating($imdbId); // Recalculate average after new rating
																						$aiReview = $api->generate_ai_review($movie['Title'], $movie['Plot'], $ratingValue);
																						$_SESSION['ai_review'] = $aiReview; // Store AI review in session to display after redirect
																		} else {
																						$_SESSION['message'] = ['type' => 'error', 'text' => 'Could not save your rating and review.'];
																		}
														} else {
																		$_SESSION['message'] = ['type' => 'warning', 'text' => 'Please select a valid rating (1-5 stars).'];
														}
														// Redirect to prevent form resubmission on refresh
														header('Location: /movie/search?movie=' . urlencode($movie_title)); 
														die;
										}

										// Retrieve AI review from session if it was just generated
										$aiReview = $_SESSION['ai_review'] ?? null;

						} else {
										// Movie not found or API error
										// FIX: Corrected assignment syntax here
										$_SESSION['message'] = ['type' => 'error', 'text' => $movie['Error'] ?? 'Movie not found.']; 
										header('Location: /movie'); // Redirect back to search page
										die;
						}

						$data = [
										'movie' => $movie,
										'average_rating' => $averageRating,
										'user_identifier' => $userIdentifier, // Pass the determined identifier to the view
										'user_rating' => $userRating, // Pass the user's specific rating
										'all_reviews' => $allReviews, // Pass all reviews for the movie
										'ai_review' => $aiReview,
										'message' => $_SESSION['message'] ?? null
						];

						// Clear session messages and AI review after displaying them
						unset($_SESSION['message']);
						unset($_SESSION['ai_review']);

						$this->view('movie/results', $data);
		}
}
