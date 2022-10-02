<?php
    session_start();
    require_once"database.php";
    if (isset($_GET["book"])) {
        $ISBN = $_GET["book"];
        $un = $_SESSION["un"];
        $rdate = date('Y-m-d');
        $sql = "INSERT INTO Reservations (ISBN, Username, ReservedDate)
        VALUES ('$ISBN', '$un', '$rdate')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New Reservation Added')</script>";
            $sql = "UPDATE books SET Reserved = 'Y' WHERE ISBN = '$ISBN'";
            $conn->query($sql);
        } 
        else {
                echo "Error: Reservation could not be completed";
        }
    }
    
    if(isset($_GET["remove"])) {
        $ISBN = $_GET["remove"];
        $sql = "DELETE FROM Reservations WHERE ISBN = '$ISBN'";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Reservation Deleted')</script>";
            $sql = "UPDATE books SET Reserved = 'N' WHERE ISBN = '$ISBN'";
            $conn->query($sql);
        } 
        else {
                echo "Error: Reservation could not be deleted";
            }
    }
?>

<html>
    <head>
        <title>Reservations</title>
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

            <div id="result">
                <?php
                $un = $_SESSION["un"];
                $sql= "SELECT * FROM books WHERE ISBN IN (SELECT ISBN FROM Reservations WHERE Username = '$un')";
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
                        "</td>"."<td><button onclick=".'location.href="reserve.php?remove='.$row["ISBN"].'"; return false >Remove</button></td>'
                        ."</tr>\n";
                    }
                    echo "</table>\n";
                }
                else {
                    echo "0 results";
                }
                    
                ?>
            </div>
            <div><button onclick='location.href="index.php?page=0"; return false'>Return to Main Page</button></div>
            <hr>
            <footer>Site By: C20483514 2021</footer>
        </div>
    </body>
</html>