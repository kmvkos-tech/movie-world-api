# MovieWorld

MovieWorld is a social sharing platform where users can share their favorite movies, express their opinions by liking or hating movies, and explore movies added by other users. This README provides instructions for setting up, running, and understanding the application.

---

## Features

1. **User Authentication**
    - Sign up and log in for authorized access.
    - API authentication using Laravel Sanctum.

2. **Movies**
    - Users can add their favorite movies.
    - Movies include title, description, date of publication, user information, and voting stats (likes and hates).
    - Unauthorized users can view movies but cannot add or vote.

3. **Voting**
    - Authorized users can like or hate movies.
    - Users cannot vote on their own movies.
    - Voting can be changed or retracted.

4. **Movie List**
    - All movies can be viewed with details such as title, description, user, publication date, and votes.
    - Movies can be sorted by likes, hates, or publication date.
    - Filter movies by specific users.

---

## Installation


### Steps

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/movieworld.git
   cd movieworld
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Set up environment variables:
   ```bash
   cp .env.example .env
   ```
   Configure the `.env` file with your database and other application settings.

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```

6. Run the application:
   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`.

---

## API Endpoints

### Authentication
- **POST /api/register**: Register a new user.
- **POST /api/login**: Log in a user and obtain a token.
- **POST /api/logout**: Log out the authenticated user.

### Movies
- **GET /api/movies**: Get the list of movies with optional sorting (`likes`, `hates`, `publication_date`).
- **GET /api/movies/user/{userId}**: Get movies added by a specific user.
- **POST /api/movies**: Add a new movie (authenticated users only).

### Voting
- **POST /api/movies/{movieId}/vote**: Like or hate a movie (authenticated users only).
- **DELETE /api/movies/{movieId}/vote**: Retract a vote (authenticated users only).

---

## Database Structure

### Tables
1. **users**: Stores user information (name, email, password).
2. **movies**: Stores movie details (title, description, publication date, user_id).
3. **votes**: Tracks user votes (movie_id, user_id, vote_type).

---

## Dummy Data
To populate the database with test data, use Laravel factories and seeders:
```bash
php artisan db:seed
```
- **20 users**: Each user has 10 movies.
- **200 movies**: Each movie has 10 votes.
- **2000 votes**: Distributed among users, ensuring no self-voting.

Login credentials for dummy users:
- **Email**: email of the dummy user (Inspect in network of the browser to see the response of the api/movie to get a users' email)
- **Password**: password
---

## Testing

### Manual Testing
Use tools like **Postman** to test API endpoints.

### Automated Testing
Run PHPUnit tests to ensure functionality:
```bash
php artisan test
```

---

## Future Enhancements
- Implement pagination for movie lists.
- SWAGGER documentation for API endpoints.
- Decouple Eloquent models from controllers using repositories.


---


