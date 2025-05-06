<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="forms.css">
</head>

<body>
    <br><br>
    <center>
        <h1>Log In</h1>
        <form action="login.php" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br><br>
            <input type="submit" value="Log In" name="login"><br><br>

            Dont have an account? <a href="index.php">Sign Up</a>

        </form>


    </center>

</body>

</html>
<?php
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // search if the username already exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // verify the password
        if (password_verify($password, $row['password_hash'])) {
            echo "Login successful";
            //set session variables
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['password'] = $password;
            // redirect to dashboard
            header("Location: dashboard.php");
        } else {
            echo "Invalid password";
        }
    } else {
        echo "Invalid username";
    }

    $conn->close();
}
