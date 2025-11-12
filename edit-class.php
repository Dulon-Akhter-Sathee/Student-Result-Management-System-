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

if(isset($_POST['update'])) {
    $classname = ($_POST['classname']);
    $classnamenumeric = ($_POST['classnamenumeric']); 
    $section = ($_POST['section']);
    $cid = intval($_GET['classid']);

    if (!preg_match("/^[A-Za-z]+$/", $classname)) {
        $error = "Class name should contain letters only.";
    } else {
        $sql = "UPDATE tblclasses 
                SET ClassName=:classname, ClassNameNumeric=:classnamenumeric, Section=:section 
                WHERE id=:cid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':classname', $classname, PDO::PARAM_STR);
        $query->bindParam(':classnamenumeric', $classnamenumeric, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);
        $query->bindParam(':cid', $cid, PDO::PARAM_INT);

        if($query->execute()){
            $msg = "Data has been updated successfully";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}

// Fetch class data
$cid = intval($_GET['classid']);
$sql = "SELECT * FROM tblclasses WHERE id=:cid";
$query = $dbh->prepare($sql);
$query->bindParam(':cid', $cid, PDO::PARAM_INT);
$query->execute();
$class = $query->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Class | SRMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background: #f5f6fa; margin:0; overflow-x:hidden; }

.topbar { background: #1c2530; height:64px; display:flex; align-items:center; padding:0 18px; position:fixed; top:0; width:100%; z-index:1052;}
.topbar .material-icons{ cursor:pointer; color:white; }
.fw-bold { color:white; margin:0; }

.sidebar { width:240px; background:#212529; position:fixed; top:0; left:0; height:100%; color:white; padding-top:70px; z-index:1051; transition: transform .3s ease; overflow-y:auto; overflow-x:hidden;}
.sidebar.collapsed { transform:translateX(-240px); }
.sidebar .side-nav a { display:flex; align-items:center; gap:10px; padding:12px 20px; color:#dcdcdc; text-decoration:none; font-size:14px;}
.sidebar .side-nav a:hover { background: rgba(255,255,255,0.09); }
.child-nav { display:none; padding-left:15px;}
.has-children.open .child-nav { display:block; }
.arrow { margin-left:auto; transition:.3s; }
.has-children.open .arrow { transform:rotate(90deg); }

.main-content { margin-left:240px; padding:90px 25px 25px; transition: margin-left .3s ease;}
.main-content.collapsed { margin-left:0; }

.profile-box { text-align:center; padding:15px 5px; background:#212529; margin-bottom:10px;}
.profile-img { width:75px; height:75px; border-radius:50%; border:2px solid #71C9CE; object-fit:cover; }
.profile-box h6 { margin-top:8px; font-size:14px; font-weight:600; }
.profile-box small { font-size:12px; color:#bbb; }

.card { border-radius:10px; background:#fff; }
h4.fw-semibold { color:#333; }

@media(max-width:768px){
    .sidebar { transform:translateX(-240px); }
    .sidebar.open { transform:translateX(0); }
    .main-content { margin-left:0!important; }
}
</style>
</head>
<body>

<div class="topbar">
    <span class="material-icons me-3" id="toggleSidebar">menu</span>
    <h6 class="fw-bold">SRMS</h6>
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
                <li><a href="manage-subjectcombination.php">Manage Subject Combinationsss</a></li>
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

<div class="main-content">
    <div class="container">
        <h4 class="mb-4 fw-semibold">Update Class</h4>

        <?php if($msg){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                 <?php echo htmlentities($msg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } elseif($error){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlentities($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>

        <div class="card shadow-sm p-4">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Class Name</label>
                    <input type="text" name="classname" class="form-control" required
                           value="<?php echo htmlentities($class->ClassName ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Class Name Numeric</label>
                    <input type="number" name="classnamenumeric" class="form-control" required
                           value="<?php echo htmlentities($class->ClassNameNumeric ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <input type="text" name="section" class="form-control" required
                           value="<?php echo htmlentities($class->Section ?? ''); ?>">
                </div>

                <button type="submit" name="update" class="btn btn-primary">Update Class</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('toggleSidebar').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.querySelector('.main-content').classList.toggle('collapsed');
});

document.querySelectorAll('.has-children > a').forEach(el => {
    el.addEventListener('click', e => {
        e.preventDefault();
        el.parentElement.classList.toggle('open');
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
