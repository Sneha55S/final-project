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

										// Always fetch user's current saved rating/review if logged in
										if (isset($_SESSION['auth'])) {
												$userRating = $ratingModel->getUserRatingForMovie($userIdentifier, $movie['imdbID']);
										}

										// --- Handle POST Requests ---
										if (isset($_SESSION['auth']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
												$ratingValue = (int)($_POST['rating'] ?? 0);
												$imdbId = $movie['imdbID'];
												$posterUrl = $movie['Poster'] !== 'N/A' ? $movie['Poster'] : null;
												$moviePlot = $movie['Plot'];
												$submittedReviewText = trim($_POST['review_text'] ?? ''); // Get the review text from the form

												// Validate rating value for both actions
												if (!filter_var($ratingValue, FILTER_VALIDATE_INT) || $ratingValue < 1 || $ratingValue > 5) {
														$_SESSION['message'] = ['type' => 'warning', 'text' => 'Please select a valid rating (1-5 stars).'];
														header('Location: /movie/search?movie=' . urlencode($movie_title)); 
														die;
												}

												if (isset($_POST['submit_get_ai_review'])) { // Action: Get AI Review
														// Generate AI review
														$aiReview = $api->generate_ai_review($movie['Title'], $moviePlot, $ratingValue);
														$_SESSION['ai_review_for_movie_' . $imdbId] = $aiReview; // Store AI review
														$_SESSION['ai_rating_for_movie_' . $imdbId] = $ratingValue; // Store rating used for AI
														$_SESSION['message'] = ['type' => 'info', 'text' => 'AI review generated! You can now edit and post it.'];

														// Save the rating immediately. If there was an existing review, keep it.
														// If user had a review saved, use that. Otherwise, it's null.
														$currentSavedReview = $userRating['review_text'] ?? null;
														$ratingModel->saveRating($userIdentifier, $imdbId, $movie_title, $posterUrl, $ratingValue, $currentSavedReview);

												} elseif (isset($_POST['post_review'])) { // Action: Post/Update Review
														// This action saves whatever is currently in the textarea
														if ($ratingModel->saveRating($userIdentifier, $imdbId, $movie_title, $posterUrl, $ratingValue, $submittedReviewText)) {
																$_SESSION['message'] = ['type' => 'success', 'text' => 'Your rating and review have been saved!'];
																// Clear AI review from session after saving
																unset($_SESSION['ai_review_for_movie_' . $imdbId]);
																unset($_SESSION['ai_rating_for_movie_' . $imdbId]);
														} else {
																$_SESSION['message'] = ['type' => 'error', 'text' => 'Could not save your rating and review.'];
														}
												}
												// Always redirect after POST
												header('Location: /movie/search?movie=' . urlencode($movie_title)); 
												die;
										}

										// After POST, or on initial GET, retrieve AI review from session if it exists
										$aiReview = $_SESSION['ai_review_for_movie_' . $movie['imdbID']] ?? null; 

										// If an AI review was just generated, override userRating's review_text for display
										if ($aiReview && (!isset($userRating['review_text']) || empty($userRating['review_text']) || ($userRating['review_text'] !== $aiReview))) {
												$userRating['review_text'] = $aiReview;
												// Also ensure the rating used for AI is pre-selected if no saved rating
												if (!isset($userRating['rating'])) {
														$userRating['rating'] = $_SESSION['ai_rating_for_movie_' . $movie['imdbID']] ?? 0;
												}
										}

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
										'user_identifier' => $userIdentifier,
										'user_rating' => $userRating, // This now contains user's saved rating OR AI-generated review
										'all_reviews' => $allReviews,
										'message' => $_SESSION['message'] ?? null
						];

						// Clear general message after displaying
						unset($_SESSION['message']);
						// AI review specific session data is cleared after saving, or remains for display

						$this->view('movie/results', $data);
		}
}
