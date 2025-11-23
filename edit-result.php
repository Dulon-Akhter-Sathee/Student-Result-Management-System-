<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
    exit();
}

$stid=intval($_GET['stid']);
$msg = '';
$error = '';

if(isset($_POST['submit'])) {
    $rowid=$_POST['id'];
    $marks=$_POST['marks']; 

    foreach($_POST['id'] as $count => $id){
        $mrks=$marks[$count];
        $iid=$rowid[$count];

        $sql="UPDATE tblresult SET marks=:mrks WHERE id=:iid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':mrks',$mrks,PDO::PARAM_STR);
        $query->bindParam(':iid',$iid,PDO::PARAM_STR);
        $query->execute();
    }
    $msg="Result info updated successfully";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student Result | SRMS</title>
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
@media(max-width:768px) {
    .sidebar { transform:translateX(-240px); }
    .sidebar.open { transform:translateX(0); }
    .main-content { margin-left:0 !important; }
}
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
                <li><a href="add-results.php">Add Results</a></li>
                <li><a href="manage-results.php" class="active">Manage Results</a></li>
            </ul>
        </li>
        <li><a href="change-password.php"><span class="material-icons">key</span> Change Password</a></li>
        <li><a href="logout.php"><span class="material-icons">logout</span> Logout</a></li>
    </ul>
</div>

<div class="main-content" id="main">
    <div class="card-box">
        <h4>Edit Student Result</h4>

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

        <form method="post">
            <?php
            $ret = "SELECT tblstudents.StudentName,tblclasses.ClassName,tblclasses.Section 
                    FROM tblstudents 
                    JOIN tblclasses ON tblclasses.id=tblstudents.ClassId 
                    WHERE tblstudents.StudentId=:stid LIMIT 1";
            $stmt = $dbh->prepare($ret);
            $stmt->bindParam(':stid',$stid,PDO::PARAM_STR);
            $stmt->execute();
            $student = $stmt->fetch(PDO::FETCH_OBJ);
            if($student) {
            ?>
            <div class="mb-3">
                <label class="form-label">Class</label>
                <input type="text" class="form-control" value="<?php echo htmlentities($student->ClassName.' ('.$student->Section.')'); ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Student Name</label>
                <input type="text" class="form-control" value="<?php echo htmlentities($student->StudentName); ?>" readonly>
            </div>
            <?php
            }

            $sql = "SELECT tblsubjects.SubjectName, tblresult.marks, tblresult.id as resultid 
                    FROM tblresult 
                    JOIN tblsubjects ON tblsubjects.id=tblresult.SubjectId 
                    WHERE tblresult.StudentId=:stid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':stid',$stid,PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            foreach($results as $res) {
            ?>
            <div class="mb-3">
                <label class="form-label"><?php echo htmlentities($res->SubjectName); ?></label>
                <input type="hidden" name="id[]" value="<?php echo htmlentities($res->resultid); ?>">
                <input type="text" name="marks[]" class="form-control" value="<?php echo htmlentities($res->marks); ?>" required>
            </div>
            <?php } ?>

            <button type="submit" name="submit" class="btn btn-primary">Update Result</button>
        </form>
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
