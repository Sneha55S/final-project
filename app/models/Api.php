<?php

class Api {

		public function __construct() {

		}

		public function search_movie ($movie_title) {
			$query_url = "http://www.omdbapi.com/?apikey=".$_ENV['omdb_key']."&t=" . $movie_title;

			$json = file_get_contents($query_url);
			$phpObj = json_decode($json);
			$movie =  (array) $phpObj;
			return $movie;
		}

}
