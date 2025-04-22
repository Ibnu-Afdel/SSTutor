<?php

use App\Livewire\Course\CoursePlay;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\ManageCourses;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Course\CourseListing;
use App\Livewire\Course\CourseDetail;
// use App\Livewire\Course\Enrollment;
use App\Livewire\Course\Lesson;
use App\Livewire\Course\Review;
use App\Livewire\Instructor\CourseManagement;
use App\Livewire\User\Profile;
use App\Livewire\User\Follow;
use App\Livewire\Course\Chat;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\User\Dashboard;
use App\Livewire\Instructor\Dashboard as InstructorDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Course\CourseEnrollment;
use App\Livewire\Course\Create;
use App\Livewire\Course\Edit;
use App\Livewire\Instructor\ManageContent;
use Illuminate\Support\Facades\Auth;

// Home Page Route
Route::get('/', HomePage::class)->name('home');


// Courses
Route::get('/courses', CourseListing::class)->name('courses.index'); // List all courses

Route::get('/courses/{courseId}', CourseDetail::class)->name('course.detail'); // Course details page

// create and edit

// Route::get('/courses/edit/{course}', Edit::class)->name('courses.edit'); // Edit an existing course

//EDIT       Route::get('/courses/edit/{courseId}', Edit::class)->name('courses.edit')->where('course', '[0-9]+');

// Enrollments
Route::get('/courses/{course}/enroll', CourseEnrollment::class)->name('course.enrollment'); // Handle course enrollment

// Lessons
Route::get('/courses/{course}/lessons/{lesson}', Lesson::class)->name('course.lesson'); // View specific lesson

// Reviews
Route::get('/courses/{course}/reviews', Review::class)->name('course.reviews'); // View and add course reviews

// Instructor Dashboard
Route::middleware('instructor')->group(function () {
    Route::get('/instructor/dashboard', InstructorDashboard::class)->name('instructor.dashboard'); // Instructor's dashboard
    Route::get('/instructor/courses', CourseManagement::class)->name('instructor.course_management'); // Manage courses
    Route::get('/instructor/courses/create', Create::class)->name('courses.create'); // Create a new course
    Route::get('/instructor/courses/edit/{courseId}', Edit::class)->name('courses.edit');
    Route::get('/instructor/courses/{course}/manage-content', ManageContent::class)->name('instructor.manage_content');
});


// Admin Routes (Only accessible by admin users)
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/courses', ManageCourses::class)->name('admin.manage_courses'); // Admin managing courses
    Route::get('/admin/users', ManageUsers::class)->name('admin.manage_users'); // Admin managing users
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
});

// User Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('user.profile'); // User's profile
    Route::get('/follow/{user}', Follow::class)->name('user.follow'); // Follow/Unfollow functionality
    Route::get('/chat/{course}', Chat::class)->name('user.chat'); // Chat between students of the same course
    // Route::get('/courses/{course}/learn', CoursePlay::class)->name('course-play');
    Route::get('/courses/{course}/learn/{lesson?}', CoursePlay::class)->name('course-play');
});



// Authentication Routes
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/register', Register::class)->name('register')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Role-based Dashboard Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', Dashboard::class)->name('user.dashboard');

    // Route::group(['middleware' => 'admin'], function () {
    //     Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    // });

    // Route::group(['middleware' => 'instructor'], function () {
    //     Route::get('/instructor/dashboard', InstructorDashboard::class)->name('instructor.dashboard');
    // });
});

Route::get('/courses/{course}/lessons', Lesson::class)->name('courses.lessons');
Route::get('/courses/{course}/enroll', CourseEnrollment::class)->name('courses.enroll');
