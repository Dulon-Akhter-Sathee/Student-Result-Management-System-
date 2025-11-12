<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['alogin']) || $_SESSION['alogin']=="") {
    header("Location: index.php");
    exit();
}

$msg = '';
$error = '';

if (isset($_POST['submit'])) {
    $classname = ($_POST['classname']);
    $classnamenumeric = ($_POST['classnamenumeric']); 
    $section = ($_POST['section']);

    if (!preg_match("/^[A-Za-z]+$/", $classname)) {
        $error = "Class name should contain letters only.";
    } else {
        $sql = "INSERT INTO tblclasses (ClassName, ClassNameNumeric, Section) 
                VALUES (:classname, :classnamenumeric, :section)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classname', $classname, PDO::PARAM_STR);
        $query->bindParam(':classnamenumeric', $classnamenumeric, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->execute();

        if ($dbh->lastInsertId()) {
            $msg = "Class Created Successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Class | SRMS</title>
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
.sidebar .side-nav a:hover, .sidebar .side-nav a.active { background: rgba(255,255,255,0.09); color: #fff; }
.child-nav { display: none; background: #212529; }
.has-children.open .child-nav { display: block; }
.arrow { margin-left: auto; transition: .3s; }
.has-children.open .arrow { transform: rotate(90deg); }
.profile-box { text-align: center; padding: 15px 5px; background: #212529; margin-bottom: 10px; }
.profile-img { width: 75px; height: 75px; border-radius: 50%; border: 2px solid #71C9CE; object-fit: cover; }
.main-content { margin-left: 240px; padding: 90px 25px 25px; transition: margin-left .3s ease; }
.main-content.collapsed { margin-left: 0; }
.card-box { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
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

        <li class="has-children open">
            <a href="#"><span class="material-icons">class</span> Classes <span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-class.php" class="active">Create Class</a></li>
                <li><a href="manage-classes.php">Manage Classes</a></li>
            </ul>
        </li>

        <li class="has-children">
            <a href="#"><span class="material-icons">menu_book</span> Subjects<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-subject.php">Create Subject</a></li>
                <li><a href="manage-subjects.php">Manage Subjects</a></li>
                <li><a href="add-subjectcombination.php">Add Subject Combination</a></li>
                <li><a href="manage-subjectcombination.php">Manage Subject Combination</a></li>
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
        <h4 class="fw-semibold mb-4">Create Student Class</h4>

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
                    <label class="form-label">Class Name</label>
                    <input type="text" name="classname" class="form-control" placeholder="Enter Class Name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Class Name in Numeric</label>
                    <input type="number" name="classnamenumeric" class="form-control" placeholder="Enter Numeric Value" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" placeholder="Enter Section" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary">Create Class</button>
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
