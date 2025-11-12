<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(!isset($_SESSION['alogin']) || $_SESSION['alogin']=="") {
    header("Location: index.php");
    exit();
}

$delmsg = "";

if(isset($_GET['id']) && isset($_GET['del'])) { 
    $subid = $_GET['id'];
    $sql = "DELETE FROM tblsubjects WHERE id = :subid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':subid', $subid, PDO::PARAM_INT);
    $query->execute();
    $delmsg = "Subject deleted successfully.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Subjects | SRMS</title>
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
.sidebar .side-nav a:hover { background: rgba(255,255,255,0.09); }
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
.table-responsive { margin-top:10px; }
.table th, .table td { vertical-align: middle; }
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

<div class="main-content" id="main">
    <div class="card-box">
        <h4>Manage Subjects</h4>

        
        <?php if($delmsg){ ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?php echo htmlentities($delmsg); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Creation Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM tblsubjects ORDER BY Creationdate DESC";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if($query->rowCount() > 0){
                        foreach($results as $result){ ?>
                            <tr>
                                <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result->SubjectName);?></td>
                                <td><?php echo htmlentities($result->SubjectCode);?></td>
                                <td><?php echo htmlentities($result->Creationdate);?></td>
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit-subject.php?subjectid=<?php echo htmlentities($result->id);?>" 
                                           class="btn btn-info btn-sm">Edit</a>

                                        <a href="manage-subjects.php?id=<?php echo $result->id;?>&del=delete" 
                                           onclick="return confirm('Are you sure?');" 
                                           class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                </td>
                            </tr>
                    <?php $cnt++; }} ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Sidebar toggle
document.getElementById('toggleSidebar').addEventListener('click', function(){
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('main').classList.toggle('collapsed');
});

// Submenu toggle
document.querySelectorAll('.has-children > a').forEach(a => {
    a.addEventListener('click', function(e){
        e.preventDefault();
        this.parentElement.classList.toggle('open');
    });
});
</script>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
