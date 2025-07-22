<?php

class Omdb extends Controller {

		public function index() {        
				$query_url = "http://www.omdbapi.com/?apikey=".$_ENV['OMDB_KEY']."&i=tt3896198"; // Corrected variable name to OMDB_KEY

				$json = file_get_contents($query_url);
				$phpObj = json_decode($json);
				$movie = (array) $phpObj;
		}
}