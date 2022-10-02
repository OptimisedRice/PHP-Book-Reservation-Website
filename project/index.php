<?php
    session_start();
    if (isset($_SESSION["success"])) {
        echo("<script>alert('".$_SESSION["success"]."')</script>");
        unset($_SESSION["success"]);
        $_POST['book'] = '';
        $_POST['searchtype'] = 'both';
        $_SESSION['searchtype'] = $_POST['searchtype'];
    }

    if (!isset($_SESSION["un"])) {
        echo("<script>alert('No user logged in')</script>");
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once"database.php";
?>
<html>
    <head>
        <title>Welcome</title>
        <link rel="stylesheet" href="site.css">
    </head>

    <body id="bg">
        <div id="main1">
            <header>
                <br>
                <h1 id="main_heading">Welcome to Kieran's Library</h1>
                <br>
                <hr>
            </header>

            <div>
                <button onclick="location.href='logout.php'; return false ">Logout</button>
            </div>

            <div id="contact">
                <h2>Search</h2>

                <?php
                    if (isset($_SESSION["error"])) {
                        if ($_SESSION['error'] == 'Error: Please choose a searchtype') {
                            $_POST['book'] = '';
                            $_POST['searchtype'] = 'both';
                        }
                        echo("<script>alert('".$_SESSION["error"]."')</script>");
                        unset($_SESSION["error"]);
                    }
                ?>

                <form method="post">
                    <div>Search by keyword (Book title or Author)<input type="text" name="book"></div>
                    <div>
                        <input class="rad" type="radio" id="bt" name="searchtype" value="booktitle"
                        <?php if (isset($_POST['searchtype']) && $_POST['searchtype'] == 'booktitle') echo 'checked'?>>
                        <label for="bt">Book Title</label><br>
                        <input class="rad" type="radio" id="aut" name="searchtype" value="author"
                        <?php if (isset($_POST['searchtype']) && $_POST['searchtype'] == 'author') echo 'checked'?>>
                        <label for="aut">Author</label><br>
                        <input class="rad" type="radio" id="b" name="searchtype" value="both"
                        <?php if (isset($_POST['searchtype']) && $_POST['searchtype'] == 'both') echo 'checked'?>>
                        <label for="b">Both</label>
                    </div>
                    <button type="submit">Search</button>
                </form>

                <form method="post" name="CategorySelect">
                    <p>Search by Category Description</p>
                    <select name="Categories" onchange="CategorySelect.submit()">
                    <option value="">-- Please Select --</option>
                    <?php
                        $sql = "SELECT CategoryDescription FROM categories";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo("<option value='".$row["CategoryDescription"]."'>".$row["CategoryDescription"]."</option>");
                        }
                    ?>
                    </select>
                </form>
            </div>

            <div id="result">
                <div class="button"><button onclick="location.href='reserve.php'; return false ">View Reserved Books</button></div>
                <br>
                <?php
                if(!isset($_POST['book']) && !isset($_SESSION['Categories'])) {
                    $_POST['book'] = $_SESSION['book'];

                }

                if(isset($_POST['Categories'])) {
                unset($_POST['book']);
                }

                if(isset($_POST['book'])) {
                    unset($_SESSION['Categories']);
                    $book = $_POST['book'];
                    if(isset($_GET['page'])) {
                        $page = $_GET['page'];
                    }

                    if(isset($_POST['searchtype'])) {
                        $_SESSION['searchtype'] = $_POST['searchtype'];
                    }

                    else {
                        $_POST['searchtype'] = $_SESSION['searchtype'];
                    }

                    if (isset($_GET['page']) && isset($_POST['searchtype']) && $_POST['searchtype'] == 'booktitle') {
                        $sql= "SELECT * FROM books WHERE (BookTitle LIKE '%$book%')";
                        $result = $conn->query($sql);
                        $pagenum = ceil(($result->num_rows)/5);
                        if($pagenum < ($page / 5) + 1 && $result->num_rows > 0) {
                            $_SESSION['book'] = $book;
                            header("Location: index.php?page=0");
                            exit();
                        }
                        $sql= "SELECT * FROM books WHERE (BookTitle LIKE '%$book%') LIMIT $page, 5";
                    }

                    elseif (isset($_GET['page']) && isset($_POST['searchtype']) && $_POST['searchtype'] == 'author') {               
                        $sql= "SELECT * FROM books WHERE (Author LIKE '%$book%')";
                        $result = $conn->query($sql);
                        $pagenum = ceil(($result->num_rows)/5);
                        if($pagenum < ($page / 5) + 1 && $result->num_rows > 0) {
                            $_SESSION['book'] = $book;
                            header("Location: index.php?page=0");
                            exit();
                        }
                        $sql= "SELECT * FROM books WHERE (Author LIKE '%$book%') LIMIT $page, 5";
                        
                    }

                    elseif (isset($_GET['page']) && isset($_POST['searchtype']) && $_POST['searchtype'] == 'both') {
                        $sql= "SELECT * FROM books WHERE (BookTitle LIKE '%$book%' OR Author LIKE '%$book%')";
                        $result = $conn->query($sql);
                        $pagenum = ceil(($result->num_rows)/5);
                        if($pagenum < ($page / 5) + 1 && $result->num_rows > 0) {
                            $_SESSION['book'] = $book;
                            header("Location: index.php?page=0");
                            exit();
                        }
                        $sql= "SELECT * FROM books WHERE (BookTitle LIKE '%$book%' OR Author LIKE '%$book%') LIMIT $page, 5";
                    }

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "searched by: ".$_POST['searchtype'];
                        echo "<table border='1'>";
                        echo "<thead>";
                        echo "<tr><td>"."ISBN".
                        "</td><td>"."BookTitle".
                        "</td><td>" ."Author".
                        "</td><td>" ."Edition".
                        "</td><td>" ."Year".
                        "</td><td>" ."CategoryID".
                        "</td><td>" ."Reserved".
                        "</td></tr>\n";
                        echo "</thead>\n";
                        //output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["ISBN"].
                            "</td><td>".$row["BookTitle"].
                            "</td><td>" .$row["Author"].
                            "</td><td>" .$row["Edition"].
                            "</td><td>" .$row["Year"].
                            "</td><td>" .$row["CategoryID"].
                            "</td><td>" .$row["Reserved"].
                            "</td>";
                            if($row["Reserved"] == 'N') {
                                echo "<td><button onclick=".'location.href="reserve.php?book='.$row["ISBN"].'"; return false >Reserve</button></td>';
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";
                        $i = 0;
                        while($i < $pagenum) {
                            echo "<button"." onclick=".'location.href="index.php?page='.($i * 5).'"; return false >'.($i + 1)."</button>";
                            $_SESSION['book'] = $book;
                            $i++;
                        }
                    }

                    else {
                        echo "searched by: ".$_POST['searchtype']."</br>";
                        echo "0 results";
                    }
                }
                if(!isset($_POST['Categories']) && !isset($_SESSION['book'])) {
                    $_POST['Categories'] = $_SESSION['Categories'];
                }

                if(isset($_POST['book'])) {
                    unset($_POST['Categories']);
                }

                if(isset($_POST['Categories'])) {
                    unset($_SESSION['book']);
                    $category = $_POST['Categories'];
                    if(isset($_GET['page'])) {
                        $page = $_GET['page'];
                    }

                    if($category == '' && ! isset($_GET['Categories'])) {
                        $sql= "SELECT * FROM books";
                    }

                    elseif(isset($_GET['Categories']) && $category == '') {
                        $sql= "SELECT * FROM books";
                        $result = $conn->query($sql);
                        $pagenum = ceil(($result->num_rows)/5);
                        $sql= "SELECT * FROM books LIMIT $page, 5";
                    }

                    else {
                        $sql= "SELECT * FROM books WHERE CategoryID LIKE (SELECT CategoryID FROM categories WHERE CategoryDescription = '$category')";
                        $result = $conn->query($sql);
                        $pagenum = ceil(($result->num_rows)/5);
                        $sql= "SELECT * FROM books WHERE CategoryID LIKE (SELECT CategoryID FROM categories WHERE CategoryDescription = '$category') LIMIT $page, 5";
                    }

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<table border='1'>";
                        echo "<thead>";
                        echo "<tr><td>"."ISBN".
                        "</td><td>"."BookTitle".
                        "</td><td>" ."Author".
                        "</td><td>" ."Edition".
                        "</td><td>" ."Year".
                        "</td><td>" ."CategoryID".
                        "</td><td>" ."Reserved".
                        "</td></tr>\n";
                        echo "</thead>\n";
                        //output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>";
                            echo $row["ISBN"].
                            "</td><td>".$row["BookTitle"].
                            "</td><td>" .$row["Author"].
                            "</td><td>" .$row["Edition"].
                            "</td><td>" .$row["Year"].
                            "</td><td>" .$row["CategoryID"].
                            "</td><td>" .$row["Reserved"].
                            "</td>";
                            if($row["Reserved"] == 'N') {
                                echo "<td><button onclick=".'location.href="reserve.php?book='.$row["ISBN"].'"; return false >Reserve</button></td>';
                            }
                            echo "</tr>\n";
                        }
                        echo "</table>\n";
                        $i = 0;
                        while($i < $pagenum) {
                            echo "<button"." onclick=".'location.href="index.php?page='.($i * 5).'"; return false >'.($i + 1)."</button>";
                            $_SESSION['Categories'] = $category;
                            $i++;
                        }
                    }

                    else {
                        echo "0 results";
                    }
                }
                ?>
            </div>
            <hr>
            <footer>Site By: C20483514 2021</footer>
        </div>
    </body>
</html>