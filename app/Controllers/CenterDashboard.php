<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\EnrollmentModel;
use App\Models\CourseModel;
use App\Models\CenterModel;
use App\Models\NotificationModel;

class CenterDashboard extends BaseController
{
    protected $studentModel;
    protected $courseModel;
    protected $enrollModel;
    protected $centerModel;
    protected $notificationModel;

    public function __construct()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'center') {
            header('Location: ' . base_url('/login'));
            exit;
        }

        $this->studentModel = new StudentModel();
        $this->courseModel  = new CourseModel();
        $this->enrollModel  = new EnrollmentModel();
        $this->centerModel  = new CenterModel();
        $this->notificationModel = new NotificationModel();
    }
public function index()
{
    $centerId = session()->get('center_id');
    $db = \Config\Database::connect();
    $currentTime = date('Y-m-d H:i:s');
    
    // --- FETCH DYNAMIC CENTER NAME ---
    $centerData = $this->centerModel->find($centerId);
    $centerName = $centerData['center_name'] ?? 'Center';

    // --- ADMIN MESSAGES ---
    $messages = $db->table('center_messages')
                   ->where('center_id', $centerId)
                   ->where('expires_at >', $currentTime)
                   ->orderBy('status', 'DESC')
                   ->get()->getResultArray();

    // --- COUNTERS ---
    $totalStudents = $this->studentModel->where('center_id', $centerId)->countAllResults();
    $completedCount = $this->studentModel->where(['center_id' => $centerId, 'course_status' => 'completed'])->countAllResults();
    $pendingCount = $this->studentModel->where('center_id', $centerId)
                                       ->groupStart()
                                            ->where('course_status', 'pending')
                                            ->orWhere('course_status', null)
                                            ->orWhere('course_status', '')
                                       ->groupEnd()
                                       ->countAllResults();

    // --- RECENT STUDENTS TABLE LOGIC ---
    $builder = $db->table('students');
    $builder->select('students.*, enrollments.enroll_date, courses.course_name');
    $builder->join('enrollments', 'enrollments.student_id = students.id', 'left');
    $builder->join('courses', 'courses.id = enrollments.course_id', 'left');
    $builder->where('students.center_id', $centerId);
    $builder->orderBy('students.id', 'DESC')->limit(5);

    // --- TODAY'S BIRTHDAY LOGIC ---
    $todayMonth = date('m');
    $todayDay   = date('d');
    $birthdayStudents = $this->studentModel->where('center_id', $centerId)
                                           ->where('MONTH(dob)', $todayMonth)
                                           ->where('DAY(dob)', $todayDay)
                                           ->findAll();

    // --- FIXED: UPCOMING BIRTHDAYS (RAW QUERY TO PREVENT SYNTAX ERROR) ---
    $after7Days = date('m-d', strtotime('+7 days'));
    $todayDate  = date('m-d');

    $upcomingQuery = "SELECT * FROM students 
                      WHERE center_id = ? 
                      AND DATE_FORMAT(dob, '%m-%d') > ? 
                      AND DATE_FORMAT(dob, '%m-%d') <= ? 
                      ORDER BY DATE_FORMAT(dob, '%m-%d') ASC";
    
    $upcomingBirthdays = $db->query($upcomingQuery, [$centerId, $todayDate, $after7Days])->getResultArray();

    return view('center/dashboard', [
        'pageTitle'         => 'Center Dashboard',
        'center_name'       => $centerName,
        'totalStudents'     => $totalStudents,
        'completedCount'    => $completedCount,
        'pendingCount'      => $pendingCount,
        'totalCourses'      => $this->courseModel->where('center_id', $centerId)->countAllResults(),
        'adminMessages'     => $messages,
        'recent_students'   => $builder->get()->getResultArray(),
        'birthday_students' => $birthdayStudents, 
        'upcoming_birthdays' => $upcomingBirthdays 
    ]);
}

