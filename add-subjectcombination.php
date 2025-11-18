<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
    exit();
}

$msg = '';
$error = '';

if(isset($_POST['submit'])){
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $status = 1;

    $sql = "INSERT INTO tblsubjectcombination(ClassId,SubjectId,status) VALUES(:class,:subject,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':class', $class, PDO::PARAM_STR);
    $query->bindParam(':subject', $subject, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();

    $lastInsertId = $dbh->lastInsertId();
    if($lastInsertId){
        $msg = "Combination added successfully";
    } else {
        $error = "Something went wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Subject Combination</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<style>
body{ background:#f5f6fa; margin:0; font-family:'Poppins',sans-serif; }
.topbar{ background:#1c2530; height:64px; display:flex; align-items:center; padding:0 18px; position:fixed; top:0; width:100%; color:white; z-index:1052; }
#toggleSidebar{ cursor:pointer; }
.sidebar{ width:240px; background:#212529; position:fixed; top:0; left:0; height:100%; color:white; padding-top:70px; overflow-y:auto; transition:.3s; }
.sidebar.collapsed{ transform:translateX(-240px); }
.sidebar .side-nav a{ display:flex; align-items:center; gap:10px; padding:12px 20px; color:#dcdcdc; text-decoration:none; font-size:14px; }
.sidebar .side-nav a:hover{ background:rgba(255,255,255,.08); }
.child-nav{ display:none; }
.has-children.open .child-nav{ display:block; }
.arrow{ margin-left:auto; transition:.3s; }
.has-children.open .arrow{ transform:rotate(90deg); }
.profile-box{text-align:center; padding:15px 5px; background:#212529; margin-bottom:10px;}
.profile-img{width:75px;height:75px;border-radius:50%;border:2px solid #71C9CE;object-fit:cover;}
.profile-box h6{margin-top:8px;font-size:14px;font-weight:600;}
.profile-box small{font-size:12px;color:#bbb;}
.main-content{margin-left:240px;padding:90px 25px 25px; transition:.3s;}
.main-content.collapsed{margin-left:0;}
</style>
</head>
<body>

<div class="topbar">
    <span class="material-icons me-3" id="toggleSidebar">menu</span>
    <h6 class="m-0">SRMS</h6>
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
            <a href="#"><span class="material-icons">class</span> Classes<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-class.php">Create Class</a></li>
                <li><a href="manage-classes.php">Manage Classes</a></li>
            </ul>
        </li>
        <li class="has-children">
            <a href="#"><span class="material-icons">menu_book</span> Subjects<span class="material-icons arrow">chevron_right</span></a>
            <ul class="child-nav list-unstyled">
                <li><a href="create-subject.php">Create Subject</a></li>
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
            <a href="#"><span class="material-icons">fact_check</span> Results <span class="material-icons arrow">chevron_right</span></a>
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
        <h3>Add Subject Combination</h3>

        <?php if($msg){?>
        <div class="alert alert-success"><?php echo htmlentities($msg); ?>
        
    </div>
        
        <?php } else if($error){ ?>
        <div class="alert alert-danger"><?php echo htmlentities($error); ?></div>
        <?php } ?>

        <form method="post">
            <div class="mb-3">
                <label for="class" class="form-label">Select Class</label>
                <select name="class" id="class" class="form-select" required>
                    <option value="">Select Class</option>
                    <?php
                    $sql = "SELECT * FROM tblclasses";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $classes = $query->fetchAll(PDO::FETCH_OBJ);
                    foreach($classes as $c){
                        echo '<option value="'.htmlentities($c->id).'">'.htmlentities($c->ClassName).' Section-'.htmlentities($c->Section).'</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Select Subject</label>
                <select name="subject" id="subject" class="form-select" required>
                    <option value="">Select Subject</option>
                    <?php
                    $sql = "SELECT * FROM tblsubjects";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $subjects = $query->fetchAll(PDO::FETCH_OBJ);
                    foreach($subjects as $s){
                        echo '<option value="'.htmlentities($s->id).'">'.htmlentities($s->SubjectName).'</option>';
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Add Combination</button>
        </form>
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
    document.querySelector('.main-content').classList.toggle('collapsed');
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>