<?php

class Movie extends Controller {

		public function index() {		
				$this->view('movie/index');
		}

		public function search() {
				$movie_title = $_REQUEST['movie'] ?? '';

				if (empty($movie_title)) {
						header('Location: /movie');
						die;
				}

				$api = $this->model('Api');
				$ratingModel = $this->model('Rating');

				$movie = $api->search_movie($movie_title);

				$aiReview = null;
				$averageRating = ['average_rating' => 0, 'rating_count' => 0];
				$userIdentifier = $_SERVER['REMOTE_ADDR'] ?? uniqid('user_'); // Simple ID for non-logged-in users

				if (isset($movie['Response']) && $movie['Response'] == 'True') {
						// Get current average rating before any new submission
						$averageRating = $ratingModel->getAverageRating($movie['imdbID']);

						// Handle rating submission (POST request)
						if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
								$ratingValue = (int)($_POST['rating'] ?? 0); // Cast to int for validation
								$imdbId = $movie['imdbID'];
								$posterUrl = $movie['Poster'] !== 'N/A' ? $movie['Poster'] : null;

								// Validate rating: must be an integer between 1 and 5
								if (filter_var($ratingValue, FILTER_VALIDATE_INT) && $ratingValue >= 1 && $ratingValue <= 5) {
										if ($ratingModel->saveRating($userIdentifier, $imdbId, $movie_title, $posterUrl, $ratingValue)) {
												$_SESSION['message'] = ['type' => 'success', 'text' => 'Your rating has been saved!'];
												// Re-fetch average rating after saving
												$averageRating = $ratingModel->getAverageRating($imdbId);

												// --- Generate AI Review Immediately After Saving Rating ---
												$aiReview = $api->generate_ai_review($movie['Title'], $movie['Plot'], $ratingValue);
												$_SESSION['ai_review'] = $aiReview; // Store AI review in session
												// --- END AI Review Generation ---

										} else {
												$_SESSION['message'] = ['type' => 'error', 'text' => 'Could not save your rating.'];
										}
								} else {
										$_SESSION['message'] = ['type' => 'warning', 'text' => 'Please select a valid rating (1-5 stars).'];
								}
								// Redirect to self to prevent form resubmission on refresh
								header('Location: /movie/search?movie=' . urlencode($movie_title));
								die;
						}

						// If AI review was generated and stored in session from a previous POST, retrieve it
						$aiReview = $_SESSION['ai_review'] ?? null;

				} else {
						// Movie not found or API error
						$_SESSION['message'] = ['type' => 'error', 'text' => $movie['Error'] ?? 'Movie not found.'];
						header('Location: /movie'); // Redirect back to search page
						die;
				}

				// Pass data to the view
				$data = [
						'movie' => $movie,
						'average_rating' => $averageRating,
						'user_identifier' => $userIdentifier,
						'ai_review' => $aiReview, // Pass the AI review
						'message' => $_SESSION['message'] ?? null // Get message from session
				];

				// Clear session messages/AI review after retrieving for this request
				unset($_SESSION['message']);
				unset($_SESSION['ai_review']);

				$this->view('movie/results', $data);
		}

				// The entire review() method should be removed from here.
				/*
		public function review($movie_title = '', $rating = '') {		
				// This method is not used in our current flow, can be left as is or removed.
				header('Location: /movie');
				die;
		}
				*/
}