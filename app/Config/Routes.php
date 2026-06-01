<?php

namespace Config;

use CodeIgniter\Config\Services;

$routes = Services::routes();

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('CenterAuth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); 

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Default Route
$routes->get('/', function () {
    return redirect()->to('/login');
});

// ================= AUTH ROUTES =================
$routes->get('login', 'CenterAuth::login');
$routes->post('login-process', 'CenterAuth::loginProcess');
$routes->post('superadmin-register', 'CenterAuth::registerSuperAdmin');
$routes->get('logout', 'CenterAuth::logout');

// ================= SUPER ADMIN ROUTES =================
$routes->group('superadmin', ['filter' => 'auth'], function ($routes) {

    // DASHBOARD
    $routes->get('dashboard', 'SuperAdmin::dashboard');

    // EXPORT ROUTES
    $routes->get('all-students-export-excel', 'SuperAdmin::allStudentsExportExcel');
    $routes->get('all-students-export-pdf', 'SuperAdmin::allStudentsExportPDF');

    // ENROLLMENTS
    $routes->get('enrollments', 'SuperAdmin::enrollments');
    $routes->get('toggle-enrollment/(:num)', 'SuperAdmin::toggleEnrollment/$1');

    // CENTERS
    $routes->get('centers', 'SuperAdmin::centers');
    $routes->match(['get','post'], 'add-center', 'SuperAdmin::addCenter');
    $routes->match(['get','post'], 'edit-center/(:num)', 'SuperAdmin::editCenter/$1');
    $routes->get('delete-center/(:num)', 'SuperAdmin::deleteCenter/$1');
    $routes->post('update-center-status/(:num)', 'SuperAdmin::updateCenterStatus/$1');

    // PERMISSIONS & MESSAGING
    $routes->post('save-permissions/(:num)', 'SuperAdmin::savePermissions/$1');
    $routes->post('send-center-msg', 'SuperAdmin::sendCenterMsg');

    // COURSES
    $routes->get('courses', 'SuperAdmin::courses');
    $routes->match(['get','post'], 'add-course', 'SuperAdmin::addCourse');
    $routes->match(['get','post'], 'edit-course/(:num)', 'SuperAdmin::editCourse/$1');
    $routes->get('delete-course/(:num)', 'SuperAdmin::deleteCourse/$1');

    // STUDENTS (✅ ADDED MISSING EDIT/VIEW ROUTES TO FIX 404)
    $routes->get('all-students', 'SuperAdmin::allStudents');
    $routes->match(['get','post'], 'edit-student/(:num)', 'SuperAdmin::editStudent/$1'); // Birthday card click fix
    $routes->get('view-student/(:num)', 'SuperAdmin::viewStudent/$1');
    $routes->get('delete-student/(:num)', 'SuperAdmin::deleteStudent/$1');
    
    // ✅ MASTER UPDATE & MAIL ROUTES
    $routes->post('update-student-master', 'SuperAdmin::update_student_master');
    $routes->post('send-custom-mail', 'SuperAdmin::send_custom_mail');
    $routes->post('send-student-notification', 'SuperAdmin::send_student_notification');

    // PROFILE & PASSWORD
    $routes->get('profile', 'SuperAdmin::profile');
    $routes->match(['get','post'], 'edit-profile', 'SuperAdmin::editProfile');
    $routes->get('change-password', 'SuperAdmin::changePassword');
    $routes->post('update-password', 'SuperAdmin::updatePassword');

    // AJAX API
    $routes->get('get-course-info/(:num)', 'SuperAdmin::getCourseInfo/$1'); 
    $routes->get('get-center-students/(:num)', 'SuperAdmin::getCenterStudents/$1');

    // OTHERS & GLOBAL SEARCH
    $routes->get('global-search', 'SuperAdmin::globalSearch');
    $routes->post('get-details-by-id', 'SuperAdmin::getDetailsById'); 

    /**
     * ✅ NOTIFICATION SYSTEM ROUTES (SUPERADMIN)
     */
    $routes->get('notifications', 'SuperAdmin::viewNotifications'); 
    $routes->get('view-notification/(:num)', 'SuperAdmin::viewNotification/$1');
    $routes->get('mark-notification/(:num)', 'SuperAdmin::markNotification/$1'); 
    $routes->get('mark-all-read', 'SuperAdmin::markAllNotifications'); 
    $routes->get('delete-notification/(:num)', 'SuperAdmin::deleteNotification/$1'); 
    $routes->get('clear-notifications', 'SuperAdmin::clearAllNotifications'); 

    /**
     * ✅ UPCOMING EVENTS & EXPIRY ROUTES (SUPERADMIN)
     */
    $routes->get('upcoming-events', 'SuperAdmin::upcoming_events'); 
});

// ================= CENTER ROUTES =================
$routes->group('center', ['filter' => 'auth'], function ($routes) {

    // Main Dashboard
    $routes->get('dashboard', 'CenterDashboard::index');
    
    // DASHBOARD FILTER ROUTES
    $routes->get('completed', 'CenterDashboard::completedStudents');
    $routes->get('in-progress', 'CenterDashboard::inProgressStudents');

    /**
     * ✅ MESSAGE & NOTIFICATION LOGIC (CENTER)
     */
    $routes->get('markMsgRead/(:num)', 'CenterDashboard::markMsgRead/$1');
    $routes->get('deleteMsg/(:num)', 'CenterDashboard::deleteMsg/$1');
    $routes->get('notifications', 'CenterDashboard::notifications');
    
    /**
     * ✅ UPCOMING EVENTS & BIRTHDAYS (CENTER)
     */
    $routes->get('upcoming-events', 'CenterDashboard::upcomingEvents');

    // STUDENTS & ENROLLMENTS
    $routes->get('students', 'CenterDashboard::students');
    $routes->get('add-student', 'CenterDashboard::addStudent');
    $routes->post('save-student', 'CenterDashboard::saveStudent');
    $routes->get('edit-student/(:num)', 'CenterDashboard::editStudent/$1');
    $routes->post('update-student/(:num)', 'CenterDashboard::updateStudent/$1');
    $routes->get('delete-student/(:num)', 'CenterDashboard::deleteStudent/$1');
    
    // Status Update for AJAX
    $routes->post('update-course-status/(:num)', 'CenterDashboard::updateCourseStatus/$1');
    
    // COURSES & ENROLLMENTS
    $routes->get('courses', 'CenterDashboard::courses');
    $routes->get('enrollments', 'CenterDashboard::enrollments');
    $routes->get('my-courses', 'CenterDashboard::courses'); 

    // AJAX AUTO-FILL
    $routes->get('get-course-details/(:num)', 'CenterDashboard::getCourseDetails/$1');

    // PROFILE, PASSWORD & SETTINGS
    $routes->get('profile', 'CenterDashboard::profile');
    $routes->match(['get','post'], 'edit-profile', 'CenterDashboard::editProfile');
    $routes->get('change-password', 'CenterDashboard::changePassword');
    $routes->post('update-password', 'CenterDashboard::updatePassword');
    $routes->get('settings', 'CenterDashboard::settings'); 
});

/**
 * ✅ GLOBAL FALLBACK FIX
 */
$routes->get('superadmin/upcoming-events', 'SuperAdmin::upcoming_events', ['filter' => 'auth']);