// --- NEW FUNCTION FOR UPCOMING EVENTS PAGE ---
public function upcomingEvents()
{
    $centerId = session()->get('center_id');
    $db = \Config\Database::connect();
    
    // RAW SQL Query to avoid 'ASC' inside function error
    $sql = "SELECT * FROM students 
            WHERE center_id = ? 
            AND DATE_FORMAT(dob, '%m-%d') BETWEEN DATE_FORMAT(CURDATE(), '%m-%d') AND DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 7 DAY), '%m-%d')
            ORDER BY DATE_FORMAT(dob, '%m-%d') ASC";
            
    $data['birthdays'] = $db->query($sql, [$centerId])->getResultArray();
    $data['pageTitle'] = 'Upcoming Events';
    
    return view('center/upcoming_events', $data);
}
    public function completedStudents() 
    {
        $centerId = session()->get('center_id');
        $students = $this->studentModel->where(['center_id' => $centerId, 'course_status' => 'completed'])->findAll();
        return view('center/students', [
            'pageTitle' => 'Completed Students',
            'students'  => $this->attachStudentData($students)
        ]);
    }

    public function inProgressStudents() 
    {
        $centerId = session()->get('center_id');
        $students = $this->studentModel->where('center_id', $centerId)
                                       ->groupStart()
                                            ->where('course_status', 'pending')
                                            ->orWhere('course_status', null)
                                            ->orWhere('course_status', '')
                                       ->groupEnd()
                                       ->findAll();
        return view('center/students', [
            'pageTitle' => 'In-Progress Students',
            'students'  => $this->attachStudentData($students)
        ]);
    }

    private function attachStudentData($students) 
    {
        $db = \Config\Database::connect();
        foreach ($students as &$student) {
            $courseData = $db->table('enrollments')
                ->select('enrollments.enroll_date, courses.course_name')
                ->join('courses', 'courses.id = enrollments.course_id', 'left')
                ->where('enrollments.student_id', $student['id'])
                ->get()->getRow();

            if ($courseData) {
                $student['course_name'] = $courseData->course_name;
                $student['enroll_date'] = $courseData->enroll_date;
            } else {
                $student['course_name'] = 'N/A';
                $student['enroll_date'] = '-';
            }
            if (empty($student['enrollment_no'])) $student['enrollment_no'] = "NOT-SET";
        }
        return $students;
    }

    public function updateCourseStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $student = $this->studentModel->find($id);
        if (!$student) {
            return $this->response->setJSON(['success' => false, 'message' => 'Student not found']);
        }

        $currentStatus = $student['course_status'] ?? 'pending';
        $newStatus = ($currentStatus == 'completed') ? 'pending' : 'completed';

        if ($this->studentModel->update($id, ['course_status' => $newStatus])) {
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Database update failed']);
    }

    public function students()
    {
        $centerId = session()->get('center_id');
        $students = $this->studentModel->where('center_id', $centerId)->findAll();
        return view('center/students', [
            'pageTitle' => 'All Students',
            'students'  => $this->attachStudentData($students)
        ]);
    }

    public function markMsgRead($id)
    {
        $db = \Config\Database::connect();
        $db->table('center_messages')
            ->where(['id' => $id, 'center_id' => session()->get('center_id')])
            ->update(['status' => 'read']);
        return redirect()->back()->with('success', 'Notice marked as read.');
    }

    public function deleteMsg($id)
    {
        $db = \Config\Database::connect();
        $db->table('center_messages')
            ->where(['id' => $id, 'center_id' => session()->get('center_id')])
            ->delete();
        return redirect()->back()->with('success', 'Notice deleted successfully.');
    }

    public function profile()
    {
        $center = $this->centerModel->find(session()->get('center_id'));
        return view('center/profile', ['pageTitle' => 'My Profile', 'center' => $center]);
    }

    public function editProfile()
    {
        $center = $this->centerModel->find(session()->get('center_id'));
        return view('center/edit_profile', ['center' => $center]);
    }

    public function updateProfile()
    {
        $data = [
            'center_name' => $this->request->getPost('center_name'),
            'email'        => $this->request->getPost('email'),
            'phone'        => $this->request->getPost('phone'),
            'address'      => $this->request->getPost('address'),
        ];
        $this->centerModel->update(session()->get('center_id'), $data);
        // Updating session too so it reflects everywhere immediately
        session()->set('center_name', $data['center_name']);
        return redirect()->to('center/profile')->with('success', 'Profile updated!');
    }

    public function settings()
    {
        return view('center/change_password', ['center' => $this->centerModel->find(session()->get('center_id'))]);
    }

    public function updatePassword()
    {
        $center = $this->centerModel->find(session()->get('center_id'));
        if (!password_verify($this->request->getPost('old_password'), $center['password'])) {
            return redirect()->back()->with('error', 'Old password incorrect');
        }
        $this->centerModel->update($center['id'], ['password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT)]);
        return redirect()->to('center/profile')->with('success', 'Password updated');
    }

    public function addStudent()
    {
        $courses = $this->courseModel->where('center_id', session()->get('center_id'))->findAll();
        return view('center/add-student', ['pageTitle' => 'Add Student', 'center_code' => session()->get('center_code'), 'courses' => $courses]);
    }
public function saveStudent() {
    $db = \Config\Database::connect();
    
    // 1. Validation
    if (!$this->validate([
        'student_sequence' => 'required',
        'student_name'     => 'required',
        'relation_type'    => 'required|in_list[Father,Husband]', 
        'father_name'      => 'required',
        'course_id'        => 'required',
        'email'            => 'required|valid_email',
    ])) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // 2. Image Handling
    $photoName = null; 
    $file = $this->request->getFile('image'); 
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $photoName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/students/', $photoName);
    }

    $enrollment_no = session()->get('center_code') . '-' . $this->request->getPost('student_sequence');

    try {
        $db->transStart();

        // --- STUDENTS TABLE INSERT ---
        $studentData = [
            'center_id'      => session()->get('center_id'),
            'enrollment_no'  => $enrollment_no, 
            'student_name'   => $this->request->getPost('student_name'),
            'relation_type'  => $this->request->getPost('relation_type'), 
            'father_name'    => $this->request->getPost('father_name'),
            'phone'          => $this->request->getPost('phone'),
            'guardian_phone' => $this->request->getPost('guardian_phone'),
            'dob'            => $this->request->getPost('dob'),
            'email'          => $this->request->getPost('email'),
            'address'        => $this->request->getPost('address'),
            'image'          => $photoName,
            'course_status'  => 'pending'
        ];

        $db->table('students')->insert($studentData);
        $student_id = $db->insertID();

        // --- SMART LOGIC: DURATION FETCH & EXPIRY ---
        $enrollDateStr = $this->request->getPost('enroll_date') ?: date('Y-m-d');
        $courseId      = $this->request->getPost('course_id');
        
        // Course model se exact duration uthayein (Column: course_duration)
        $course = $this->courseModel->find($courseId);
        $durationText = $course['course_duration'] ?? '0'; // 👈 FIX: Ye column name match hona chahiye
        
        $durationValue = (int) preg_replace('/[^0-9]/', '', $durationText);
        $durationLower = strtolower($durationText);
        $expiryDate    = new \DateTime($enrollDateStr);
        
        if ($durationValue > 0) {
            if (strpos($durationLower, 'month') !== false) {
                $expiryDate->modify("+$durationValue months");
            } else {
                $expiryDate->modify("+$durationValue days");
            }
        }
        $finalExpiry = $expiryDate->format('Y-m-d');

        // --- ENROLLMENTS TABLE INSERT ---
        $db->table('enrollments')->insert([
            'student_id'  => $student_id,
            'center_id'   => session()->get('center_id'),
            'course_id'   => $courseId,
            'duration'    => $durationText, // ✅ Pehli baar mein hi sahi duration jayegi
            'enroll_date' => $enrollDateStr,
            'expiry_date' => $finalExpiry,
        ]);
        
        $db->transComplete();

        if ($db->transStatus() === FALSE) {
            return redirect()->back()->withInput()->with('error', 'Database error.');
        }

        return redirect()->to('center/students')->with('success', 'Student successfully save ho gaya hai!');

    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}
 public function editStudent($id)
{
    $db = \Config\Database::connect();
    $centerId = session()->get('center_id');

    // 1. Student check karein
    $student = $this->studentModel->where(['id' => $id, 'center_id' => $centerId])->first();

    if (!$student) {
        return redirect()->to('center/students')->with('error', 'Access denied or Student not found!');
    }

    // 2. Enrollment data fetch karein
    $enroll = $db->table('enrollments')->where('student_id', $id)->get()->getRowArray();
    
    // --- FIX: Default values set karein taaki View crash na ho ---
    $student['course_id']   = $enroll['course_id'] ?? ''; 
    $student['enroll_date'] = $enroll['enroll_date'] ?? date('Y-m-d');
    $student['expiry_date'] = $enroll['expiry_date'] ?? '';
    $student['duration']    = $enroll['duration'] ?? '';

    // --- FIX: Agar enrollment mein duration khali hai, toh Course table se uthao ---
    if ($enroll && empty($enroll['duration']) && !empty($enroll['course_id'])) {
        $course = $this->courseModel->find($enroll['course_id']);
        $student['duration'] = $course['course_duration'] ?? '0';
    }

    // 3. Enrollment Number / Sequence logic
    if (!empty($student['enrollment_no'])) {
        $enrollParts = explode('-', $student['enrollment_no']);
        $student['student_sequence'] = end($enrollParts); 
    } else {
        $student['student_sequence'] = '';
    }

    // 4. View return
    return view('center/edit_student', [ 
        'pageTitle'   => 'Edit Student', 
        'student'     => $student, 
        'courses'     => $this->courseModel->where('status', 1)->findAll(),
        'center_code' => session()->get('center_code')
    ]);
}

 public function updateStudent($id) {
    $db = \Config\Database::connect();
    $centerId = session()->get('center_id');
    
    // 1. Security Check: Student isi center ka hona chahiye
    $oldStudent = $this->studentModel
                        ->where('id', $id)
                        ->where('center_id', $centerId)
                        ->first();

    if (!$oldStudent) {
        return redirect()->back()->with('error', 'Student nahi mila ya access denied!');
    }

    // 2. Data Preparation
    $studentData = [
        'student_name'   => $this->request->getPost('student_name'),
        'relation_type'  => $this->request->getPost('relation_type'),
        'father_name'    => $this->request->getPost('father_name'), 
        'phone'          => $this->request->getPost('phone'),
        'guardian_phone' => $this->request->getPost('guardian_phone'), 
        'dob'            => $this->request->getPost('dob'),
        'email'          => $this->request->getPost('email'),
        'address'        => $this->request->getPost('address'), 
        'enrollment_no'  => session()->get('center_code') . '-' . $this->request->getPost('student_sequence'),
    ];

    // 3. Image Logic (Old image delete if new one uploaded)
    $file = $this->request->getFile('image'); 
    if ($file && $file->isValid() && !$file->hasMoved()) {
        if (!empty($oldStudent['image']) && file_exists(FCPATH . 'uploads/students/' . $oldStudent['image'])) {
            unlink(FCPATH . 'uploads/students/' . $oldStudent['image']);
        }
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/students/', $newName);
        $studentData['image'] = $newName; 
    }

    // --- TRANSACTION START ---
    $db->transBegin(); 

    try {
        // 1. Update Students Table
        $db->table('students')->where('id', $id)->update($studentData);

        // 2. Fresh Course Duration & Expiry Logic
        $courseId      = $this->request->getPost('course_id');
        $enrollDateStr = $this->request->getPost('enroll_date') ?: date('Y-m-d');
        $course        = $this->courseModel->find($courseId);
        
        $durationText  = $course['course_duration'] ?? '0'; 
        $durationValue = (int) preg_replace('/[^0-9]/', '', $durationText);
        $expiryDate    = new \DateTime($enrollDateStr);

        if ($durationValue > 0) {
            if (strpos(strtolower($durationText), 'month') !== false) {
                $expiryDate->modify("+$durationValue months");
            } else {
                $expiryDate->modify("+$durationValue days");
            }
        }
        $finalExpiry = $expiryDate->format('Y-m-d');

        $enrollData = [
            'course_id'   => $courseId,
            'duration'    => $durationText,
            'enroll_date' => $enrollDateStr,
            'expiry_date' => $finalExpiry,
        ];

        // 3. Update Enrollments Table (FIXED: Added where clause)
        $db->table('enrollments')
           ->where('student_id', $id)
           ->where('center_id', $centerId) // Extra security
           ->update($enrollData);

        // --- TRANSACTION COMMIT ---
        if ($db->transStatus() === FALSE) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Database error: Update fail ho gaya.');
        } else {
            $db->transCommit(); 
            return redirect()->to(base_url('center/students'))->with('success', 'Student data successfully update ho gaya hai!');
        }

    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}
    public function deleteStudent($id)
    {
        $this->enrollModel->where('student_id', $id)->delete();
        $this->studentModel->delete($id);
        return redirect()->to('center/students')->with('success', 'Deleted!');
    }

    public function courses()
    {
        $courses = $this->courseModel->where('center_id', session()->get('center_id'))->findAll();
        foreach ($courses as &$c) { 
            $c['student_count'] = $this->enrollModel->where(['course_id' => $c['id'], 'center_id' => session()->get('center_id')])->countAllResults(); 
        }
        return view('center/courses', ['pageTitle' => 'Courses', 'courses' => $courses]);
    }

    public function enrollments()
    {
        $enrollments = $this->enrollModel->where('center_id', session()->get('center_id'))->findAll();
        foreach ($enrollments as &$e) { 
            $e['student'] = $this->studentModel->find($e['student_id']); 
            $e['course'] = $this->courseModel->find($e['course_id']); 
        }
        return view('center/enrollments', ['pageTitle' => 'Enrollments', 'enrollments' => $enrollments]);
    }

    public function getCourseDetails($id)
    {
        $course = $this->courseModel->find($id);
        return $course ? $this->response->setJSON($course) : $this->response->setJSON(['error' => 'Not found'], 404);
    }
    // Is function ko apne CenterDashboard controller mein last mein add kar dein

public function notifications()
{
    $centerId = session()->get('center_id');
    
    // Database connection check
    $db = \Config\Database::connect();
    
    // Table name sahi check karein (aapke DB mein 'center_messages' hai ya 'notifications'?)
    $allMessages = $db->table('center_messages') 
                      ->where('center_id', $centerId)
                      ->orderBy('created_at', 'DESC')
                      ->get()
                      ->getResultArray();

    // Check karein ki view file 'app/Views/center/notifications.php' sahi location par hai
    return view('center/notifications', [
        'pageTitle' => 'All Announcements',
        'messages'  => $allMessages
    ]);
}
}