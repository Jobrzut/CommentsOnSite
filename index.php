<?php
    include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CommentHere!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
        <h1>Comment<span>Here!<span></h1>
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post">
            <div class="formular">
                <h2>Użytkownik:</h2>
                <input type="text" name="username" size="47.5"> <br>
                <h2>Komentarz:</h2>
                <textarea name="comment" cols="50" rows="5"></textarea> <br>
                <input type="submit" name="submit" id="formbutton"> 
            </div>
        </form>
    </div>
    <?php 

    $sql = "SELECT * FROM userscomments ORDER BY reg_date DESC"; 
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo '<ul class="comment-container">';
            echo '<li>';
            echo '<p id="user">';
            echo $row["user"] . ":";
            echo '</p>';
            echo '<p id="commenttext">';
            echo $row["comment"];
            echo '</p>';
            echo '</li>';
            echo '<ul class="comment-container">';
            }
        };

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $comment = filter_input(INPUT_POST, "comment", FILTER_SANITIZE_SPECIAL_CHARS);
        
        if(empty($username)) {
            echo "Zapomniałeś o nazwie użytkownika";
        } elseif (empty($comment)) {
            echo "A komentarz gdzie???";
        } else {
            $sql = "INSERT INTO userscomments (user, comment)
                    VALUES ('$username', '$comment')";
            try {
                mysqli_query($conn, $sql);
                echo "Dodałeś komentarz!";
                header('Location: .');
            }
            catch (mysqli_sql_exception) {
                echo "Nie udało się :/";
            }
        }
    }
?>
    <form action="index.php" method="post">
        <div class="usuwanie">
            <p>Podaj id komentarza który chcesz usunąć:</p>
            <input type="number" name="number">
            <input type="submit" name="delete" id="usuwaniebutton" value="✓">
        </div>
    </form>
</body>
</html>

<?php
    if (isset($_POST["delete"])) {
        $whichid = filter_input(INPUT_POST, "number", FILTER_SANITIZE_SPECIAL_CHARS);
        $sql = "DELETE FROM userscomments WHERE id = {$whichid}";
        mysqli_query($conn, $sql);
        header('Location: .');
    }
?>