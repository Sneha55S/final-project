<?php

class Omdb extends Controller {

		public function index() {		
			// This controller was primarily for debugging OMDB API.
			// In a production app, you might remove it or restrict access.
			// If you want to use it for a specific purpose, you'd need to
			// define what it should do (e.g., return JSON, render a view).
			// For now, we'll just ensure it doesn't output raw data and die.
			$query_url = "http://www.omdbapi.com/?apikey=".$_ENV['OMDB_KEY']."&i=tt3896198"; // Corrected variable name to OMDB_KEY

			$json = file_get_contents($query_url);
			$phpObj = json_decode($json);
			$movie = (array) $phpObj;

			// Do something with $movie, e.g., render a view, or return JSON.
			// Do NOT use echo/print_r/die here in a live application.
			// Example:
			// $this->view('some_omdb_test_view', ['movie_data' => $movie]);
		}
}
