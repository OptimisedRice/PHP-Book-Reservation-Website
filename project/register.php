<?php
    session_start();
?>
<html>
    <head>
        <title>Register Page</title>
        <link rel="stylesheet" href="site.css">
    </head>

    <?php
    require_once"database.php";
    if ( !empty($_POST['un']) && !empty($_POST['pw']) && !empty($_POST['pw2'])
    && !empty($_POST['fn']) && !empty($_POST['sn']) && !empty($_POST['al1']) 
     && !empty($_POST['city']) && !empty($_POST['tele']) && !empty($_POST['mobile'])) {
        $un = $_POST['un'];
        $pw = $_POST['pw'];
        $pw2 = $_POST['pw2'];
        if (strlen($pw) > 6) {
            $_SESSION["error"] = "Password must be 6 characters or less";
            header('Location: register.php');
            exit();
        }
        
        if ($pw != $pw2) {
            $_SESSION["error"] = "Passwords do not match";
            header('Location: register.php');
            exit();
        }
        $fn = $_POST['fn'];
        $sn = $_POST['sn'];
        $al1 = $_POST['al1'];
        $al2 = $_POST['al2'];
        $city = $_POST['city'];
        $tele = $_POST['tele'];
        $mobile = $_POST['mobile'];
        if(strlen((string)$mobile) != 10) {
            $_SESSION["error"] = "Mobile phone numbers must be 10 numbers in length";
            header('Location: register.php');
            exit();
        }
        $sql= "INSERT INTO Users
        VALUES ('$un', '$pw', '$fn', '$sn', '$al1', '$al2', '$city', $tele, $mobile)";
        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("New user registered successfully");</script>';
        } 
        else {
            $sql= "SELECT Username FROM Users";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                //output data of each row
                while($row = $result->fetch_assoc()) {
                    if($un == $row["Username"]) {
                        $_SESSION["error"] = "Error: Username already exists";
                        header('Location: register.php');
                        exit();
                    }
                }
                echo "Error:" . $sql . "<br>" . $conn->error;
                
            }
        }
    }

    elseif (count($_POST) > 0) {
        $_SESSION["error"] = "Error: Make sure all fields are filled in.";
        header('Location: register.php');
        return;
    }
        $conn->close();
    ?>
    <body id="bg">
        <div id="main1">
            <header>
                <br>
                <h1 id="main_heading">Welcome to Kieran's Library</h1>
                <br>
                <hr>
            </header>
            
            <div id="contact">
                <h2>Register</h2>
                <?php
                    if (isset($_SESSION["error"])) {
                        echo("<script>alert('".$_SESSION["error"]."')</script>");
                        unset($_SESSION["error"]);
                    }
                ?>
                <form method="post">
                    <div><label>Username:</label><input type="text" name="un"></div>
                    <div><label>Password:</label><input type="password" name="pw"></div>
                    <div><label>Confirm Password:</label><input type="password" name="pw2"></div>
                    <div><label>Firstname:</label><input type="text" name="fn"></div>
                    <div><label>Surname:</label><input type="text" name="sn"></div>
                    <div><label>Address Line 1:</label><input type="text" name="al1"></div>
                    <div><label>Address Line 2:</label><input type="text" name="al2"></div>
                    <div><label>City:</label><input type="text" name="city"></div>
                    <div><label>Telephone:</label><input type="number" name="tele"></div>
                    <div><label>Mobile:</label><input type="number" name="mobile"></div>

                    <button type="submit">Register</button>
                    <button onclick="location.href='login.php'; return false ">Cancel</button>

                </form>
            </div>
            <hr>
            <footer>Site By: C20483514 2021</footer>
        </div>
    </body> 
</html>