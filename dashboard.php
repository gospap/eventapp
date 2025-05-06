<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle session timeout
$timeout_duration = 1800;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration)) {
    session_unset();
    session_destroy(); // destroy session data in storage
    header("Location: login.php"); // redirect to login page
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php"); // redirect to login page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
    <div class="welcome"><?php echo "<h1>Welcome back " . $_SESSION['username'] . " !</h1>"; ?></div>
    <center><input type="text" id="search" name="searchparties" placeholder="Where are the parties at?"></center>
    <div class="container">
        <!-- Displaying the parties from the database -->
        <h2>Upcoming Events</h2>
        <div class="party-container">
            <div class="container mt-4">
                <div class="row">
                    <?php
                    $query = "SELECT * FROM parties ORDER BY date DESC LIMIT 5";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $party_id = $row['id'];
                            $max_guests = $row['max_guests'];

                            $count_query = "SELECT COUNT(*) as guest_count FROM party_guests WHERE party_id='$party_id'";
                            $count_result = mysqli_query($conn, $count_query);
                            $count_row = mysqli_fetch_assoc($count_result);
                            $rsvp_count = $count_row['guest_count'];

                            $remaining = $max_guests - $rsvp_count;
                            echo "<div class='col-md-4 mb-4'>";
                            echo "<div class='card h-100'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($row['title']) . "</h5>";
                            echo "<h6 class='card-subtitle mb-2 text-muted'>" . htmlspecialchars($row['date']) . "</h6>";
                            echo "<p class='card-text'><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                            echo "<p class='card-text'>" . htmlspecialchars($row['description']) . "</p>";
                            if ($remaining > 0) {
                                echo "<form method='post' action=''>";
                                echo "<input type='hidden' name='party_id' value='" . $row['id'] . "'>";
                                echo "<button type='submit' name='join_party' class='btn btn-primary'>I will go</button>";
                                echo "</form>";
                            } else {
                                echo "<p class='card-text text-danger'>Party is full!</p>";
                            }
                            echo "<div class='card-footer'>Remaining: " . $remaining . "/" . $max_guests . " empty spots</div>";

                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No upcoming parties found.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Event Button -->
    <?php
    if ($_SESSION['username'] === "gospap" && $_SESSION['password'] === "giorgosnikos2005") {
        echo '<button id="addEventBtn" class="btn btn-primary">Add Event</button>';
    }
    ?>
    <!-- Popup Overlay -->
    <div id="popupOverlay" class="popup-overlay">
        <div class="popup-content">
            <span id="closePopup" class="close-btn">&times;</span>
            <h3>Add New Event</h3>
            <form method="post" action="">
                <input type="text" id="eventadd" name="title" placeholder="Title" required><br>
                <textarea name="description" placeholder="Description" required></textarea><br>
                <input type="text" id="eventadd" name="location" placeholder="Location" required><br>
                <input type="date" id="eventadd" name="date" required><br>
                <input type="number" id="eventadd" name="max_guests" placeholder="Max Guests" required><br>
                <label><input type="checkbox" name="invite_only" value="1"> Invite Only</label><br>
                <button type="submit" name="add_event" class="btn btn-success">Add Event</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('addEventBtn').addEventListener('click', function() {
            document.getElementById('popupOverlay').style.display = 'flex';
        });

        document.getElementById('closePopup').addEventListener('click', function() {
            document.getElementById('popupOverlay').style.display = 'none';
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const themeSelect = document.getElementById("themeSelect");
            const cards = document.querySelectorAll(".card");
            const nav = document.querySelector("nav");
            const cardtext = document.querySelectorAll(".card-text");
            // Load the saved theme from localStorage
            const savedTheme = localStorage.getItem("theme") || "light";
            document.body.classList.add(`bg-${savedTheme}`);
            if (savedTheme ===
                "dark") {
                nav.classList.add("bg-dark");
            }
            cardtext.forEach(text => {
                if (savedTheme === "dark") {
                    text.classList.add("text-white");
                } else {
                    text.classList.remove("text-white");
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
                document.body.classList.add(`bg-${selectedTheme}`);
                const container = document.querySelector(".container");
                container.classList.add(`bg-${selectedTheme}`);
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
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // redirect to login page if not logged in
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php"); // redirect to login page after logout
    exit();
}

// party joining
if (isset($_POST['join_party'])) {
    $party_id = $_POST['party_id'];
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM party_guests WHERE user_id='$user_id' AND party_id='$party_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO party_guests (party_id,user_id,rsvp_status,checked_in) VALUES ('$party_id','$user_id','going',0)";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('You have successfully joined the party!');</script>";
        } else {
            echo "<script>alert('Error joining the party. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('You are already going to this party!');</script>";
    }
}

// handle adding an event or party
if (isset($_POST['add_event'])) {
    $host_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $max_guests = $_POST['max_guests'];
    $invite_only = isset($_POST['invite_only']) ? 1 : 0;
    $created_at = date('Y-m-d H:i:s');

    // Insert the new event into the database
    $sql = "INSERT INTO parties (host_id,title, description, location, date, max_guests, invite_only,created_at) VALUES ('$host_id','$title', '$description', '$location', '$date', '$max_guests', '$invite_only','$created_at')";

    if (mysqli_query($conn, $sql)) {
        // Use header() for redirect to avoid resubmission

        header("Location: dashboard.php?success=1");
        exit();
    } else {
        header("Location: dashboard.php?error=1");
        exit();
    }
}
?>