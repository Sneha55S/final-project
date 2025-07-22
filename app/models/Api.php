<?php

class Api {

		public function __construct() {
				if (!isset($_ENV['OMDB_KEY'])) {
						throw new Exception("OMDB API Key is not set in environment variables.");
				}
				if (!isset($_ENV['GEMINI_KEY'])) {
						throw new Exception("Gemini API Key is not set in environment variables.");
				}
		}

		public function search_movie ($movie_title) {
				$query_url = "http://www.omdbapi.com/?apikey=" . $_ENV['OMDB_KEY'] . "&t=" . urlencode($movie_title);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $query_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

				$json = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				if ($json === false || $httpCode !== 200) {
						error_log("OMDB API Error: HTTP Code " . $httpCode . " | Response: " . $json);
						return ['Response' => 'False', 'Error' => 'Failed to connect to OMDB API or invalid response.'];
				}

				$phpObj = json_decode($json, true);
				return $phpObj;
		}

		/**
		 * Generates an AI review using the Google Gemini API, now considering the user's rating.
		 * @param string $movieTitle The title of the movie.
		 * @param string $plot The plot/summary of the movie.
		 * @param int $rating The rating given by the user (1-5).
		 * @return string The AI-generated review, or an error message.
		 */
		public function generate_ai_review($movieTitle, $plot, $rating) {
				if (!isset($_ENV['GEMINI_KEY'])) {
						return "Error: Gemini API Key is not set.";
				}

				// Construct the prompt to include the rating
				$prompt = "Write a concise movie review for the film '{$movieTitle}'. The user gave it a rating of {$rating} out of 5 stars. Reflect this rating in the tone and content of the review. Plot summary: '{$plot}'";

				$payload = json_encode([
						'contents' => [
								[
										'role' => 'user',
										'parts' => [
												['text' => $prompt]
										]
								]
						],
						'generationConfig' => [
								'temperature' => 0.7,
								'maxOutputTokens' => 200
						]
				]);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $_ENV['GEMINI_KEY']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				$response = curl_exec($ch);
				$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);

				if ($httpCode != 200) {
						error_log("Gemini API Error (HTTP {$httpCode}): " . $response);
						return "Error generating review: API returned HTTP {$httpCode}. Please check your API key and network connection.";
				}

				$result = json_decode($response, true);

				if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
						return $result['candidates'][0]['content']['parts'][0]['text'];
				} else {
						error_log("Gemini API Response Error: " . print_r($result, true));
						return "Error: Could not get a review from AI. Please try again or check API response structure.";
				}
		}
}

?>
