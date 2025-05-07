<?php
include('db.php');
session_start();

if (isset($_POST['save_password'])) {
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];
    if (empty($newpassword) || empty($confirmpassword)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } elseif ($newpassword !== $confirmpassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $token = bin2hex(random_bytes(32));
        $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);
        $username = $_SESSION['username'];

        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $sql = "UPDATE users SET password_hash = '$hashed_password' WHERE username = '$username'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Password updated successfully.');</script>";
            } else {
                echo "<script>alert('Error updating password.');</script>";
            }
        } else {
            echo "<script>alert('User not found.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="global.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="forum.php">Forum</a></li>
            <li><a href="dashboard.php?logout=true">Logout</a></li>

        </ul>
    </nav>
    <div class="container">
        <div class="container mt-5 ">
            <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="user-tab" data-bs-toggle="tab" data-bs-target="#user" type="button" role="tab">User Settings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="privacy-tab" data-bs-toggle="tab" data-bs-target="#privacy" type="button" role="tab">Privacy</button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">Account</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- User Settings Tab -->
                <div class="tab-pane fade show active" id="user" role="tabpanel">
                    <div class="card shadow p-4">
                        <h3 class="mb-4">User Settings</h3>
                        <div class="text-center mb-4">
                            <div class="profile-pic">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                            <h5 class="mb-0"><?php echo strtoupper($_SESSION['username']); ?></h5>
                            <small class="text-muted"><?php echo $_SESSION['email']; ?></small>
                        </div>
                        <form method="POST" action="settings.php">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?php echo $_SESSION['username']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo $_SESSION['email']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="newpassword" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="confirmpassword" class="form-control">
                            </div>
                            <button type="submit" name="save_password" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                    <br><br><br>
                </div>

                <!-- Privacy Tab -->
                <div class="tab-pane fade" id="privacy" role="tabpanel">
                    <div class="card shadow p-4">
                        <h3>Privacy Settings</h3>
                        <p>Manage your privacy settings here.</p>
                        <form action="">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="PVisibility" name="PVisibility" checked>
                                <label class="form-check-label" for="PVisibility">Profile Visibility (Checked: Visible Unchecked: Invisible)</label>
                            </div>
                            <hr>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="PEmail" name="PEmail" checked>
                                <label class="form-check-label" for="AStatus">Activity Status (Checked: Enabled Unchecked: Disabled)</label><br>
                            </div>
                            <hr>
                            <input type="submit" class="btn btn-primary" value="Save Changes">


                        </form>

                    </div>

                </div>
                <!-- Account Tab -->
                <div class="tab-pane fade" id="account" role="tabpanel">
                    <div class="card shadow p-4">
                        <h3>Account Settings</h3>
                        <p>Manage your account information here.</p>
                        <ul>
                            <li>
                                <label for="themeSelect">Theme:</label>
                                <select id="themeSelect" class="form-select">
                                    <option value="light">Light</option>
                                    <option value="dark">Dark</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const themeSelect = document.getElementById("themeSelect");
            const cards = document.querySelectorAll(".card");
            const nav = document.querySelector("nav");
            const muted = document.querySelectorAll(".text-muted");
            // Load the saved theme from localStorage
            const savedTheme = localStorage.getItem("theme") || "light";
            document.body.classList.add(`bg-${savedTheme}`);

            if (savedTheme === "dark") {
                nav.classList.add("bg-dark");
            }
            if (savedTheme === "light") {
                nav.classList.remove("bg-light");
                nav.classList.add("")
            }
            muted.forEach(mutedText => {
                if (savedTheme === "dark") {
                    mutedText.classList.add("text-white");
                } else {
                    mutedText.classList.remove("text-white");
                }
            });
            cards.forEach(card => {
                card.classList.add(`bg-${savedTheme}`);
                if (savedTheme === "dark") {
                    card.classList.add("text-white");
                } else {
                    card.classList.remove("text-white");
                }
            });

            themeSelect.value = savedTheme;

            // Listen for theme changes
            themeSelect.addEventListener("change", function() {
                // Remove the current theme class
                document.body.classList.remove("light-theme", "dark-theme");

                // Add the selected theme class
                const selectedTheme = themeSelect.value;
                document.body.classList.add(`${selectedTheme}-theme`);
                const container = document.querySelector(".container");
                container.classList.add(`${selectedTheme}-theme`);
                // Save the selected theme to localStorage
                localStorage.setItem("theme", selectedTheme);
            });
        });
    </script>
</body>

</html>
<?php
$timeout_duration = 1800;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset();
    session_destroy(); // destroy session data in storage
    header("Location: login.php"); // redirect to login page
}
$_SESSION['LAST_ACTIVITY'] = time();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>