<?php

class Rating {

    /**
     * Saves a movie rating to the database.
     * Inserts the movie if it doesn't exist, then inserts the rating.
     * @param string $userIdentifier A unique identifier for the user (e.g., IP address or session ID).
     * @param string $imdbId The IMDb ID of the movie.
     * @param string $movieTitle The title of the movie.
     * @param string|null $posterUrl The URL of the movie poster.
     * @param int $rating The rating given by the user (1-5).
     * @return bool True on success, false on failure.
     */
    public function saveRating($userIdentifier, $imdbId, $movieTitle, $posterUrl, $rating) {
        $db = db_connect();
        $db->beginTransaction(); // Start a transaction for atomicity

        try {
            // 1. Check if movie already exists in 'movies' table
            $stmt = $db->prepare("SELECT id FROM movies WHERE imdb_id = :imdb_id");
            $stmt->bindValue(':imdb_id', $imdbId);
            $stmt->execute();
            $movie = $stmt->fetch(PDO::FETCH_ASSOC);

            $movieId = null;
            if ($movie) {
                $movieId = $movie['id'];
            } else {
                // 2. If movie doesn't exist, insert it
                $stmt = $db->prepare("INSERT INTO movies (imdb_id, title, poster_url) VALUES (:imdb_id, :title, :poster_url)");
                $stmt->bindValue(':imdb_id', $imdbId);
                $stmt->bindValue(':title', $movieTitle);
                $stmt->bindValue(':poster_url', $posterUrl);
                $stmt->execute();
                $movieId = $db->lastInsertId(); // Get the ID of the newly inserted movie
            }

            if ($movieId) {
                // 3. Insert the rating
                $stmt = $db->prepare("INSERT INTO ratings (movie_id, user_identifier, rating) VALUES (:movie_id, :user_identifier, :rating)");
                $stmt->bindValue(':movie_id', $movieId);
                $stmt->bindValue(':user_identifier', $userIdentifier);
                $stmt->bindValue(':rating', $rating);
                $stmt->execute();
                $db->commit(); // Commit the transaction
                return true;
            }

            $db->rollBack(); // Rollback if movieId is null (shouldn't happen if logic is correct)
            return false;

        } catch (PDOException $e) {
            $db->rollBack(); // Rollback on any error
            error_log("Database Error in saveRating: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves the average rating and count for a given movie.
     * @param string $imdbId The IMDb ID of the movie.
     * @return array An associative array with 'average_rating' and 'rating_count'.
     */
    public function getAverageRating($imdbId) {
        $db = db_connect();
        try {
            $stmt = $db->prepare("
                SELECT 
                    COALESCE(AVG(r.rating), 0) AS average_rating, 
                    COUNT(r.id) AS rating_count
                FROM ratings r
                JOIN movies m ON r.movie_id = m.id
                WHERE m.imdb_id = :imdb_id
            ");
            $stmt->bindValue(':imdb_id', $imdbId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Format average_rating to one decimal place
            $result['average_rating'] = number_format((float)$result['average_rating'], 1);

            return $result;
        } catch (PDOException $e) {
            error_log("Database Error in getAverageRating: " . $e->getMessage());
            return ['average_rating' => 0, 'rating_count' => 0];
        }
    }
}
