✨ Cine Review: Your Movie Reviews ✨


🎬 Project Overview
Cine Review is a dynamic web application for movie enthusiasts. It allows users to search for films, view detailed information, explore community ratings, and for logged-in members, to contribute their own ratings and AI-assisted reviews. Built with a clean, minimalistic design, it showcases core web development principles and API integrations.


🌟 Key Features
Movie Search & Details: Find movies and view comprehensive information (poster, plot, cast, etc.) powered by the OMDB API.

User Authentication: Secure user registration, login, and logout.

Guests: Can search, view details, and see all ratings/reviews.

Logged-in Users: Can submit ratings and create/edit AI-generated reviews.


Interactive Rating & Review System:

Submit 1-5 star ratings.

Get AI-generated review suggestions (from Google Gemini API) based on your rating.

Edit and post your final review to the database.


Community Reviews: See all user-submitted text reviews displayed publicly.

Clean UI & Navigation: Minimalist design with fixed header/footer and intuitive URLs.

Live Footer: Displays current date and time.



🛠️ Technical Stack
Backend: PHP (MVC Architecture)

Database: MySQL / MariaDB (via PDO)

Frontend: HTML5, CSS3, JavaScript, Bootstrap 5

APIs: OMDB API, Google Gemini API



🚧 Development Journey: Challenges & Solutions

Building Cine Review involved overcoming several key hurdles:

Replit Environment Issues: Persistent PHP "command not found" errors and file casing problems were resolved by explicit Nix configurations and targeted code workarounds.

Robust URL Routing: Ensuring clean, consistent URL parsing across different server environments.

Dynamic UI for Reviews: Implementing a two-step process for AI review generation and subsequent user editing/saving, requiring careful state management.

Database Schema Alignment: Correcting and evolving the database structure to support all application features.

Deployment to PaaS: Transitioning from a local development environment to a production-ready PHP hosting platform.



🚀 Getting Started
Clone: Get the project code from GitHub.

Install: Run composer install.

Database: Set up MySQL/MariaDB and run the SQL schema for users and ratings tables.

Configure: Set your DB_HOST, DB_NAME, DB_USER, DB_PASS, DB_PORT, OMDB_KEY, and GEMINI_KEY as environment variables on your hosting platform.

Launch: Deploy to a PHP-compatible PaaS (like Render) or run locally (e.g., on Replit).
