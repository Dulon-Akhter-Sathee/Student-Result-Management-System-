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
    <title>School Result Management System</title>
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            background: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 350px;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 20px;
            font-size: 22px;
        }

        label {
            display: block;
            font-size: 14px;
            margin: 8px 0 3px 0;
            text-align: left;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            border: none;
            color: #fff;
            font-size: 17px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #218838;
        }

        .back-link {
            margin-top: 10px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    
    <div class="login-box">
        <h2>School Result Management System</h2>

        <form action="result.php" method="post">
            <label for="rollid">Enter your Roll Id</label>
            <input type="text" id="rollid" name="rollid" placeholder="Enter Your Roll Id" required autocomplete="off">

            <label for="class">Select Class</label>
            <select name="class" id="class" required>
                <option value="">Select Class</option>
                <?php
                $sql = "SELECT * FROM tblclasses";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        echo '<option value="' . htmlentities($result->id) . '">' . htmlentities($result->ClassName) . ' Section-' . htmlentities($result->Section) . '</option>';
                    }
                }
                ?>
            </select>

            <button type="submit">Search</button>

            <div class="back-link">
                <a href="index.php">Back to Home</a>
            </div>
        </form>
    </div>

</body>
</html>