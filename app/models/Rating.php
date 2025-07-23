<?php

class Rating {

    // Modified to accept review_text
    public function saveRating($userIdentifier, $imdbId, $movieTitle, $posterUrl, $ratingValue, $reviewText = null) {
        $db = db_connect();
        try {
            // Check if a rating from this user for this movie already exists
            $statement = $db->prepare("SELECT id FROM ratings WHERE user_identifier = :user_identifier AND imdb_id = :imdb_id");
            $statement->bindValue(':user_identifier', $userIdentifier);
            $statement->bindValue(':imdb_id', $imdbId);
            $statement->execute();
            $existingRating = $statement->fetch(PDO::FETCH_ASSOC);

            if ($existingRating) {
                // Update existing rating
                $statement = $db->prepare("UPDATE ratings SET rating = :rating, review_text = :review_text, movie_title = :movie_title, poster_url = :poster_url, created_at = NOW() WHERE id = :id");
                $statement->bindValue(':id', $existingRating['id']);
            } else {
                // Insert new rating
                $statement = $db->prepare("INSERT INTO ratings (user_identifier, imdb_id, movie_title, poster_url, rating, review_text, created_at) VALUES (:user_identifier, :imdb_id, :movie_title, :poster_url, :rating, :review_text, NOW())");
                $statement->bindValue(':user_identifier', $userIdentifier);
                $statement->bindValue(':imdb_id', $imdbId);
            }

            $statement->bindValue(':rating', $ratingValue);
            $statement->bindValue(':review_text', $reviewText); // Bind the new review text
            $statement->bindValue(':movie_title', $movieTitle);
            $statement->bindValue(':poster_url', $posterUrl);
            return $statement->execute();

        } catch (PDOException $e) {
            error_log("Database error saving rating: " . $e->getMessage());
            return false;
        }
    }

    public function getAverageRating($imdbId) {
        $db = db_connect();
        try {
            $statement = $db->prepare("SELECT AVG(rating) as average_rating, COUNT(id) as rating_count FROM ratings WHERE imdb_id = :imdb_id");
            $statement->bindValue(':imdb_id', $imdbId);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['rating_count'] > 0) {
                return [
                    'average_rating' => round($result['average_rating'], 1),
                    'rating_count' => (int)$result['rating_count']
                ];
            }
            return ['average_rating' => 0, 'rating_count' => 0];

        } catch (PDOException $e) {
            error_log("Database error getting average rating: " . $e->getMessage());
            return ['average_rating' => 0, 'rating_count' => 0];
        }
    }

    // New method to get a specific user's rating for a movie
    public function getUserRatingForMovie($userIdentifier, $imdbId) {
        $db = db_connect();
        try {
            $statement = $db->prepare("SELECT rating, review_text FROM ratings WHERE user_identifier = :user_identifier AND imdb_id = :imdb_id LIMIT 1");
            $statement->bindValue(':user_identifier', $userIdentifier);
            $statement->bindValue(':imdb_id', $imdbId);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC); // Returns rating and review_text or false
        } catch (PDOException $e) {
            error_log("Database error getting user rating: " . $e->getMessage());
            return false;
        }
    }

    // New method to get all reviews for a movie
    public function getAllReviewsForMovie($imdbId) {
        $db = db_connect();
        try {
            // Note: We are not using ORDER BY to avoid potential index issues on some environments.
            // Sorting can be done in PHP if necessary.
            $statement = $db->prepare("SELECT user_identifier, rating, review_text, created_at FROM ratings WHERE imdb_id = :imdb_id AND review_text IS NOT NULL AND review_text != ''");
            $statement->bindValue(':imdb_id', $imdbId);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC); // Returns all reviews
        } catch (PDOException $e) {
            error_log("Database error getting all reviews: " . $e->getMessage());
            return [];
        }
    }
}
