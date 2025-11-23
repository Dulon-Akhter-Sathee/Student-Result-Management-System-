<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['alogin']) || $_SESSION['alogin']=="") {
    header("Location: index.php");
    exit();
}

// Show success message after reload
$msg = '';
if(isset($_SESSION['msg'])){
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// Load Class selection
$selectedClass = $_POST['class'] ?? '';
$selectedStudent = $_POST['studentid'] ?? '';
$students = [];
$subjects = [];
$resultExists = false;

if($selectedClass){
    // Students
    $st = $dbh->prepare("SELECT StudentId, StudentName FROM tblstudents WHERE ClassId=:cid ORDER BY StudentName");
    $st->execute([':cid'=>$selectedClass]);
    $students = $st->fetchAll(PDO::FETCH_OBJ);

    // Subjects
    $sb = $dbh->prepare("SELECT s.id, s.SubjectName
                         FROM tblsubjectcombination sc
                         JOIN tblsubjects s ON s.id=sc.SubjectId
                         WHERE sc.ClassId=:cid ORDER BY s.SubjectName");
    $sb->execute([':cid'=>$selectedClass]);
    $subjects = $sb->fetchAll(PDO::FETCH_OBJ);
}

// Check result exists
if($selectedClass && $selectedStudent){
    $ch = $dbh->prepare("SELECT id FROM tblresult WHERE ClassId=:cid AND StudentId=:sid");
    $ch->execute([':cid'=>$selectedClass, ':sid'=>$selectedStudent]);
    if($ch->rowCount() > 0){
        $resultExists = true;
    }
}

if(isset($_POST['submit_marks'])){
    $class = $_POST['class'];
    $student = $_POST['studentid'];
    $marks = $_POST['marks'];
    $subjects = $_POST['subject_ids'];

    for($i=0; $i<count($marks); $i++){
        $sql = "INSERT INTO tblresult(StudentId, ClassId, SubjectId, marks)
                VALUES(:sid, :cid, :subid, :mks)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ':sid'=>$student,
            ':cid'=>$class,
            ':subid'=>$subjects[$i],
            ':mks'=>$marks[$i]
        ]);
    }

    $_SESSION['msg'] = "Result added successfully";

    header("Location: add-results.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Result | SRMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
body { font-family: 'Poppins', sans-serif; background: #f5f6fa; margin:0; overflow-x:hidden; }
.topbar { background:#1c2530; height:64px; display:flex; align-items:center; padding:0 18px; position:fixed; top:0; width:100%; z-index:1052; }
.topbar .material-icons { cursor:pointer; color:white; }
.fw-bold { color:white; margin:0; }
.sidebar { width:240px; background:#212529; position:fixed; top:0; left:0; height:100%; color:white; padding-top:70px; z-index:1051; overflow-y:auto; transition: transform .3s ease; }
.sidebar.collapsed { transform:translateX(-240px); }
.sidebar .side-nav a { display:flex; align-items:center; gap:10px; padding:12px 20px; color:#dcdcdc; text-decoration:none; font-size:14px; }
.sidebar .side-nav a:hover, .sidebar .side-nav a.active { color:#fff; }
.child-nav { display:none; padding-left:15px; }
.has-children.open .child-nav { display:block; }
.arrow { margin-left:auto; transition:.3s; }
.has-children.open .arrow { transform:rotate(90deg); }
.profile-box { text-align:center; padding:15px 5px; background:#212529; margin-bottom:10px; }
.profile-img { width:75px; height:75px; border-radius:50%; border:2px solid #71C9CE; object-fit:cover; }
.profile-box h6 { margin-top:8px; font-size:14px; font-weight:600; }
.profile-box small { font-size:12px; color:#bbb; }
.main-content { margin-left:240px; padding:90px 25px 25px; transition: margin-left .3s ease; }
.main-content.collapsed { margin-left:0; }
.card-box { border-radius:10px; padding:22px; background:white; color:#333; margin-bottom:20px; }
@media(max-width:768px) { .sidebar { transform:translateX(-240px); } .sidebar.open { transform:translateX(0); } .main-content { margin-left:0 !important; } }
</style>
</head>
<body>

<div class="topbar">
    <span class="material-icons me-3" id="toggleSidebar">menu</span>
    <h6 class="fw-bold m-0">SRMS</h6>
</div>

<div class="sidebar" id="sidebar">
    <div class="profile-box">
        <img src="images/profile-image.png" alt="Admin" class="profile-img">
        <h6>Admin</h6>
        <small>Administrator</small>
    </div>

    <ul class="side-nav list-unstyled">
        <li><a href="dashboard.php"><span class="material-icons">dashboard</span> Dashboard</a></li>

        <li class="has-children">
            <a href="#"><span class="material-icons">class</span> Classes <span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-class.php">Create Class</a></li>
                <li><a href="manage-classes.php">Manage Classes</a></li>
            </ul>
        </li>

        <li class="has-children">
            <a href="#"><span class="material-icons">menu_book</span> Subjects <span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-subject.php">Create Subject</a></li>
                <li><a href="manage-subjects.php">Manage Subjects</a></li>
                <li><a href="add-subjectcombination.php">Add Subject Combination</a></li>
                <li><a href="manage-subjectcombination.php">Manage Subject Combination</a></li>
            </ul>
        </li>

        <li class="has-children">
            <a href="#"><span class="material-icons">groups</span> Students <span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="add-students.php">Add Students</a></li>
                <li><a href="manage-students.php">Manage Students</a></li>
            </ul>
        </li>

        <li class="has-children">
            <a href="#"><span class="material-icons">fact_check</span> Results <span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="add-results.php" class="active">Add Results</a></li>
                <li><a href="manage-results.php">Manage Results</a></li>
            </ul>
        </li>

        <li><a href="change-password.php"><span class="material-icons">key</span> Change Password</a></li>
        <li><a href="logout.php"><span class="material-icons">logout</span> Logout</a></li>
    </ul>
</div>

<div class="main-content" id="main">
<div class="card-box">
<h4>Declare Student Result</h4>

<!-- SUCCESS MESSAGE -->
<?php if($msg){ ?>
<div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
<?php } ?>

<!-- ERROR IF RESULT ALREADY EXISTS -->
<?php if($resultExists){ ?> 
<div class="alert alert-danger">Result already declared for this student!</div>
<?php } ?>

<!-- STEP 1: Select Class -->
<form method="post">
    <div class="mb-3">
        <label class="form-label">Class</label>
        <select name="class" class="form-select" required onchange="this.form.submit()">
            <option value="">Select Class</option>
            <?php
            $sql = $dbh->prepare("SELECT * FROM tblclasses");
            $sql->execute();
            $classes = $sql->fetchAll(PDO::FETCH_OBJ);
            foreach($classes as $cls){
                echo '<option value="'.$cls->id.'"';
                if($selectedClass==$cls->id) echo ' selected';
                echo '>'.$cls->ClassName.' - '.$cls->Section.'</option>';
            }
            ?>
        </select>
    </div>
</form>

<!-- STEP 2: Student -->
<?php if($students): ?>
<form method="post">
    <input type="hidden" name="class" value="<?php echo $selectedClass; ?>">

    <div class="mb-3">
        <label class="form-label">Student</label>
        <select name="studentid" class="form-select" required onchange="this.form.submit()">
            <option value="">Select Student</option>
            <?php foreach($students as $stu): ?>
                <option value="<?php echo $stu->StudentId; ?>"
                <?php if($selectedStudent==$stu->StudentId) echo ' selected'; ?>>
                <?php echo $stu->StudentName; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Subjects -->
    <?php if($subjects): ?>
        <h5>Subjects</h5>
        <?php foreach($subjects as $sub): ?>
            <div class="mb-3">
                <label><?php echo $sub->SubjectName; ?></label>
                <input type="hidden" name="subject_ids[]" value="<?php echo $sub->id; ?>">
                <input type="number" name="marks[]" class="form-control" min="0" max="100"
                placeholder="Enter marks out of 100" required
                <?php if($resultExists) echo 'disabled'; ?>>
            </div>
        <?php endforeach; ?>

        <?php if(!$resultExists): ?>
            <button type="submit" name="submit_marks" class="btn btn-primary">Submit Result</button>
        <?php endif; ?>

    <?php endif; ?>
</form>
<?php endif; ?>

</div>
</div>

<script>
document.querySelectorAll('.has-children > a').forEach(a => {
    a.addEventListener('click', function(e){ 
        e.preventDefault(); 
        this.parentElement.classList.toggle('open'); 
    });
});

document.getElementById('toggleSidebar').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('collapsed');
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
