<?php

use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware' => 'guest'], function () {
    Volt::route('/login', 'login')->name('login');
});

Route::group(['middleware' => ['auth', 'userAccessLog']], function () {
    Route::get('/logout', LogoutController::class)->name('logout');

    Volt::route('/dashboard', 'dashboard')->name('dashboard');

    Route::prefix('masters')->group( function () {
        Volt::route('/users', 'masters.users.index')->middleware('can:manage-users')->name('users');
        Volt::route('/faculties', 'masters.faculties.index')->middleware('can:manage-faculties')->name('faculties');
        Volt::route('/departments', 'masters.departments.index')->middleware('can:manage-departments')->name('departments');
        Volt::route('/students', 'masters.students.index')->middleware('can:manage-students')->name('students');
        Volt::route('/lecturers', 'masters.lecturers.index')->middleware('can:manage-lecturers')->name('lecturers');
        Volt::route('/topics', 'masters.topics.index')->middleware('can:manage-theses')->name('topics');
    });

    Route::prefix('menus')->group(function () {
        Volt::route('/theses', 'menus.theses.index')->middleware('can:manage-theses')->name('theses');
        Volt::route('/theses/{thesis}', 'menus.theses.detail')->middleware('can:manage-theses')->name('thesis.detail');
    });

    Volt::route('/roles', 'settings.roles.index')->middleware('can:manage-roles')->name('roles');

    Volt::route('/permissions', 'settings.permissions.index')->middleware('can:manage-permissions')->name('permissions');
});
