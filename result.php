<?php
session_start();
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result</title>
    <style>
        body {
            background: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .result-box {
            width: 500px;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        h2, h3 {
            margin-bottom: 15px;
        }

        p {
            margin: 5px 0;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .back-link {
            margin-top: 15px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
            color: #fff;
        }

        .alert-warning {
            background-color: #f0ad4e;
        }

        .alert-danger {
            background-color: #d9534f;
        }
    </style>
</head>
<body>
<div class="result-box" id="exampl">

    <h2>Student Result Management System</h2>

<?php
$rollid = $_POST['rollid'];
$classid = $_POST['class'];
$_SESSION['rollid'] = $rollid;
$_SESSION['classid'] = $classid;

// Fetch student details
$qery = "SELECT tblstudents.StudentName, tblstudents.RollId, tblstudents.StudentId, tblclasses.ClassName, tblclasses.Section 
FROM tblstudents 
JOIN tblclasses ON tblclasses.id = tblstudents.ClassId 
WHERE tblstudents.RollId=:rollid AND tblstudents.ClassId=:classid";

$stmt = $dbh->prepare($qery);
$stmt->bindParam(':rollid', $rollid, PDO::PARAM_STR);
$stmt->bindParam(':classid', $classid, PDO::PARAM_STR);
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_OBJ);

if($student) {
    echo "<p><b>Student Name:</b> " . htmlentities($student->StudentName) . "</p>";
    echo "<p><b>Roll ID:</b> " . htmlentities($student->RollId) . "</p>";
    echo "<p><b>Class:</b> " . htmlentities($student->ClassName) . " (" . htmlentities($student->Section) . ")</p>";

    // Fetch results
    $query = "SELECT tblsubjects.SubjectName, tr.marks
              FROM tblresult tr
              JOIN tblstudents s ON s.StudentId = tr.StudentId
              JOIN tblsubjects ON tblsubjects.id = tr.SubjectId
              WHERE s.RollId=:rollid AND s.ClassId=:classid";
    $res = $dbh->prepare($query);
    $res->bindParam(':rollid', $rollid, PDO::PARAM_STR);
    $res->bindParam(':classid', $classid, PDO::PARAM_STR);
    $res->execute();
    $results = $res->fetchAll(PDO::FETCH_OBJ);

    if(count($results) > 0) {
        $totlcount = 0;
        echo "<table>";
        echo "<tr><th>#</th><th>Subject</th><th>Marks</th></tr>";
        $cnt = 1;
        foreach($results as $result) {
            echo "<tr>";
            echo "<td>" . $cnt . "</td>";
            echo "<td>" . htmlentities($result->SubjectName) . "</td>";
            echo "<td>" . htmlentities($result->marks) . "</td>";
            echo "</tr>";
            $totlcount += $result->marks;
            $cnt++;
        }
        $outof = ($cnt-1)*100;
        $percentage = ($totlcount*100)/$outof;
        echo "<tr><th colspan='2'>Total Marks</th><td>$totlcount out of $outof</td></tr>";
        echo "<tr><th colspan='2'>Percentage</th><td>$percentage%</td></tr>";
        echo "</table>";
        
    } else {
        echo "<div class='alert alert-warning'>Notice! Your result has not been declared yet.</div>";
    }

} else {
    echo "<div class='alert alert-danger'>Invalid Roll ID or Class selection.</div>";
}
?>

<div class="back-link">
    <a href="index.php">Back to Home</a>
</div>
</div>

</body>
</html>
