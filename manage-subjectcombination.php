<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['alogin']) || $_SESSION['alogin']=="") {
    header("Location: index.php");
    exit();
}

// Activate Subject Combination
if(isset($_GET['acid'])) {
    $acid=intval($_GET['acid']);
    $status=1;
    $sql="UPDATE tblsubjectcombination SET status=:status WHERE id=:acid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':acid',$acid,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->execute();
    $msg="Subject Activated successfully";
}

// Deactivate Subject Combination
if(isset($_GET['did'])) {
    $did=intval($_GET['did']);
    $status=0;
    $sql="UPDATE tblsubjectcombination SET status=:status WHERE id=:did";
    $query = $dbh->prepare($sql);
    $query->bindParam(':did',$did,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->execute();
    $msg="Subject Deactivated successfully";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Subject Combination</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body { font-family: 'Poppins', sans-serif; background:#f5f6fa; overflow-x:hidden; margin:0; }
.topbar { background:#1c2530; height:64px; display:flex; align-items:center; padding:0 18px; position:fixed; top:0; width:100%; z-index:1052; color:white; }
.topbar .material-icons { cursor:pointer; }
.sidebar { width:240px; background:#212529; position:fixed; top:0; left:0; height:100%; color:white; padding-top:70px; z-index:1051; transition:transform .3s ease; overflow-y:auto; }
.sidebar.collapsed { transform:translateX(-240px); }
.sidebar .side-nav a { display:flex; align-items:center; gap:13px; padding:12px 20px; color:#dcdcdc; text-decoration:none; font-size:14px; }
.sidebar .side-nav a:hover { background: rgba(255,255,255,0.09); }
.child-nav { display:none; background:#212529; }
.has-children.open .child-nav { display:block; }
.arrow { margin-left:auto; transition:.3s; }
.has-children.open .arrow { transform:rotate(90deg); }
.profile-box { text-align:center; padding:15px 5px; background:#212529; margin-bottom:10px; }
.profile-img { width:75px; height:75px; border-radius:50%; border:2px solid #71C9CE; object-fit:cover; }
.profile-box h6 { margin-top:8px; font-size:14px; font-weight:600; }
.profile-box small { font-size:12px; color:#bbb; }
.main-content { margin-left:240px; padding:90px 25px 25px; transition:margin-left .3s ease; }
.main-content.collapsed { margin-left:0; }
.table thead th { background:#1c2530; color:white; }
.table tbody tr:hover { background: rgba(0,0,0,0.05); }
.alert { margin-top:15px; }
@media(max-width:768px){.sidebar{transform:translateX(-240px);}.sidebar.open{transform:translateX(0);}.main-content{margin-left:0 !important;}}
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

<div class="main-content" id="main">
    <div class="container-fluid">

        <?php if(isset($msg)){ ?>
            <div class="alert alert-success"><?php echo htmlentities($msg); ?></div>
        <?php } ?>

        <h3 class="mb-3">Manage Subject Combinations</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Class & Section</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $sql = "SELECT tblclasses.ClassName,tblclasses.Section,tblsubjects.SubjectName,tblsubjectcombination.id as scid,tblsubjectcombination.status 
                    FROM tblsubjectcombination 
                    JOIN tblclasses ON tblclasses.id=tblsubjectcombination.ClassId  
                    JOIN tblsubjects ON tblsubjects.id=tblsubjectcombination.SubjectId";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            $cnt=1;
            if($query->rowCount() > 0){
                foreach($results as $result){
            ?>
                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($result->ClassName);?> - Section <?php echo htmlentities($result->Section);?></td>
                    <td><?php echo htmlentities($result->SubjectName);?></td>
                    <td><?php echo $result->status ? 'Active' : 'Inactive'; ?></td>
                    <td>
                        <?php if($result->status == 0){ ?>
                        <a href="manage-subjectcombination.php?acid=<?php echo $result->scid; ?>" onclick="return confirm('Activate this subject?');"><i class="material-icons text-success">check_circle</i></a>
                        <?php } else { ?>
                        <a href="manage-subjectcombination.php?did=<?php echo $result->scid; ?>" onclick="return confirm('Deactivate this subject?');"><i class="material-icons text-danger">cancel</i></a>
                        <?php } ?>
                    </td>
                </tr>
            <?php $cnt++; } } ?>
            </tbody>
        </table>

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

</body>
</html>