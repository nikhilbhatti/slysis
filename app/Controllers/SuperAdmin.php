<?php

namespace App\Controllers;

use App\Models\CenterModel;
use App\Models\StudentModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\SuperAdminModel;
use App\Models\NotificationModel;

// ================= FOR EXPORT =================
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class SuperAdmin extends BaseController
{
    protected $superAdminModel;
    protected $session;

    public function __construct()
    {
        $this->session = session();
        $this->superAdminModel = new SuperAdminModel();

        // ✅ LOGIN CHECK
        if (!$this->session->get('superadmin_id')) {
            redirect()->to('/login')->send();
            exit;
        }
    }

    // ================= RENDER VIEW WITH NOTIFICATIONS =================
    protected function renderView($view, $data = [])
    {
        // Add notifications to all views
        $data['notifications'] = $this->getNotifications(5);
        $data['unreadCount'] = $this->getUnreadCount();
        
        return view($view, $data);
    }

    // ================= DASHBOARD - WITH REAL COURSE COUNT =================
public function dashboard()
{
    $centerModel     = new CenterModel();
    $studentModel    = new StudentModel();
    $courseModel     = new CourseModel();
    $enrollmentModel = new EnrollmentModel();
    $db = \Config\Database::connect();

    // --- 1. BIRTHDAY LOGIC ---
    $todayDate = date('m-d');
    $sevenDaysLater = date('m-d', strtotime('+7 days'));

    $birthdaySql = "SELECT students.id, students.student_name, centers.center_name, students.dob,
                    DATE_FORMAT(students.dob, '%m-%d') as b_date
                    FROM students 
                    LEFT JOIN centers ON centers.id = students.center_id 
                    WHERE DATE_FORMAT(students.dob, '%m-%d') >= ? 
                    AND DATE_FORMAT(students.dob, '%m-%d') <= ? 
                    ORDER BY b_date ASC";

    $birthday_students = $db->query($birthdaySql, [$todayDate, $sevenDaysLater])->getResultArray();

    // --- 2. CALLING UPCOMING EXPIRIES ---
    $upcoming_events = $this->getUpcomingExpiries();

    // --- 3. RECENT CENTERS LOGIC ---
    $recentCenters = $centerModel->where('status', 1)
        ->orderBy('id', 'DESC')
        ->limit(5)
        ->findAll();

    foreach ($recentCenters as $key => $center) {
        $recentCenters[$key]['student_count'] = $studentModel
            ->where('center_id', $center['id'])
            ->countAllResults();

        $recentCenters[$key]['course_count'] = $courseModel
            ->where('center_id', $center['id'])
            ->countAllResults();
    }

    // --- 4. PREPARING DATA ARRAY (Updated logic for Unique Students) ---
    $data = [
        'pageTitle'         => 'Super Admin Dashboard',
        'totalCenters'      => $centerModel->where('status', 1)->countAllResults(),
        
        // UNIQUE STUDENT COUNT: Enrollments table se unique student_id count karne ke liye
        'totalStudents'     => $db->table('enrollments')
                                 ->select('student_id')
                                 ->distinct()
                                 ->countAllResults(),

        'totalCourses'      => $courseModel->countAllResults(),
        
        // ACTIVE ENROLLMENTS: Ye hamesha 113 (Total) hi dikhayega
        'totalEnrollments'  => $enrollmentModel->where('status', 'active')->countAllResults(),
        
        'recentCenters'     => $recentCenters,
        'recentStudents'    => $studentModel->orderBy('id', 'DESC')->limit(5)->findAll(),
        'recentCourses'     => $courseModel->orderBy('id', 'DESC')->limit(5)->findAll(),
        'birthday_students' => $birthday_students,
        'upcoming_events'   => $upcoming_events, 
        'enrollments'       => $enrollmentModel
            ->select('enrollments.id, enrollments.status, enrollments.enroll_date, enrollments.expiry_date, students.student_name, courses.course_name, centers.center_name')
            ->join('students', 'students.id = enrollments.student_id', 'left')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->join('centers', 'centers.id = enrollments.center_id', 'left')
            ->orderBy('enrollments.id', 'DESC')
            ->findAll()
    ];

    return $this->renderView('superadmin/dashboard', $data);
}
// ================= NEW SEPARATE FUNCTION =================
private function getUpcomingExpiries()
{
    $db = \Config\Database::connect();
    $currentDate = date('Y-m-d');
    $sevenDaysLater = date('Y-m-d', strtotime('+7 days'));

    return $db->table('enrollments')
        ->select('enrollments.*, students.student_name, courses.course_name, centers.center_name')
        ->join('students', 'students.id = enrollments.student_id', 'left')
        ->join('courses', 'courses.id = enrollments.course_id', 'left')
        ->join('centers', 'centers.id = enrollments.center_id', 'left')
        ->where('enrollments.expiry_date >=', $currentDate)
        ->where('enrollments.expiry_date <=', $sevenDaysLater)
        ->orderBy('enrollments.expiry_date', 'ASC')
        ->get()
        ->getResultArray();
}
// ================= UPCOMING EVENTS PAGE =================
// ================= UPCOMING EVENTS PAGE =================
public function upcoming_events()
{
    $db = \Config\Database::connect();
    
    // 1. Birthday Range Logic (Today to next 7 days)
    $today = date('m-d');
    $sevenDaysLater = date('m-d', strtotime('+7 days'));

    // RAW SQL Query - Same logic as your CenterDashboard to fix syntax errors
    $sql = "SELECT students.student_name, centers.center_name, students.dob, students.phone 
            FROM students 
            LEFT JOIN centers ON centers.id = students.center_id 
            WHERE DATE_FORMAT(students.dob, '%m-%d') >= ? 
            AND DATE_FORMAT(students.dob, '%m-%d') <= ? 
            ORDER BY DATE_FORMAT(students.dob, '%m-%d') ASC";

    // Executing the query
    $birthdays = $db->query($sql, [$today, $sevenDaysLater])->getResultArray();

    // 2. Data Array
    $data = [
        'pageTitle'         => 'Upcoming Expiries & Birthdays',
        'upcoming_events'   => $this->getUpcomingExpiries(), // Aapka purana function exiries ke liye
        'birthdays'         => $birthdays, // Variable name matches your view file
    ];

    return $this->renderView('superadmin/upcoming_events', $data);
}
// Birthday logic ko reuse karne ke liye alag function (Optional but better)
private function getBirthdayStudents()
{
    $studentModel = new \App\Models\StudentModel();
    $today = date('m-d');
    return $studentModel
        ->select('students.student_name, centers.center_name, students.dob, students.phone')
        ->join('centers', 'centers.id = students.center_id', 'left')
        ->where("DATE_FORMAT(students.dob, '%m-%d')", $today)
        ->findAll();
}
    // ================= CENTERS =================
    public function centers()
    {
        $model = new CenterModel();
        
        // Sab centers ke saath student aur course count
        $centers = $model->findAll();
        
        $studentModel = new StudentModel();
        $courseModel = new CourseModel();
        
        foreach ($centers as $key => $center) {
            $centers[$key]['student_count'] = $studentModel->where('center_id', $center['id'])->countAllResults();
            $centers[$key]['course_count'] = $courseModel->where('center_id', $center['id'])->countAllResults();
        }
        
        $data['centers'] = $centers;
        $data['pageTitle'] = 'Manage Centers';
        
        return $this->renderView('superadmin/centers', $data);
    }

    // ================= SAVE CENTER PERMISSIONS =================
    public function savePermissions($centerId)
    {
        $db = \Config\Database::connect();

        $permissions = $this->request->getPost('perm') ?? $this->request->getPost('permissions');

        // Purani permissions delete karein
        $db->table('center_permissions')->where('center_id', $centerId)->delete();

        // Nayi permissions insert karein agar array khali nahi hai
        if (!empty($permissions) && is_array($permissions)) {
            foreach ($permissions as $perm) {
                $db->table('center_permissions')->insert([
                    'center_id'   => $centerId,
                    'module_name' => $perm,
                    'is_allowed'  => 1,
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }
            $this->addNotification('Permissions Updated', 'Center permissions have been updated.', 'permission');
            return redirect()->back()->with('success', 'Permissions updated successfully!');
        }

        $this->addNotification('Permissions Removed', 'All permissions removed for center.', 'permission');
        return redirect()->back()->with('success', 'All permissions removed for this center.');
    }

    // ================= SEND MESSAGE TO CENTER (24hrs Auto-cut Logic) =================
    public function sendCenterMsg()
    {
        $db = \Config\Database::connect();
        $centerId = $this->request->getPost('center_id');
        $message  = $this->request->getPost('message');

        $data = [
            'center_id'  => $centerId,
            'message'    => $message,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'status'     => 'unread'
        ];

        $db->table('center_messages')->insert($data);
        
        $this->addNotification('New Message', 'Message sent to center. It will expire in 24 hours.', 'message');
        return redirect()->back()->with('success', 'Message sent to center. It will expire in 24 hours.');
    }

    // ================= AJAX: GET LATEST 5 STUDENTS FOR CENTER =================
    public function getCenterStudents($centerId)
    {
        $studentModel = new StudentModel();

        $students = $studentModel
            ->select('id, student_name, email, phone, enrollment_no, created_at')
            ->where('center_id', $centerId)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->findAll();

        return $this->response->setJSON($students);
    }

    // ================= ADD CENTER =================
    public function addCenter()
{
    $model = new CenterModel();

    if ($this->request->getMethod(true) === 'POST') {
        $validationRules = [
            'center_name' => 'required',
            'center_code' => 'required|is_unique[centers.center_code]',
            'address'     => 'required',
            'phone'       => 'required',
            'email'       => 'required|valid_email|is_unique[centers.email]',
            'password'    => 'required|min_length[6]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $db = \Config\Database::connect();

        // ✅ Aapki users table mein check kar rahe hain
        $adminExists = $db->table('users') 
                          ->where('email', $email)
                          ->get()
                          ->getRow();

        if ($adminExists) {
            // English Warning Message
            return redirect()->back()
                ->withInput()
                ->with('error', 'This email is already registered as an Admin account. Please use a different email for the Center.');
        }

        $data = [
            'center_name' => $this->request->getPost('center_name'),
            'center_code' => $this->request->getPost('center_code'),
            'address'     => $this->request->getPost('address'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $email,
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'status'      => 1
        ];

        $inserted = $model->insert($data);

        if ($inserted) {
            $this->addNotification('New Center Added', 'A new center "' . $data['center_name'] . '" has been added.', 'center');
            return redirect()->to(base_url('superadmin/centers'))->with('success', 'Center added successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add center!');
        }
    }

    $data['pageTitle'] = 'Add Center';
    return $this->renderView('superadmin/add-center', $data);
}
    // ================= EDIT CENTER =================
 public function editCenter($id)
{
    $model  = new CenterModel();
    $center = $model->find($id);

    if (!$center) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

    if ($this->request->getMethod(true) === 'POST') {
        $email = $this->request->getPost('email');

        $validationRules = [
            'center_name' => 'required',
            'address'     => 'required',
            'phone'       => 'required',
            'email'       => 'required|valid_email'
        ];

        // Agar email change ho rahi hai toh centers table mein uniqueness check karein
        if ($email != $center['email']) {
            $validationRules['email'] .= '|is_unique[centers.email]';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- CROSS-TABLE CHECK START (Using 'users' table) ---
        $db = \Config\Database::connect();
        $adminExists = $db->table('users') 
                          ->where('email', $email)
                          ->get()
                          ->getRow();

        if ($adminExists) {
            // English Warning Message
            return redirect()->back()
                ->withInput()
                ->with('error', 'This email is already registered as an Admin account. Please use a different email for the Center.');
        }
        // --- CROSS-TABLE CHECK END ---

        $update = [
            'center_name' => $this->request->getPost('center_name'),
            'address'     => $this->request->getPost('address'),
            'phone'       => $this->request->getPost('phone'),
            'email'       => $email
        ];

        if ($this->request->getPost('password')) {
            $update['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $model->update($id, $update);
        
        $this->addNotification('Center Updated', 'Center "' . $update['center_name'] . '" has been updated.', 'center');
        return redirect()->to(base_url('superadmin/centers'))->with('success', 'Center updated successfully!');
    }

    $data['center'] = $center;
    $data['pageTitle'] = 'Edit Center';
    return $this->renderView('superadmin/edit-center', $data);
}
    // ================= UPDATE CENTER STATUS =================
    public function updateCenterStatus($id)
    {
        $centerModel = new CenterModel();
        $center = $centerModel->find($id);

        if (!$center) {
            return redirect()->to(base_url('superadmin/centers'))->with('error', 'Center not found!');
        }

        $status = $this->request->getPost('status');
        if ($status === null) {
            $status = $center['status'] == 1 ? 0 : 1;
        }

        $centerModel->update($id, ['status' => $status]);
        
        $statusText = $status == 1 ? 'activated' : 'deactivated';
        $this->addNotification('Center Status Updated', 'Center "' . $center['center_name'] . '" has been ' . $statusText . '.', 'center');
        
        return redirect()->to(base_url('superadmin/centers'))->with('success', 'Center status updated successfully!');
    }

    // ================= DELETE CENTER =================
    public function deleteCenter($id)
    {
        $centerModel     = new CenterModel();
        $studentModel    = new StudentModel();
        $courseModel     = new CourseModel();
        $enrollmentModel = new EnrollmentModel();

        $center = $centerModel->find($id);
        if (!$center) {
            return redirect()->to(base_url('superadmin/centers'))->with('error', 'Center not found!');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $enrollmentModel->where('center_id', $id)->delete();
        $courseModel->where('center_id', $id)->delete();
        $studentModel->where('center_id', $id)->delete();
        $centerModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to(base_url('superadmin/centers'))->with('error', 'Failed to delete center and related data!');
        }

        $this->addNotification('Center Deleted', 'Center "' . $center['center_name'] . '" and all related data have been deleted.', 'center');
        return redirect()->to(base_url('superadmin/centers'))->with('success', 'Center and all related data deleted successfully!');
    }

    // ================= COURSES =================
    public function courses()
    {
        $courseModel = new CourseModel();
        $data['courses'] = $courseModel
            ->select('courses.*, centers.center_name')
            ->join('centers', 'centers.id = courses.center_id', 'left')
            ->orderBy('courses.id', 'DESC')
            ->findAll();

        $data['pageTitle'] = 'Manage Courses';
        return $this->renderView('superadmin/courses', $data);
    }

    // ================= GET COURSE INFO (AJAX) =================
    public function getCourseInfo($id)
    {
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);
        if ($course) {
            return $this->response->setJSON($course);
        }
        return $this->response->setJSON(['error' => 'Not found'], 404);
    }

    // ================= ADD COURSE =================
   public function addCourse()
{
    $centerModel = new CenterModel();
    $courseModel = new CourseModel();

    $data['centers'] = $centerModel->where('status', 1)->findAll();
    $data['pageTitle'] = 'Add Course';

    if ($this->request->getMethod(true) === 'POST') {

        // Updated Validation Rules
        $validationRules = [
            'course_name'     => 'required|min_length[3]',
            'center_id'       => 'required|integer',
            'course_duration' => 'required', // Yahan se numeric hata diya taaki text allow ho sake
            'fee'             => 'required|numeric',
        ];

        // Custom Error Messages (Optional but helpful)
        $customErrors = [
            'course_duration' => [
                'required' => 'Please enter the course duration (e.g., 6 Months).'
            ]
        ];

        if (!$this->validate($validationRules, $customErrors)) {
            // Agar validation fail ho toh errors ke saath wapas bhejenge
            return $this->renderView('superadmin/add-course', [
                'centers'   => $data['centers'],
                'pageTitle' => $data['pageTitle'],
                'errors'    => $this->validator->getErrors()
            ]);
        }

        $courseData = [
            'course_name'     => $this->request->getPost('course_name'),
            'center_id'       => $this->request->getPost('center_id'),
            'fee'             => $this->request->getPost('fee'),
            'course_duration' => $this->request->getPost('course_duration'), // Ab ye "1 month" accept karega
            'status'          => 1
        ];

        try {
            $inserted = $courseModel->insert($courseData);

            if ($inserted) {
                $this->addNotification('New Course Added', 'A new course "' . $courseData['course_name'] . '" has been added.', 'course');
                return redirect()->to(base_url('superadmin/courses'))->with('success', 'Course added successfully!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to save course in database.');
            }
        } catch (\Exception $e) {
            // Agar Database error aaye (jaise INT column mein string bhej rahe hon)
            return redirect()->back()->withInput()->with('error', 'Database Error: Please check if duration column is VARCHAR.');
        }
    }

    return $this->renderView('superadmin/add-course', $data);
}

    // ================= EDIT COURSE =================
   public function editCourse($id)
{
    $courseModel = new CourseModel();
    $centerModel = new CenterModel();

    // Course check
    $course = $courseModel->find($id);
    if (!$course) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $data['course'] = $course;
    $data['centers'] = $centerModel->where('status', 1)->findAll();
    $data['pageTitle'] = 'Edit Course';

    if ($this->request->getMethod(true) === 'POST') {
        
        // Updated Validation Rules (Duration se numeric hata diya)
        $validationRules = [
            'course_name'     => 'required|min_length[3]',
            'center_id'       => 'required|integer',
            'course_duration' => 'required', // Ab text allow karega
            'fee'             => 'required|numeric',
            'status'          => 'required|integer'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $update = [
            'course_name'     => $this->request->getPost('course_name'),
            'center_id'       => $this->request->getPost('center_id'),
            'fee'             => $this->request->getPost('fee'),
            'course_duration' => $this->request->getPost('course_duration'),
            'status'          => $this->request->getPost('status')
        ];

        try {
            $courseModel->update($id, $update);
            
            $this->addNotification('Course Updated', 'Course "' . $update['course_name'] . '" has been updated.', 'course');
            
            return redirect()->to(base_url('superadmin/courses'))->with('success', 'Course updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Database Error: Could not update course.');
        }
    }

    return $this->renderView('superadmin/edit-course', $data);
}
    // ================= DELETE COURSE =================
    public function deleteCourse($id)
    {
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);
        
        if ($course) {
            $courseName = $course['course_name'];
            $courseModel->delete($id);
            $this->addNotification('Course Deleted', 'Course "' . $courseName . '" has been deleted.', 'course');
            return redirect()->to(base_url('superadmin/courses'))->with('success', 'Course deleted successfully!');
        }
        
        return redirect()->to(base_url('superadmin/courses'))->with('error', 'Course not found!');
    }

    // ================= ENROLLMENTS =================
    public function enrollments()
    {
        $db = \Config\Database::connect();

        $centers = $db->table('centers')->select('id, center_name, center_code')->get()->getResultArray();

        $centerId = $this->request->getGet('center_id');
        $status = $this->request->getGet('course_status');

        $builder = $db->table('enrollments e');
        $builder->select('e.*, s.student_name, s.phone, s.enrollment_no as real_enroll_no, s.course_status, c.course_name, ctr.center_name');
        $builder->join('students s', 's.id = e.student_id', 'left');
        $builder->join('courses c', 'c.id = e.course_id', 'left');
        $builder->join('centers ctr', 'ctr.id = e.center_id', 'left');

        if (!empty($centerId)) {
            $builder->where('e.center_id', $centerId);
        }
        if (!empty($status)) {
            $builder->where('s.course_status', $status);
        }

        $data = [
            'pageTitle'       => 'Enrollment Manager',
            'enrollments'     => $builder->get()->getResultArray(),
            'centers'         => $centers,
            'selected_center' => $centerId
        ];

        return $this->renderView('superadmin/enrollments', $data);
    }

    // ================= TOGGLE ENROLLMENT STATUS =================
    public function toggleEnrollment($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('enrollments');

        $enrollment = $builder->where('id', $id)->get()->getRow();

        if ($enrollment) {
            $newStatus = ($enrollment->status === 'active') ? 'inactive' : 'active';

            $builder->where('id', $id)->update(['status' => $newStatus]);
            
            $this->addNotification('Enrollment Updated', 'Enrollment status updated to ' . $newStatus, 'enrollment');

            return redirect()->to(base_url('superadmin/enrollments'))
                ->with('success', 'Enrollment status updated to ' . $newStatus);
        }

        return redirect()->to(base_url('superadmin/enrollments'))
            ->with('error', 'Enrollment record not found!');
    }

    // ================= ALL STUDENTS =================
public function allStudents()
{
    $db = \Config\Database::connect();

    // 1. GET parameters for filtering, sorting and SEARCH
    $selected_center = $this->request->getGet('center_id') ?? null;
    $search          = $this->request->getGet('search') ?? null; 
    $sortField       = $this->request->getGet('sort') ?? 'id'; 
    $sortOrder       = strtoupper($this->request->getGet('order') ?? 'DESC');

    $allowedSortFields = ['id', 'student_name', 'enrollment_no', 'email', 'created_at', 'center_name', 'course_name'];
    if (!in_array($sortField, $allowedSortFields)) { 
        $sortField = 'id'; 
    }

    // 2. Builder query with Joins
    $builder = $db->table('students s');
    
    // YAHAN ABS() ADD KIYA HAI TAQI MINUS WALE DAYS BHI POSITIVE KI TARAH SORT HON
    $builder->select("
        s.*,
        ctr.center_name,
        e.enroll_date,
        e.expiry_date,
        e.duration as enrollment_duration,
        c.course_name,
        c.id as course_id,
        DATEDIFF(e.expiry_date, CURDATE()) as sql_days_left,
        ABS(DATEDIFF(e.expiry_date, CURDATE())) as abs_days_left
    ");
    $builder->join('enrollments e', 'e.student_id = s.id', 'left');
    $builder->join('courses c', 'c.id = e.course_id', 'left');
    $builder->join('centers ctr', 'ctr.id = s.center_id', 'left');

    if (!empty($search)) {
        $builder->groupStart()
                ->like('s.student_name', $search)
                ->orLike('s.enrollment_no', $search)
                ->orLike('s.email', $search)
                ->groupEnd();
    }

    if ($selected_center) {
        $builder->where('s.center_id', $selected_center);
    }

    // --- UPDATED SORTING LOGIC FOR ABSOLUTE ORDER (13, 31, 34, 41, 55) ---
    if ($sortField == 'course_name') {
        // ABS use karne se -31 aur 31 dono 31 mane jayenge, isse numerical order sahi aayega
        $builder->orderBy('abs_days_left', $sortOrder);
        $builder->orderBy('c.course_name', 'ASC');
    } elseif ($sortField == 'center_name') {
        $builder->orderBy('ctr.center_name', $sortOrder);
    } else {
        $builder->orderBy("s.$sortField", $sortOrder);
    }

    $students = $builder->get()->getResultArray();

    // 3. Date & Status Logic (BILKUL UNTOUCHED)
    $today = new \DateTime('today');

    foreach ($students as &$student) {
        if (!empty($student['image'])) {
            $imageName = ltrim($student['image'], '/');
            $student['img_display'] = base_url('uploads/students/' . $imageName);
        } else {
            $student['img_display'] = base_url('uploads/students/default-avatar.png'); 
        }

        $student['duration_display'] = !empty($student['enrollment_duration']) ? $student['enrollment_duration'] : 'N/A';
        $student['days_left'] = null;
        $student['status_label'] = 'No Enrollment';
        $student['status_class'] = 'badge-secondary';
        $student['course_display'] = !empty($student['course_name']) ? $student['course_name'] : 'Not Assigned';

        $expiryDate = null;

        if (!empty($student['expiry_date']) && $student['expiry_date'] !== '0000-00-00') {
            try {
                $expiryDate = new \DateTime($student['expiry_date']);
            } catch (\Exception $e) { $expiryDate = null; }
        } 
        elseif (!empty($student['enroll_date']) && !empty($student['enrollment_duration'])) {
            try {
                $enrollDate = new \DateTime($student['enroll_date']);
                $durationStr = strtolower($student['enrollment_duration']); 
                $value = (int) preg_replace('/[^0-9]/', '', $durationStr);
                
                if ($value > 0) {
                    $expiryDate = clone $enrollDate;
                    if (strpos($durationStr, 'month') !== false) {
                        $expiryDate->modify("+$value months");
                    } else {
                        $expiryDate->modify("+$value days");
                    }
                }
            } catch (\Exception $e) { $expiryDate = null; }
        }

        if ($expiryDate) {
            $expiryDate->setTime(0, 0, 0);
            $diff = $today->diff($expiryDate);
            $days = (int)$diff->format('%r%a'); 
            $student['days_left'] = $days;
            
            if ($days < 0) {
                $student['status_label'] = 'Expired (' . abs($days) . ' days ago)';
                $student['status_class'] = 'bg-danger'; 
            } elseif ($days == 0) {
                $student['status_label'] = 'Expires Today';
                $student['status_class'] = 'bg-warning text-dark';
            } elseif ($days <= 10) {
                $student['status_label'] = $days . ' Days Left (Urgent)';
                $student['status_class'] = 'bg-warning text-dark';
            } else {
                $student['status_label'] = $days . ' Days Left';
                $student['status_class'] = 'bg-success';
            }
        }
    }

    $data = [
        'pageTitle'       => 'All Students Master List',
        'students'        => $students,
        'centers'         => (new \App\Models\CenterModel())->where('status', 1)->findAll(),
        'courses'         => (new \App\Models\CourseModel())->findAll(),
        'selected_center' => $selected_center,
        'search'          => $search,
        'sortField'       => $sortField,
        'sortOrder'       => $sortOrder
    ];

    return $this->renderView('superadmin/all-students', $data);
}
public function update_student_master()
{
    $db = \Config\Database::connect();
    $studentModel = new \App\Models\StudentModel();
    $courseModel = new \App\Models\CourseModel();

    // 1. Get Input Data
    $id = $this->request->getPost('student_id');
    $course_id = $this->request->getPost('course_id');
    $enroll_date = $this->request->getPost('enroll_date');
    
    $oldStudent = $studentModel->find($id);

    if (!$oldStudent) {
        return redirect()->back()->with('error', 'Student not found ID: ' . $id);
    }

    // --- COURSE & EXPIRY LOGIC ---
    $courseInfo = $courseModel->find($course_id);
    $new_duration = isset($courseInfo['course_duration']) ? $courseInfo['course_duration'] : '0';
    
    $expiry_date = null;
    if (!empty($enroll_date) && !empty($new_duration)) {
        try {
            $date = new \DateTime($enroll_date);
            // Numeric value extract karna (e.g., "6 months" -> 6)
            $value = (int) preg_replace('/[^0-9]/', '', $new_duration);
            
            if ($value > 0) {
                if (strpos(strtolower($new_duration), 'month') !== false) {
                    $date->modify("+$value months");
                } else {
                    $date->modify("+$value days");
                }
                $expiry_date = $date->format('Y-m-d');
            }
        } catch (\Exception $e) {
            log_message('error', 'Expiry Calculation Error: ' . $e->getMessage());
        }
    }

    // 2. Prepare Students Table Data
    $studentData = [
        'student_name'  => $this->request->getPost('student_name'),
        'father_name'   => $this->request->getPost('father_name'),
        'relation_type' => $this->request->getPost('relation_type'),
        'dob'           => $this->request->getPost('dob'),
        'phone'         => $this->request->getPost('phone'),
        'email'         => $this->request->getPost('email'),
        'address'       => $this->request->getPost('address'),
        'course_status' => $this->request->getPost('certificate_status'), 
    ];

    // --- FILE UPLOAD LOGIC ---
    $file = $this->request->getFile('student_image');
    if ($file && $file->isValid() && !$file->hasMoved()) {
        // Purani image delete karna (Optional but recommended)
        if (!empty($oldStudent['image']) && file_exists('uploads/students/' . $oldStudent['image'])) {
            @unlink('uploads/students/' . $oldStudent['image']);
        }

        $newName = $file->getRandomName();
        $file->move('uploads/students/', $newName);
        $studentData['image'] = $newName;
    }

    // 3. Database Update with Transaction
    $db->transStart();
    
    // A. Update main student table
    $db->table('students')->where('id', $id)->update($studentData);

    // B. Check if enrollment exists
    $enrollmentExists = $db->table('enrollments')->where('student_id', $id)->get()->getRow();

    $enrollmentData = [
        'student_id'  => $id,
        'course_id'   => $course_id,
        'enroll_date' => $enroll_date,
        'expiry_date' => $expiry_date,
        'duration'    => $new_duration
    ];

    if ($enrollmentExists) {
        // Update
        $db->table('enrollments')->where('student_id', $id)->update($enrollmentData);
    } else {
        // Insert (Safe side agar table empty ho)
        $db->table('enrollments')->insert($enrollmentData);
    }

    $db->transComplete();

    // Final check
    if ($db->transStatus() === FALSE) {
        log_message('error', 'Transaction Failed! Last Query: ' . $db->getLastQuery());
        return redirect()->back()->with('error', 'Update failed! Database error.');
    }

    return redirect()->back()->with('success', 'Student record updated successfully!');
}
  public function studentDetails($id)
    {
        $studentModel = new StudentModel();
        $enrollmentModel = new EnrollmentModel();
        $courseModel = new CourseModel();
        
        $student = $studentModel
            ->select('students.*, centers.center_name, centers.center_code')
            ->join('centers', 'centers.id = students.center_id', 'left')
            ->where('students.id', $id)
            ->first();
        
        if (!$student) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Student not found');
        }
        
        $enrollments = $enrollmentModel
            ->select('enrollments.*, courses.course_name, courses.course_code, courses.fee, courses.course_duration')
            ->join('courses', 'courses.id = enrollments.course_id', 'left')
            ->where('enrollments.student_id', $id)
            ->orderBy('enrollments.id', 'DESC')
            ->findAll();
        
        $availableCourses = $courseModel
            ->where('center_id', $student['center_id'])
            ->where('status', 1)
            ->findAll();
        
        $data = [
            'pageTitle' => 'Student Details - ' . $student['student_name'],
            'student' => $student,
            'enrollments' => $enrollments,
            'availableCourses' => $availableCourses
        ];
        
        return $this->renderView('superadmin/student-details', $data);
    }

    // ================= ADD ENROLLMENT =================
 public function addEnrollment()
{
    $enrollmentModel = new \App\Models\EnrollmentModel();
    $courseModel     = new \App\Models\CourseModel();
    
    if ($this->request->getMethod(true) === 'POST') {
        // 1. Validation Rules
        $validationRules = [
            'student_id' => 'required|integer',
            'course_id'  => 'required|integer',
            'center_id'  => 'required|integer'
        ];
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $student_id = $this->request->getPost('student_id');
        $course_id  = $this->request->getPost('course_id');
        $center_id  = $this->request->getPost('center_id');

        // 2. Duplicate Check
        $existing = $enrollmentModel
            ->where('student_id', $student_id)
            ->where('course_id', $course_id)
            ->first();
        
        if ($existing) {
            return redirect()->back()->with('error', 'Student is already enrolled in this course!');
        }

        // 3. Fetch Course Details
        $course = $courseModel->find($course_id);
        if (!$course) {
            return redirect()->back()->with('error', 'Selected course not found!');
        }

        // 4. Calculation Logic (Dates)
        $enroll_date  = date('Y-m-d'); 
        $duration_val = $course['course_duration'] ?? '0'; // e.g., "6 Months"
        
        // Extract numeric months from string
        $months = (int) preg_replace('/[^0-9]/', '', $duration_val);

        $expiry_date = null;
        if ($months > 0) {
            $expiry_date = date('Y-m-d', strtotime("+$months months", strtotime($enroll_date)));
        }
        
        // 5. Data Preparation
        $data = [
            'student_id'  => $student_id,
            'course_id'   => $course_id,
            'center_id'   => $center_id,
            'enroll_date' => $enroll_date,
            'expiry_date' => $expiry_date,
            'duration'    => $duration_val,
            'fee'         => $course['course_fee'] ?? $course['fee'] ?? 0, // Field name check karein
            'status'      => 'active'
        ];
        
        // 6. Insert with Error Handling
        try {
            if ($enrollmentModel->insert($data)) {
                $this->addNotification('New Enrollment', 'Student has been enrolled successfully.', 'enrollment');
                return redirect()->back()->with('success', 'Student enrolled successfully! Expiry: ' . ($expiry_date ?? 'N/A'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        }
        
        return redirect()->back()->with('error', 'Failed to enroll student!');
    }
    
    return redirect()->to(base_url('superadmin/all-students'));
}

// ================= UPDATE ENROLLMENT STATUS =================
public function updateEnrollmentStatus($id)
{
    $enrollmentModel = new \App\Models\EnrollmentModel();
    
    $status = $this->request->getPost('status');
    
    if (empty($status)) {
        return redirect()->back()->with('error', 'Status is required!');
    }

    if ($enrollmentModel->update($id, ['status' => $status])) {
        $this->addNotification('Enrollment Updated', 'Enrollment status has been updated.', 'enrollment');
        return redirect()->back()->with('success', 'Enrollment status updated successfully!');
    }

    return redirect()->back()->with('error', 'Update failed!');
}
    // ================= DELETE ENROLLMENT =================
    public function deleteEnrollment($id)
    {
        $enrollmentModel = new EnrollmentModel();
        $enrollmentModel->delete($id);
        
        $this->addNotification('Enrollment Deleted', 'Enrollment has been deleted.', 'enrollment');
        return redirect()->back()->with('success', 'Enrollment deleted successfully!');
    }

    // ================= EXPORT STUDENTS EXCEL =================
 public function allStudentsExportExcel()
{
    $studentModel = new StudentModel();

    // GET parameters capture kar rahe hain
    $selected_center = $this->request->getGet('center_id') ?? null;
    $sortField       = $this->request->getGet('sort') ?? 'id';
    $sortOrder       = $this->request->getGet('order') ?? 'DESC';

    // Security check
    $allowedSortFields = ['id', 'student_name', 'email', 'phone', 'enrollment_no', 'center_name'];
    if (!in_array($sortField, $allowedSortFields)) {
        $sortField = 'id';
    }

    $studentQuery = $studentModel
        ->select('
            students.student_name,
            students.email,
            students.phone,
            students.enrollment_no,
            centers.center_name,
            centers.center_code,
            GROUP_CONCAT(courses.course_name SEPARATOR ", ") as courses
        ')
        ->join('centers', 'centers.id = students.center_id', 'left')
        ->join('enrollments', 'enrollments.student_id = students.id', 'left')
        ->join('courses', 'courses.id = enrollments.course_id', 'left')
        ->groupBy('students.id');

    // Apply Center Filter
    if ($selected_center) {
        $studentQuery->where('students.center_id', $selected_center);
    }

    // Dynamic Sorting (Screen waali hi sorting yahan chalegi)
    $studentQuery->orderBy("students.$sortField", $sortOrder);

    $students = $studentQuery->findAll();

    // Excel Generation Start
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Header Row (ID remove kar diya gaya hai, columns A se G tak shift ho gaye hain)
    $sheet->setCellValue('A1', 'Name')
        ->setCellValue('B1', 'Email')
        ->setCellValue('C1', 'Phone')
        ->setCellValue('D1', 'Enrollment No')
        ->setCellValue('E1', 'Center Name')
        ->setCellValue('F1', 'Center Code')
        ->setCellValue('G1', 'Courses');

    // Bold Headers (A1 to G1)
    $sheet->getStyle('A1:G1')->getFont()->setBold(true);

    $row = 2;
    foreach ($students as $student) {
        $sheet->setCellValue('A' . $row, $student['student_name'])
            ->setCellValue('B' . $row, $student['email'] ?? 'N/A')
            ->setCellValue('C' . $row, $student['phone'] ?? 'N/A')
            ->setCellValue('D' . $row, $student['enrollment_no'] ?? 'N/A')
            ->setCellValue('E' . $row, $student['center_name'] ?? 'N/A')
            ->setCellValue('F' . $row, $student['center_code'] ?? 'N/A')
            ->setCellValue('G' . $row, $student['courses'] ?? 'N/A');
        $row++;
    }

    // Auto-size columns (Ab A se G tak range hai)
    foreach(range('A','G') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'students_list_' . date('d-m-Y_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    $writer->save('php://output');
    exit;
}
    // ================= EXPORT STUDENTS PDF =================
 public function allStudentsExportPDF()
{
    $studentModel = new StudentModel();

    // GET parameters capture kar rahe hain sorting aur center filter ke liye
    $selected_center = $this->request->getGet('center_id') ?? null;
    $sortField       = $this->request->getGet('sort') ?? 'id';
    $sortOrder       = $this->request->getGet('order') ?? 'DESC';

    // Security check: Allowed columns list
    $allowedSortFields = ['id', 'student_name', 'email', 'phone', 'enrollment_no', 'center_name'];
    if (!in_array($sortField, $allowedSortFields)) {
        $sortField = 'id';
    }

    $studentQuery = $studentModel
        ->select('
            students.student_name,
            students.email,
            students.phone,
            students.enrollment_no,
            centers.center_name,
            centers.center_code,
            GROUP_CONCAT(courses.course_name SEPARATOR ", ") as courses
        ')
        ->join('centers', 'centers.id = students.center_id', 'left')
        ->join('enrollments', 'enrollments.student_id = students.id', 'left')
        ->join('courses', 'courses.id = enrollments.course_id', 'left')
        ->groupBy('students.id');

    // Center Filter apply kar rahe hain
    if ($selected_center) {
        $studentQuery->where('students.center_id', $selected_center);
    }

    // Dynamic Sorting Apply
    $studentQuery->orderBy("students.$sortField", $sortOrder);

    $students = $studentQuery->findAll();

    // PDF HTML Content
    $html = '<h2 style="text-align: center; font-family: sans-serif; margin-bottom: 5px;">All Students Master Report</h2>';
    $html .= '<p style="text-align: center; font-family: sans-serif; color: #666; font-size: 12px; margin-top: 0;">Generated on: ' . date('d M Y, h:i A') . '</p>';
    
    $html .= '<table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 11px;">';
    $html .= '<thead>
                <tr style="background-color: #1e293b; color: white; text-align: left;">
                    <th style="width: 20%;">Student Name</th>
                    <th style="width: 20%;">Email</th>
                    <th style="width: 12%;">Phone</th>
                    <th style="width: 13%;">Enrollment No</th>
                    <th style="width: 15%;">Center</th>
                    <th style="width: 20%;">Courses</th>
                </tr>
              </thead>
              <tbody>';

    if (!empty($students)) {
        foreach ($students as $student) {
            $html .= '<tr>
                        <td style="font-weight: bold; color: #1e293b;">' . esc($student['student_name']) . '</td>
                        <td>' . esc($student['email'] ?? 'N/A') . '</td>
                        <td>' . esc($student['phone'] ?? 'N/A') . '</td>
                        <td style="background-color: #f8fafc; font-family: monospace;">' . esc($student['enrollment_no'] ?? 'N/A') . '</td>
                        <td>' . esc($student['center_name'] ?? 'N/A') . '</td>
                        <td style="font-size: 10px;">' . esc($student['courses'] ?? 'N/A') . '</td>
                      </tr>';
        }
    } else {
        $html .= '<tr><td colspan="6" style="text-align: center; padding: 20px;">No Records Found</td></tr>';
    }

    $html .= '</tbody></table>';

    // Dompdf implementation
    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    
    // Landscape mode directory data ke liye best hai
    $dompdf->setPaper('A4', 'landscape'); 
    $dompdf->render();

    $filename = 'students_report_' . date('d-m-Y_H-i-s') . '.pdf';
    
    // PDF output to browser
    $dompdf->stream($filename, ["Attachment" => true]);
    exit;
}
    // ================= NOTIFICATIONS - COMPLETE =================
    protected function addNotification($title, $message, $type = 'info')
    {
        $notificationModel = new NotificationModel();

        $notificationModel->insert([
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'status'     => 'unread',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    protected function getNotifications($limit = 5)
    {
        $notificationModel = new NotificationModel();

        return $notificationModel
            ->where('status', 'unread')
            ->orderBy('id', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    protected function getUnreadCount()
    {
        $notificationModel = new NotificationModel();
        return $notificationModel->where('status', 'unread')->countAllResults();
    }

    public function markNotification($id)
    {
        $notificationModel = new NotificationModel();
        $notificationModel->update($id, ['status' => 'read']);
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function markAllNotifications()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->where('status', 'unread')->set(['status' => 'read'])->update();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function deleteNotification($id)
    {
        $notificationModel = new NotificationModel();
        $notificationModel->delete($id);
        
        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    public function clearAllNotifications()
    {
        $notificationModel = new NotificationModel();
        $notificationModel->truncate();
        
        return redirect()->back()->with('success', 'All notifications cleared.');
    }
public function viewNotifications()
{
    $notificationModel = new \App\Models\NotificationModel();
    
    $data = [
        'pageTitle'     => 'Notifications Center',
        'notifications' => $notificationModel->orderBy('id', 'DESC')->findAll(), // Saari notifications
        'unreadCount'   => $this->getUnreadCount(),
    ];

    return view('superadmin/notifications_list', $data);
}
public function viewNotification($id)
{
    $notificationModel = new \App\Models\NotificationModel();
    
    // Mark as read
    $notificationModel->update($id, ['status' => 'read']);

    // Poori list fetch karein
    $data = [
        'pageTitle'     => 'Notifications',
        'notifications' => $notificationModel->orderBy('id', 'DESC')->findAll(),
        'scrollToId'    => $id // Yeh ID hum view mein use karenge scroll ke liye
    ];

    return view('superadmin/notifications_list', $data);
}
    // ================= GLOBAL SEARCH =================
    public function globalSearch()
    {
        $keyword = $this->request->getGet('query');
        $data = [];

        if ($keyword) {
            $studentModel = new StudentModel();
            $courseModel  = new CourseModel();
            $centerModel  = new CenterModel();

            $students = $studentModel
                ->select('id, student_name as name, email, phone, "student" as type')
                ->groupStart()
                ->like('student_name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('phone', $keyword)
                ->orLike('enrollment_no', $keyword)
                ->groupEnd()
                ->findAll();

            $courses = $courseModel
                ->select('id, course_name as name, "course" as type')
                ->groupStart()
                ->like('course_name', $keyword)
                ->orLike('course_code', $keyword)
                ->groupEnd()
                ->findAll();

            $centers = $centerModel
                ->select('id, center_name as name, "center" as type')
                ->groupStart()
                ->like('center_name', $keyword)
                ->orLike('center_code', $keyword)
                ->groupEnd()
                ->findAll();

            $data['results'] = array_merge($students, $courses, $centers);
            $data['keyword'] = $keyword;

            if (empty($data['results'])) {
                $data['noResult'] = 'No results found for "' . $keyword . '"';
            }
        } else {
            $data['noResult'] = 'Please enter a keyword to search!';
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON($data);
        }

        $data['pageTitle'] = 'Global Search Results';
        return $this->renderView('superadmin/global_search', $data);
    }

    // ================= PROFILE =================
    public function profile()
    {
        $id = $this->session->get('superadmin_id');
        $data['superadmin'] = $this->superAdminModel
            ->where('id', $id)
            ->where('role', 'super_admin')
            ->first();
        $data['pageTitle'] = 'My Profile';
        
        return $this->renderView('superadmin/profile', $data);
    }

    // ================= EDIT PROFILE =================
    public function editProfile()
    {
        $id = $this->session->get('superadmin_id');

        if ($this->request->getMethod(true) === 'POST') {
            $validationRules = [
                'name'  => 'required',
                'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']'
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $updateData = [
                'name'  => $this->request->getPost('name'),
                'email' => $this->request->getPost('email')
            ];

            $this->superAdminModel->update($id, $updateData);
            
            $this->addNotification('Profile Updated', 'Your profile has been updated successfully.', 'profile');
            return redirect()->to(base_url('superadmin/profile'))->with('success', 'Profile updated successfully!');
        }

        $data['superadmin'] = $this->superAdminModel
            ->where('id', $id)
            ->where('role', 'super_admin')
            ->first();
        $data['pageTitle'] = 'Edit Profile';
        
        return $this->renderView('superadmin/edit-profile', $data);
    }

    // ================= CHANGE PASSWORD =================
    public function changePassword()
    {
        $data['pageTitle'] = 'Change Password';
        return $this->renderView('superadmin/change-password', $data);
    }

    // ================= UPDATE PASSWORD =================
    public function updatePassword()
    {
        $id = $this->session->get('superadmin_id');
        $superadmin = $this->superAdminModel->find($id);

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $current = $this->request->getPost('current_password');
        if (!password_verify($current, $superadmin['password'])) {
            return redirect()->back()->withInput()->with('error', 'Current password is incorrect.');
        }

        $newPassword = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);
        $this->superAdminModel->update($id, ['password' => $newPassword]);

        $this->addNotification('Password Changed', 'Your password has been changed successfully.', 'security');
        return redirect()->to(base_url('superadmin/change-password'))->with('success', 'Password changed successfully!');
    }
  public function send_custom_mail()
{
    $emailService = \Config\Services::email();

    $toEmail = $this->request->getPost('email');
    $subject = $this->request->getPost('subject');
    $message = $this->request->getPost('message');

    $emailService->setTo($toEmail);
    $emailService->setSubject($subject);
    
    // Classic HTML Design
    $htmlBody = "
    <div style='background-color: #f4f7f6; padding: 30px; font-family: sans-serif;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; border-top: 5px solid #4318ff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
            <div style='padding: 20px; text-align: center;'>
                <h2 style='color: #1b3bbb; margin: 0;'>SLYSIS ACADEMY</h2>
            </div>
            <div style='padding: 30px; color: #444; line-height: 1.6;'>
                <p>Hello Student,</p>
                <p>" . nl2br(esc($message)) . "</p>
            </div>
            <div style='background-color: #1b3bbb; padding: 15px; text-align: center; color: #fff; font-size: 12px;'>
                Regards, Administration Team <br> &copy; " . date('Y') . " Slysis Academy
            </div>
        </div>
    </div>";
    
    $emailService->setMessage($htmlBody);

    if ($emailService->send()) {
        // Hinglish Success Message
        return redirect()->back()->with('success', 'Success! The email has been sent to the student successfully.');
    } else {
        // Hinglish Error Message
        return redirect()->back()->with('error', 'Failed! Unable to send the email. Please check your SMTP settings.');
    }
}
public function send_student_notification()
{
    $rules = [
        'student_email' => 'required|valid_email',
        'subject'       => 'required|min_length[3]',
        'message'       => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->with('error', 'Please fill all fields correctly.');
    }

    $toEmail = $this->request->getPost('student_email');
    $subject = $this->request->getPost('subject');
    $message = $this->request->getPost('message');

    $email = \Config\Services::email();
    $email->setNewline("\r\n");
    $email->setCRLF("\r\n");

    $email->setTo($toEmail);
    $email->setFrom('contactslysis@gmail.com', 'Slysis Academy'); 
    $email->setSubject($subject);
    
    $emailBody = "<h3>Message from Slysis Academy</h3><hr><p>$message</p>";
    $email->setMessage($emailBody);

    if ($email->send()) {
        return redirect()->back()->with('success', "Notification sent successfully to $toEmail");
    } else {
        return redirect()->back()->with('error', 'Unable to send email. Check SMTP settings.');
    }
}
}