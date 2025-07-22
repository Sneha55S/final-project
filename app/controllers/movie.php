<?php

class Movie extends Controller {

		public function index() {		
			$this->view('movie/index');
		}

		public function search() {
			if (!isset($_REQUEST['movie'])) {
				//redirect to /movie
			}

			$api = $this->model('Api');

			$movie_title = $_REQUEST['movie'];
			$movie = $api->search_movie($movie_title);

			$this->view('movie/results', ['movie' => $movie]);

			COSC Project
				movie [search....]
			[SEARCH BUTTON]

			Barbie Rating: 1 (a href="/movie/review/barbie/1") 2 3 4 5
				
		}

		public function review($movie_title = '', $rating = '') {		
			// if rating isn't 1,2,3,4,5... etc.
		}
}
