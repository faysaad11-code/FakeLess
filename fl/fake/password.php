<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$uid     = $_SESSION["user"]["id"];
$errors  = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "", "fakeless");

    $current = $_POST["current"]  ?? "";
    $new     = $_POST["new"]      ?? "";
    $confirm = $_POST["confirm"]  ?? "";

    if ($current === "")
        $errors["current"] = "Current password is required.";

    if ($new === "")
        $errors["new"] = "New password is required.";
    elseif (strlen($new) < 8)
        $errors["new"] = "Password must be more than 8 characters.";

    if ($confirm === "")
        $errors["confirm"] = "Please confirm your new password.";
    elseif ($confirm !== $new)
        $errors["confirm"] = "Passwords do not match.";

    if (empty($errors)) {
        $c = $conn->real_escape_string($current);
        $row = $conn->query("SELECT id FROM user WHERE id=$uid AND password='$c'")->fetch_assoc();

        if (!$row) {
            $errors["current"] = "Current password is incorrect.";
        } else {
            $w = $conn->real_escape_string($new);
            $conn->query("UPDATE user SET password='$w' WHERE id=$uid");
            $success = "Password changed successfully.";
        }
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeLess – Change Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="check-style.css">
    <link rel="stylesheet" href="login-style.css">
</head>
<body>


<nav class="ck-nav">
    <a class="logo" href="index.php">
        <img src="logo.png" alt="FakeLess">
        FakeLess
    </a>
    <div class="nav-links">
        <a href="index.php">Check Image</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="account.php">My Account</a>
        <a href="logout.php">Logout</a>
    </div>
</nav>

<main class="ck-main" style="padding-top:30px;">

    <div class="ck-hero" style="margin-bottom:36px;">
        <h1>Change Password</h1>
        <p>Enter your current password then choose a new one</p>
    </div>

    <div class="login-card">

        <?php if ($success !== ""): ?>
        <div class="login-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="#69f0ae" stroke-width="1.8"/>
                <path d="M9 12l2 2 4-4" stroke="#69f0ae" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="password.php">

            <div class="field-group">
                <label for="current">Current Password</label>
                <input type="password" id="current" name="current">
                <?php if (isset($errors["current"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["current"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="new">New Password</label>
                <input type="password" id="new" name="new">
                <?php if (isset($errors["new"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["new"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="confirm">Confirm New Password</label>
                <input type="password" id="confirm" name="confirm">
                <?php if (isset($errors["confirm"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["confirm"]); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="ck-btn" style="width:100%;height:52px;font-size:1rem;">
                Change Password
            </button>

        </form>


    </div>

</main>


</body>
</html>
