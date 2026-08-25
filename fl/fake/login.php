<?php
session_start();



$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli("localhost", "root", "root", "fakeless");

    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        $username = trim($_POST["username"] ?? "");
        $password = $_POST["password"] ?? "";

        if ($username === "" || $password === "") {
            $error = "All fields are required.";
        } else {
        $u = $conn->real_escape_string($username);
        $p = $conn->real_escape_string($password);

        $row = $conn->query("SELECT id, name FROM user WHERE username='$u' AND password='$p'")->fetch_assoc();

        if ($row) {
            $_SESSION["user"] = ["id" => $row["id"], "name" => $row["name"], "username" => $username];
            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect username or password.";
        }

        $conn->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeLess – Login</title>
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
        <a href="login.php" style="border-color:rgba(192,66,138,.6);background:rgba(192,66,138,.15);">Login</a>
        <?php if (isset($_SESSION["user"])): ?>
            <a href="account.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<main class="ck-main" style="padding-top:30px;">

    <div class="ck-hero" style="margin-bottom:36px;">
        <h1>Welcome Back</h1>
        <p>Sign in to your FakeLess account</p>
    </div>

    <div class="login-card">

        <?php if ($error !== ""): ?>
        <div class="login-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="#ff8a80" stroke-width="1.8"/>
                <path d="M12 8v4M12 16h.01" stroke="#ff8a80" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="login.php">

            <div class="field-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>

            <button type="submit" class="ck-btn" style="width:100%;height:52px;font-size:1rem;">
                Sign In
            </button>

        </form>

        <a class="back-btn" href="register.php">
            Don't have an account? Register
        </a>

    </div>

</main>


</body>
</html>
