<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\ChapaController;
use App\Livewire\Course\CoursePlay;
use App\Livewire\HomePage;
use App\Livewire\Subscriptions\ManualPayment;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\ManageCourses;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Course\CourseListing;
use App\Livewire\Course\CourseDetail;
use App\Livewire\Instructor\CourseManagement;
use App\Livewire\User\Profile;
use App\Livewire\User\Follow;
use App\Livewire\Course\Chat;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\User\Dashboard;
use App\Livewire\Instructor\Dashboard as InstructorDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\InstructorApplicationForm;
use App\Livewire\Admin\ManageSubscriptions;
use App\Livewire\Course\Create;
use App\Livewire\Course\Edit;
use App\Livewire\Instructor\ManageContent;
use Illuminate\Support\Facades\Auth;

Route::get('/', HomePage::class)->name('home');

Route::get('/courses', CourseListing::class)->name('courses.index');

Route::get('/courses/{courseId}', CourseDetail::class)->name('course.detail');




Route::middleware('instructor')->group(function () {
    Route::get('/instructor/dashboard', InstructorDashboard::class)->name('instructor.dashboard');
    Route::get('/instructor/courses', CourseManagement::class)->name('instructor.course_management');
    Route::get('/instructor/courses/create', Create::class)->name('courses.create');
    Route::get('/instructor/courses/edit/{courseId}', Edit::class)->name('courses.edit');
    Route::get('/instructor/courses/{course}/manage-content', ManageContent::class)->name('instructor.manage_content');
});


Route::middleware(['admin'])->group(function () {
    Route::get('user/admin/courses', ManageCourses::class)->name('admin.manage_courses');
    Route::get('user/admin/users', ManageUsers::class)->name('admin.manage_users');
    Route::get('user/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/subscriptions', ManageSubscriptions::class)->name('admin.subscriptions'); // for now.. i will change it to filament in near future

});
Route::get('/subscribe', function () {
    return view('subscribe');
})->middleware('auth')->name('subscribe.index');



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('user.dashboard');
    Route::get('/profile/{username}', Profile::class)->name('user.profile');
    Route::get('/follow/{user}', Follow::class)->name('user.follow');
    Route::get('/chat/{course}', Chat::class)->name('user.chat');
    Route::get('/courses/{course}/learn/{lesson?}', CoursePlay::class)->name('course-play');
    Route::get('/courses/{course}/chat', Chat::class)->name('course-chat');
    Route::get('/subscribe/manual', ManualPayment::class)->name('subscribe.manual');
    Route::get('/instructor/apply', InstructorApplicationForm::class)->name('instructor.apply');
});

Route::get('/chapa/callback', [ChapaController::class, 'callback'])->name('chapa.callback');


Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/register', Register::class)->name('register')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/auth/{provider}', [OAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback']);

Route::fallback(function () {
    return response('Route not found. Available routes: ' . implode(', ', \Illuminate\Support\Facades\Route::getRoutes()->getRoutesByMethod()['GET']), 404);
});
