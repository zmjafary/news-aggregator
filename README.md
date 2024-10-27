# News Aggregator API

A powerful and flexible RESTful API for aggregating news articles from various sources including The New York Times, The Guardian, and News API. This application allows users to fetch, store, and personalize their news feed based on their preferences.

## Features

- **User Authentication**: Register, login, and logout functionality using Laravel Sanctum.
- **Fetch Articles**: Scheduled command to fetch articles from multiple news sources and store them in a database.
- **Personalized Feed**: Provide a customized news feed based on user preferences.
- **Caching**: Implements caching for optimized performance and reduced API calls.
- **Filter Articles**: Search and filter articles based on keywords, categories, sources, and published dates.
- **API Documentation**: Automatically generated API documentation using Swagger for easy reference.
- **Testing**: The application includes feature tests for user authentication, article retrieval, and user preference updates.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Technologies](#technologies)
- [License](#license)

## Installation Guide for News Aggregator

### Prerequisites
Ensure you have Docker installed on your machine, as Laravel Sail uses Docker for local development.

### Setup Steps

1. **Clone the repository**:

   ```bash
   git clone https://github.com/zmjafary/news-aggregator.git
   cd news-aggregator
   ```

2. **Install dependencies**:

   If you’re using Sail, you can install Composer dependencies with Sail as follows:

   ```bash
   ./vendor/bin/sail composer install
   ```

3. **Set up the environment**:

   Copy the `.env.example` file to `.env` and set your environment variables.

   ```bash
   cp .env.example .env
   ```

   Make sure to set your API keys for the news services in the `.env` file:

   ```env
   SERVICES_GUARDIAN_KEY=your_guardian_api_key
   SERVICES_NEWSAPI_KEY=your_newsapi_key
   SERVICES_NYT_KEY=your_nyt_api_key
   ```

4. **Generate application key**:

   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

5. **Run migrations**:

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

6. **Run the command to fetch articles** (optional, can be automated):

   ```bash
   ./vendor/bin/sail artisan articles:fetch
   ```

7. **Start the server**:

   You can start your application using Sail:

   ```bash
   ./vendor/bin/sail up
   ```

   This command will start the Docker containers for your application.

### Accessing Your Application

Once the server is running, you can access your application at `http://localhost` and `http://localhost/api/documentation` for SwaggerUI.

---

### Notes
- **Sail Commands**: Remember to prefix commands with `./vendor/bin/sail` when using Sail. This ensures you are executing commands within the context of your Docker containers.
- **Docker**: Make sure Docker is running on your machine before starting Sail.

---

## Usage

### Authentication

- **Register**: `POST /api/register`
  
  **Request Body**:
  
  ```json
  {
      "name": "John Doe",
      "email": "john@example.com",
      "password": "password123"
  }
  ```

- **Login**: `POST /api/login`
  
  **Request Body**:
  
  ```json
  {
      "email": "john@example.com",
      "password": "password123"
  }
  ```

- **Logout**: `POST /api/logout`
  
  **Headers**: 

  ```
  Authorization: Bearer {token}
  ```

### Articles

- **Get Articles**: `GET /api/articles`
  
  **Query Parameters**:
  
  - `keyword`: (optional) Search keyword
  - `category`: (optional) Filter by category
  - `source`: (optional) Filter by source
  - `date`: (optional) Filter by published date

- **Get Article by ID**: `GET /api/articles/{id}`

- **Get Personalized Feed**: `GET /api/articles/personalized`
  
  **Headers**: 

  ```
  Authorization: Bearer {token}
  ```

### User Preferences

- **Update Preferences**: `POST /api/preferences`
  
  **Request Body**:
  
  ```json
  {
      "authors": ["Author1", "Author2"],
      "category": ["Technology", "Health"],
      "source": ["The Guardian", "News API"]
  }
  ```

## API Endpoints

| Method | Endpoint                       | Description                                     |
|--------|-------------------------------|-------------------------------------------------|
| POST   | `/api/register`               | Register a new user.                           |
| POST   | `/api/login`                  | Log in an existing user.                       |
| POST   | `/api/logout`                 | Log out the authenticated user.                |
| GET    | `/api/articles`               | Retrieve a list of articles.                   |
| GET    | `/api/articles/{id}`          | Retrieve a specific article by ID.             |
| GET    | `/api/articles/personalized`   | Retrieve personalized articles based on user preferences. |
| POST   | `/api/preferences`            | Update user preferences.                        |

## Technologies

- **Laravel**: PHP framework used for the application.
- **Sanctum**: For API token authentication.
- **MySQL**: Database for storing articles and user data.
- **Cache**: Laravel caching mechanism for performance optimization.
- **Guzzle/HTTP Client**: For making HTTP requests to external news APIs.
- **Swagger**: For generating API documentation.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.