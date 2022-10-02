<?php session_start(); ?>
<html>
    <head>
        <title>Login Page</title>
        <link rel="stylesheet" href="site.css">
    </head>

    <?php
    require_once"database.php";
    if ( !empty($_POST['un']) && !empty($_POST['pw'])) {
        $un = $_POST['un'];
        $pw = $_POST['pw'];
        $sql= "SELECT Username, Password FROM Users";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            //output data of each row
            while($row = $result->fetch_assoc()) {
                if($un == $row["Username"] && $pw == $row["Password"]) {
                    $_SESSION["un"] = $un;
                    $_SESSION["success"] = "Login Successful";
                    header("Location: index.php?page=0");
                    exit();
                }
            }
            $_SESSION["error"] = "Incorrect username or password. Please try again";
            header('Location: login.php');
            exit(); 
        }
    }
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
                <h2>Login</h2>
                <?php
                    if (isset($_SESSION["error"])) {
                        echo("<script>alert('".$_SESSION["error"]."')</script>");
                        unset($_SESSION["error"]);
                    }
                ?>
                <form method="post">
                    <p>Username:<input type="text" name="un"></p>
                    <p>Password:<input type="password" name="pw"></p>
                    <button type="submit">Login</button>
                    <button onclick="location.href='register.php'; return false ">Register</button>
                </form>
            </div>
            <hr>
            <footer>Site By: C20483514 2021</footer>
        </div>
    </body>  
</html>