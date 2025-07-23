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
						$userRating = null; 
						$allReviews = []; // To store all reviews for the movie

						// Determine user identifier: username if logged in, IP address if guest
						$userIdentifier = isset($_SESSION['auth']) ? $_SESSION['username'] : ($_SERVER['REMOTE_ADDR'] ?? uniqid('guest_'));

						if (isset($movie['Response']) && $movie['Response'] == 'True') {
										$averageRating = $ratingModel->getAverageRating($movie['imdbID']);
										$allReviews = $ratingModel->getAllReviewsForMovie($movie['imdbID']); 

										
										if (isset($_SESSION['auth'])) {
												$userRating = $ratingModel->getUserRatingForMovie($userIdentifier, $movie['imdbID']);
										}

										
										if (isset($_SESSION['auth']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
												$ratingValue = (int)($_POST['rating'] ?? 0);
												$imdbId = $movie['imdbID'];
												$posterUrl = $movie['Poster'] !== 'N/A' ? $movie['Poster'] : null;
												$moviePlot = $movie['Plot'];
												$submittedReviewText = trim($_POST['review_text'] ?? ''); // 

												
												if (!filter_var($ratingValue, FILTER_VALIDATE_INT) || $ratingValue < 1 || $ratingValue > 5) {
														$_SESSION['message'] = ['type' => 'warning', 'text' => 'Please select a valid rating (1-5 stars).'];
														header('Location: /movie/search?movie=' . urlencode($movie_title)); 
														die;
												}

												if (isset($_POST['submit_get_ai_review'])) {  
														
														$aiReview = $api->generate_ai_review($movie['Title'], $moviePlot, $ratingValue);
														$_SESSION['ai_review_for_movie_' . $imdbId] = $aiReview; 
														$_SESSION['ai_rating_for_movie_' . $imdbId] = $ratingValue; 
														$_SESSION['message'] = ['type' => 'info', 'text' => 'AI review generated! You can now edit and post it.'];

														
														
														$currentSavedReview = $userRating['review_text'] ?? null
														$ratingModel->saveRating($userIdentifier, $imdbId, $movie_title, $posterUrl, $ratingValue, $currentSavedReview);

												} elseif (isset($_POST['post_review'])) {  
														
														if ($ratingModel->saveRating($userIdentifier, $imdbId, $movie_title, $posterUrl, $ratingValue, $submittedReviewText)) {
																$_SESSION['message'] = ['type' => 'success', 'text' => 'Your rating and review have been saved!'];
																
																unset($_SESSION['ai_review_for_movie_' . $imdbId]);
																unset($_SESSION['ai_rating_for_movie_' . $imdbId]);
														} else {
																$_SESSION['message'] = ['type' => 'error', 'text' => 'Could not save your rating and review.'];
														}
												}
												
												header('Location: /movie/search?movie=' . urlencode($movie_title)); 
												die;
										}

										
										$aiReview = $_SESSION['ai_review_for_movie_' . $movie['imdbID']] ?? null; 

										
										if ($aiReview && (!isset($userRating['review_text']) || empty($userRating['review_text']) || ($userRating['review_text'] !== $aiReview))) {
												$userRating['review_text'] = $aiReview;
												
												if (!isset($userRating['rating'])) {
														$userRating['rating'] = $_SESSION['ai_rating_for_movie_' . $movie['imdbID']] ?? 0;
												}
										}

						} else {
										
										
										$_SESSION['message'] = ['type' => 'error', 'text' => $movie['Error'] ?? 'Movie not found.']; 
										header('Location: /movie'); 
										die;
						}

						$data = [
										'movie' => $movie,
										'average_rating' => $averageRating,
										'user_identifier' => $userIdentifier,
										'user_rating' => $userRating, 
										'all_reviews' => $allReviews,
										'message' => $_SESSION['message'] ?? null
						];

						unset($_SESSION['message']);
						

						$this->view('movie/results', $data);
		}
}
