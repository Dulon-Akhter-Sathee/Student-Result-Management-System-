<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['alogin']) || $_SESSION['alogin']=="") {
    header("Location: index.php");
    exit;
}

$msg = '';
$error = '';

if(isset($_POST['submit'])){
    $subjectname = ($_POST['subjectname']);
    $subjectcode = ($_POST['subjectcode']);

    
    if(!preg_match("/^[A-Za-z]+$/", $subjectname)){
        $error = "Subject Name should contain letters only.";
    }
    else {
        
        $sql = "SELECT id FROM tblsubjects WHERE (SubjectName = :subjectname OR SubjectCode = :subjectcode)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectname', $subjectname, PDO::PARAM_STR);
        $query->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);

        if($result){
            $error = "Subject or Subject Code Already Exists! Duplicate Entry Rejected.";
        } 
        else {
            $sql = "INSERT INTO tblsubjects(SubjectName, SubjectCode) VALUES(:subjectname, :subjectcode)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':subjectname', $subjectname, PDO::PARAM_STR);
            $query->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);

            if($query->execute()){
                $msg = "Subject Created Successfully!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Subject | SRMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f5f6fa; overflow-x: hidden; margin: 0; }
.topbar { background: #1c2530; height: 64px; display: flex; align-items: center; padding: 0 18px; position: fixed; top: 0; width: 100%; z-index: 1052; }
.topbar .material-icons { cursor: pointer; color: white; }
.fw-bold { color: white; }
.sidebar { width: 240px; background: #212529; position: fixed; top: 0; left: 0; height: 100%; color: white; padding-top: 70px; z-index: 1051; transition: transform .3s ease; overflow-y: auto; }
.sidebar.collapsed { transform: translateX(-240px); }
.sidebar .side-nav a { display: flex; align-items: center; gap: 13px; padding: 12px 20px; color: #dcdcdc; text-decoration: none; font-size: 14px; }
.sidebar .side-nav a:hover { background: rgba(255,255,255,0.09); }
.child-nav { display: none; background: #212529; }
.has-children.open .child-nav { display: block; }
.arrow { margin-left: auto; transition: .3s; }
.has-children.open .arrow { transform: rotate(90deg); }
.profile-box { text-align: center; padding: 15px 5px; background: #212529; margin-bottom: 10px; }
.profile-img { width: 75px; height: 75px; border-radius: 50%; border: 2px solid #71C9CE; object-fit: cover; }
.main-content { margin-left: 240px; padding: 90px 25px 25px; transition: margin-left .3s ease; }
.main-content.collapsed { margin-left: 0; }
@media(max-width: 768px){
    .sidebar { transform: translateX(-240px); }
    .sidebar.open { transform: translateX(0); }
    .main-content { margin-left: 0 !important; }
}
</style>
</head>
<body>

<!-- Topbar -->
<div class="topbar">
    <span class="material-icons me-3" id="toggleSidebar">menu</span>
    <h6 class="fw-bold m-0">SRMS</h6>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="profile-box">
        <img src="images/profile-image.png" alt="Admin" class="profile-img">
        <h6>Admin</h6>
        <small>Administrator</small>
    </div>

    <ul class="side-nav list-unstyled">
        <li><a href="dashboard.php"><span class="material-icons">dashboard</span> Dashboard</a></li>
        <li class="has-children">
            <a href="#"><span class="material-icons">class</span> Classes<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-class.php">Create Class</a></li>
                <li><a href="manage-classes.php">Manage Classes</a></li>
            </ul>
        </li>
        <li class="has-children open">
            <a href="#"><span class="material-icons">menu_book</span> Subjects<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-subject.php" class="active">Create Subject</a></li>
                <li><a href="manage-subjects.php">Manage Subjects</a></li>
                <li><a href="add-subjectcombination.php">Add Subject Combination</a></li>
                <li><a href="manage-subjectcombination.php">Manage Subject Combinations</a></li>
            </ul>
        </li>
        <li class="has-children">
            <a href="#"><span class="material-icons">groups</span> Students<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="add-students.php">Add Students</a></li>
                <li><a href="manage-students.php">Manage Students</a></li>
            </ul>
        </li>
        <li class="has-children">
            <a href="#"><span class="material-icons">fact_check</span> Results<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="add-results.php">Add Results</a></li>
                <li><a href="manage-results.php">Manage Results</a></li>
            </ul>
        </li>
        <li><a href="change-password.php"><span class="material-icons">key</span> Change Password</a></li>
        <li><a href="logout.php"><span class="material-icons">logout</span> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" id="main">
    <div class="container">
        <h4 class="mb-4 fw-semibold">Create New Subject</h4>

        <?php if($msg){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                 <?php echo htmlentities($msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } else if($error){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlentities($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <div class="card shadow-sm p-4">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Subject Name</label>
                    <input type="text" name="subjectname" class="form-control" placeholder="Enter Subject Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject Code</label>
                    <input type="text" name="subjectcode" class="form-control" placeholder="Enter Subject Code" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Create Subject</button>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.has-children > a').forEach(el => {
    el.addEventListener('click', e => {
        e.preventDefault();
        el.parentElement.classList.toggle('open');
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
