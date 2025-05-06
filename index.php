<?php
include("db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="forms.css">
</head>

<body>
    <br><br>
    <center>
        <h1>Sign Up</h1>
        <form action="index.php" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" required><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" id="password" required><br>
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" required><br>
            <input type="submit" value="Sign Up" name="signup"><br><br>
            Already have an account? <a href="login.php">Log In</a>

        </form>

    </center>
    <div id="rules">
        Valid Password Rules:
        <ul>
            <li id="uppercase" class="invalid">1 Uppercase letter <span>✘</span></li>
            <li id="number" class="invalid">1 Number <span>✘</span></li>
            <li id="length" class="invalid">At least 8 characters <span>✘</span></li>
        </ul>
    </div>
    <script>

    </script>
</body>

</html>

<?php
if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // search if the username already exists
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "username already exists";
    } else {
        //hash the password
        $hashedp = password_hash($password, PASSWORD_DEFAULT);
        // insert the user into the database
        $sql = "INSERT INTO users (username,email,password_hash) VALUES ('$username','$email','$hashedp')";
        if (mysqli_query($conn, $sql)) {
            echo "User created successfully";
            header("Location: dashboard.php");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $conn->close();
}
