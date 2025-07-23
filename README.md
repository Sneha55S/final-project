Cine Review - Movie Rating Application
Project Description
Cine Review is a web application that allows users to search for movies, view detailed information, see community ratings, and for authenticated users, to submit their own ratings and AI-assisted reviews. It provides a clean, minimalistic user interface and demonstrates key web development concepts including MVC architecture, database interaction, and API integration.

Features
Movie Search & Details:

Users (guests or logged-in) can search for movies by title.

Displays comprehensive movie details (poster, plot, cast, genre, director, runtime, etc.) fetched from the OMDB API.

User Authentication & Authorization:

User Registration (Sign Up): Allows new users to create accounts with secure password hashing. Checks for unique usernames.

Login & Logout: Secure session-based authentication.

Guest Access: Unauthenticated users can search for movies, view details, and see community ratings and user reviews.

Authenticated Access: Logged-in users gain the ability to submit ratings and create reviews.

Rating System:

Logged-in users can submit a 1-5 star rating for any movie.

Users can update their existing rating for a movie.

Displays the average community rating for each movie.

Interactive AI-Generated & User-Editable Reviews:

When a logged-in user submits a rating, an AI-generated review (powered by the Google Gemini API) is provided based on the movie's plot and the rating.

This AI-generated text populates an editable textarea, allowing the user to refine or completely rewrite the review.

A separate "Post Review" action allows the user to save their final review to the database.

Display of User Reviews:

All user-submitted text reviews (including the username, rating, and review text) are displayed publicly on the movie details page.

Clean URL Routing: The application utilizes an MVC-based routing system for user-friendly and structured URLs (e.g., /login, /movie, /movie/search).

Minimalist User Interface: A clean, modern, and responsive design built with Bootstrap 5, featuring a consistent color palette and fixed header/footer.

Live Date & Time: The footer displays the current date and time, updating live.

Technologies Used
Backend: PHP (Core MVC implementation)

Database: MySQL / MariaDB (via PDO)

Dependency Management: Composer (vlucas/phpdotenv for environment variables)

Frontend: HTML5, CSS3 (Custom styles), JavaScript, Bootstrap 5

APIs:

OMDB API (for movie data)

Google Gemini API (for AI-generated reviews)

Development Environment: Replit

Deployment (Recommended): Render (PaaS) with PlanetScale (Managed MySQL)

Setup Instructions (High-Level)
Clone the Repository: Clone this project to your local machine or Replit workspace.

Install Composer Dependencies: Run composer install in your project's root directory.

Database Setup:

Create a MySQL/MariaDB database (e.g., on PlanetScale, local MAMP/XAMPP, or a hosting provider).

Execute the SQL schema for users and ratings tables (provided in project development history).

Environment Variables:

Create a .env file in the project root (or configure environment variables directly on your hosting platform).

Populate it with your database credentials (DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_PORT).

Add your API keys: OMDB_KEY (from OMDB API) and GEMINI_KEY (from Google AI Studio).

Run the Application:

Replit: Ensure replit.nix and .replit are configured correctly (as per development history) and click "Run".

Hosting: Deploy to a PHP-compatible hosting provider (e.g., Render) ensuring the web server's document root points to the public/ directory.

Challenges Faced & Solutions
Developing this application presented several interesting challenges, primarily related to environment configuration and complex user interactions:

Replit PHP Environment Instability:

Challenge: Persistent "bash: php: command not found" errors and stubborn file casing issues (e.g., home.php vs Home.php) on Replit restarts, despite correct file names and code. This indicated deep-seated caching or environment provisioning problems within Replit.

Solution: Implemented a robust replit.nix file to explicitly define PHP and Composer as dependencies, forcing Replit to install them consistently. Aggressive file system cache clearing (renaming/reverting folders) was also employed. A targeted workaround in App.php was added to specifically handle the Home.php casing issue as a last resort.

Clean URL Routing Implementation:

Challenge: Ensuring all application links and form submissions used clean URLs (e.g., /movie/search instead of index.php?url=movie/search) and that the App.php router correctly parsed these URLs, especially within the Replit environment which sometimes adds base paths (/index.php).

Solution: Meticulously refined the parseUrl() method in App.php to handle various $_SERVER['REQUEST_URI'] formats, including stripping index.php and Replit-specific base paths. All links and form action attributes across views and controllers were updated to use the clean URL format.

Database Schema Alignment:

Challenge: Initial ratings table schema was insufficient and mismatched the application's data requirements (e.g., using movie_id as INT instead of imdb_id as VARCHAR, and missing columns for movie_title, poster_url, review_text).

Solution: The ratings table was dropped and recreated with the correct schema, including imdb_id (VARCHAR), movie_title, poster_url, and review_text columns. This ensured data integrity and compatibility with the PHP models.

Interactive AI Review Workflow:

Challenge: Designing a user experience where a rating triggers an AI review, which then becomes editable, and can be saved as a separate "Post Review" action. This required managing state across page reloads and differentiating form submissions.

Solution: Implemented a two-step form submission process using distinct name attributes for the "Submit Rating & Get AI Review" and "Post Review" buttons. The AI-generated review is temporarily stored in the PHP session after the first step. Conditional rendering in the movie/results.php view, coupled with a small JavaScript snippet, dynamically shows/hides the review textarea and "Post Review" button, enabling editing only after the AI review is available or a review already exists.

PHP Syntax Errors:

Challenge: Encountered specific PHP Fatal error: Assignments can only happen to writable values errors due to incorrect array assignment syntax in controller logic.

Solution: Carefully corrected the problematic lines by ensuring values were assigned directly to array keys, rather than attempting to assign to array literals.

Deployment to Production:

Challenge: Realized that Netlify, while excellent for static sites, does not support traditional PHP applications requiring a server-side interpreter.

Solution: Identified and recommended a suitable Platform as a Service (PaaS) like Render, which offers Git-based deployment for PHP applications. Outlined the steps for setting up a managed MySQL database (e.g., PlanetScale) and configuring Render for PHP deployment, including environment variables and start commands.

This project was a valuable exercise in building a full-stack application, highlighting the importance of robust architecture, meticulous debugging, and adapting to platform-specific challenges.
