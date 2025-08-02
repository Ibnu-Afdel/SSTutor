# SSTutor - Online Learning Platform

SSTutor is a comprehensive online learning platform built with Laravel, Livewire, and Filament that connects students with instructors through interactive courses.

## Features

### For Students
- **Course Enrollment**: Browse and enroll in various courses
- **Interactive Learning**: Access organized lessons and course materials
- **Progress Tracking**: Track completion of lessons
- **Community**: Chat with instructors and other students
- **Reviews**: Leave reviews for completed courses
- **User Profiles**: Personalized dashboards and profiles
- **Follow System**: Follow favorite instructors

### For Instructors
- **Course Management**: Create, edit, and manage courses
- **Content Organization**: Organize lessons into sections
- **Student Engagement**: Chat with enrolled students
- **Course Analytics**: Track enrollments and student progress

### For Administrators
- **User Management**: Manage students and instructors
- **Course Oversight**: Monitor and manage all courses
- **Instructor Applications**: Review and approve instructor applications
- **Subscription Management**: Manage subscription plans and payments

## Technology Stack

- **Backend**: Laravel 11.x
- **Frontend**: Livewire 3.x, TailwindCSS
- **Admin Panel**: Filament
- **Authentication**: Laravel Authentication with OAuth support
- **Database**: pgsql
- **Payment Integration**: Chapa Payment Gateway
- **File Storage**: Local Storage (Laravel)
- **Deployment**: Heroku-ready

## Installation

1. Clone the repository
   ```
   git clone https://github.com/ibnu-afdel/sstutor.git
   cd sstutor
   ```

2. Install dependencies
   ```
   composer install
   npm install
   ```

3. Set up environment configuration
   ```
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database in the .env file
   ```
   DB_CONNECTION=pgsql
   DB_HOST=localhost
   DB_PORT=5432
   DB_DATABASE=your-db-name
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Create the storage symbolic link for file uploads
   ```
   php artisan storage:link
   ```

6. Run migrations and seed the database
   ```
   php artisan migrate --seed
   ```

7. Compile assets
   ```
   npm run dev
   ```

8. Start the server
   ```
   php artisan serve
   ```

9. Visit http://localhost:8000 in your browser

## Deployment

The application is configured for Heroku deployment with the following:

- Procfile for Apache configuration
- Composer and NPM scripts for post-install actions
- Node.js and NPM version requirements in package.json

## Development Roadmap

- Payment integration with multiple providers
- Real-time notifications
- OAuth integrations
- Multi-tenancy support
- Advanced admin analytics
- Mobile application

## License

The SSTutor platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
