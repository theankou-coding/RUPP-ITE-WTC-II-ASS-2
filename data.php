<?php
session_start();

class Student {
    private $id;
    private $name;
    private $department;
    private $email;

    public function __construct($id, $name, $department, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->department = $department;
        $this->email = $email;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department' => $this->department,
            'email' => $this->email
        ];
    }
}

class StudentList { 
    private $students = [];

    public function __construct() {
        if (isset($_SESSION['students'])) {
            $this->students = $_SESSION['students'];
        }
    }

    public function addStudent($id, $name, $department, $email) {
        $this->students[] = (new Student($id, $name, $department, $email))->toArray();
        $_SESSION['students'] = $this->students;
    }

    public function deleteStudent($id) {
        foreach ($this->students as $index => $student) {
            if ($student['id'] == $id) {
                array_splice($this->students, $index, 1);
                $_SESSION['students'] = $this->students;
                return json_encode(["success" => "Student with ID $id deleted successfully"], JSON_PRETTY_PRINT);
            }
        }
        return json_encode(["error" => "Student ID $id not found"], JSON_PRETTY_PRINT);
    }

    public function editStudent($id, $newName, $newDepartment, $newEmail) {
        foreach ($this->students as &$student) {
            if ($student['id'] === $id) {
                $student['name'] = $newName;
                $student['department'] = $newDepartment;
                $student['email'] = $newEmail;
                $_SESSION['students'] = $this->students;
                return true;
            }
        }
        return false; 
    }

    public function getStudentsArray() {
        return $this->students;
    }
}

$studentList = new StudentList();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        $id_param = isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '';
        $name_param = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $department_param = isset($_POST['department']) ? htmlspecialchars($_POST['department']) : '';
        $email_param = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

        if ($id_param && $name_param && $department_param && $email_param) {
            $studentList->addStudent($id_param, $name_param, $department_param, $email_param);
        }
    }

    if ($action === 'edit') {
        $id_param = isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '';
        $new_name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $new_department = isset($_POST['department']) ? htmlspecialchars($_POST['department']) : '';
        $new_email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

        if ($id_param && $new_name && $new_department && $new_email) {
            $updated = $studentList->editStudent($id_param, $new_name, $new_department, $new_email);
            header('Content-Type: application/json');
            echo json_encode(["message" => $updated ? "Student updated successfully!" : "Student not found!"], JSON_PRETTY_PRINT);
            exit();
        }
    }

    if ($action === 'delete') {
        $id_param = isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '';
        $response = $studentList->deleteStudent($id_param);
        header('Content-Type: application/json');
        echo $response;
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    header('Content-Type: application/json');
    echo json_encode($studentList->getStudentsArray(), JSON_PRETTY_PRINT);
    exit();
}

header('Content-Type: application/json');
echo json_encode($studentList->getStudentsArray(), JSON_PRETTY_PRINT);
?>