# IUT Weather Project Documentation

## Project Overview
IUT Weather is a Laravel-based web application that provides weather information using the OpenWeather API. The application allows users to manage their favorite cities and receive weather forecasts.

## Project Setup

### Requirements
- PHP 8.2 or higher
- Docker and Docker Compose
- OpenWeather API key
- Node.js and npm

### Installation Steps
1. Clone the repository
2. Copy `.env.example` to `.env` and configure:
   - Set your OpenWeather API key in `OPENWEATHER_API_KEY`
   - Configure mail settings for forecast notifications
3. Run Docker containers:
   ```bash
   docker-compose up -d
   ```
4. Install dependencies:
   ```bash
   composer install
   npm install
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Run migrations:
   ```bash
   php artisan migrate
   ```
7. Build assets:
   ```bash
   npm run build
   ```

## Features

### Authentication
- User registration and login managed by Laravel Breeze
- Email verification system
- Password reset functionality

### Weather Information
- Current weather display
- 5-day weather forecast
- Export weather data to CSV
- City coordinates lookup

### User City Management
- Add/remove cities
- Set favorite city (one per user)
- Enable/disable forecast notifications per city
- View list of saved cities

## API Routes

### Authentication
```
POST /api/auth/token
Parameters:
- email: string
- password: string
Returns: Bearer token
```

### Weather Endpoints

#### Current Weather
```
GET /api/v1/weather
Headers: Authorization: Bearer {token}
Query Parameters:
- place: string (city name)
Returns: Current weather data
```

#### Weather Forecast
```
GET /api/v1/forecast
Headers: Authorization: Bearer {token}
Query Parameters:
- place: string (city name)
Returns: 5-day forecast data
```

### User Cities Endpoints

#### List User Cities
```
GET /api/v1/users/places
Headers: Authorization: Bearer {token}
Returns: Paginated list of user's cities
```

#### Add City
```
POST /api/v1/users/places
Headers: Authorization: Bearer {token}
Body:
- city: string
Returns: Created city data
```

#### Toggle Forecast Notifications
```
PATCH /api/v1/users/places/{place}/send-forecast
Headers: Authorization: Bearer {token}
Returns: Updated city data
```

#### Toggle Favorite City
```
PATCH /api/v1/users/places/{place}/favorite
Headers: Authorization: Bearer {token}
Returns: Updated city data
```

#### Remove City
```
DELETE /api/v1/users/places/{place}
Headers: Authorization: Bearer {token}
Returns: Success message
```

## Console Commands

### Send Weather Forecasts
```bash
php artisan weather:send-forecasts
```
This command:
- Runs daily at 06:00 (configured in Kernel.php)
- Sends forecast notifications to users who enabled them
- Generates and attaches CSV files with forecast data

### Show Current Weather
```bash
php artisan weather:show {city}
```
This command:
- Displays current weather for specified city in terminal
- Shows temperature, humidity, wind speed, and other metrics

## Database Structure

### Users Table
- id (primary key)
- name
- email (unique)
- password
- email_verified_at
- remember_token
- timestamps

### User Cities Table
- id (primary key)
- user_id (foreign key)
- city
- is_favorite (boolean)
- send_forecast (boolean)
- timestamps

## Services

### OpenWeatherService
Located in `app/Services/OpenWeatherService.php`
- Handles all OpenWeather API interactions
- Implements caching for API responses
- Methods:
  - `getWeatherForDate($city, Carbon $date)`
  - `getCoordinates($city)`

## Testing

### Available Test Suites
- Feature Tests:
  - Authentication tests
  - Weather API tests
  - User City management tests
- Unit Tests:
  - OpenWeather service tests
  - Command tests

### Running Tests
```bash
php artisan test
```

## Frontend

### Technologies Used
- TailwindCSS for styling
- Alpine.js for interactivity
- Blade templates for views

### Key Views
- /resources/views/weather/search.blade.php - Weather search interface
- /resources/views/weather/current.blade.php - Weather display
- /resources/views/user_cities/index.blade.php - City management

## Notifications

### Weather Forecast Notification
- Sends daily weather forecasts via email
- Includes CSV attachment with forecast data
- Configurable per city