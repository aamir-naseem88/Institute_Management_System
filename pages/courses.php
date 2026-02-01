<?php
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
require_once __DIR__ . '/../Classes/Courses.php';
?>

<div class="main-content"> 

  <!-- courses table -->
<div class="card">
    <div class="card-body">
       <div class="row">
        <div class="col-12 col-md-6">
          <h5 class="mb-3">Courses</h5>
        </div>
        <div class="col-12 col-md-6 text-end">
          <a href="../pages/add_course.php" class="btn btn-primary">Add Course</a>
        </div>
</div>
       <hr>

<div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Course ID</th>
              <th>Course Name</th>
              <th>Course Duration</th>
              <th>Course Fee</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
<?php

// display courses
$courseObj = new Courses();
$courses = $courseObj->viewAllCourses();

// display rows
foreach ($courses as $course) {
    echo "<tr>
        <td>{$course['course_id']}</td>
        <td>{$course['course_name']}</td>
        <td>{$course['course_duration']}</td>
        <td>{$course['course_fee']}</td>
        <td>
            <a href='edit_course.php?course_id={$course['course_id']}' 
               class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='Edit Course'>
               <i class='bi bi-pencil text-primary'></i>
            </a>
            <a href='delete_course.php?course_id={$course['course_id']}' 
               class='btn btn-light btn-sm' data-bs-toggle='tooltip' title='Delete Course'>
               <i class='bi bi-trash text-danger'></i>
            </a>
        </td>
    </tr>";
}
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>