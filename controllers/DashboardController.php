<?php

require_once __DIR__ . '/ClassController.php';
require_once __DIR__ . '/UserController.php';

class DashboardController
{
    private $classController;
    private $userController;

    public function __construct()
    {
        $this->classController = new ClassController();
        $this->userController = new UserController();
    }

    public function index()
    {
        // Lấy tổng số lớp học
        $totalClasses = $this->classController->countClasses();

        // Lấy tổng số giáo viên
        $totalTeachers = $this->userController->countTeachers();

        // Lấy tổng số sinh viên
        $totalStudents = $this->userController->countStudents();

        // Trả về dữ liệu thống kê
        return [
            'totalClasses' => $totalClasses,
            'totalTeachers' => $totalTeachers,
            'totalStudents' => $totalStudents,
        ];
    }
}