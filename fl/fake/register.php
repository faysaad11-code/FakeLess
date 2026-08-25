<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = "";

$conn = new mysqli("localhost", "root", "root", "fakeless");

if ($conn->connect_error) {
    $errors["conn"] = "Connection failed: " . $conn->connect_error;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST["name"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $phone    = trim($_POST["phone"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($name === "") {
        $errors["name"] = "Full name is required.";
    }

        if ($email === "")
            $errors["email"] = "Email is required.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors["email"] = "Invalid email format.";
        elseif ($conn->query("SELECT id FROM user WHERE email='" . $conn->real_escape_string($email) . "'")->fetch_assoc())
            $errors["email"] = "Email is already registered.";

        if ($phone === "")
            $errors["phone"] = "Phone is required.";
        elseif (!preg_match('/^05\d{8}$/', $phone))
            $errors["phone"] = "Phone must be 10 digits and start with 05.";
        elseif ($conn->query("SELECT id FROM user WHERE phone='" . $conn->real_escape_string($phone) . "'")->fetch_assoc())
            $errors["phone"] = "Phone number is already registered.";

        if ($username === "")
            $errors["username"] = "Username is required.";
        elseif ($conn->query("SELECT id FROM user WHERE username='" . $conn->real_escape_string($username) . "'")->fetch_assoc())
            $errors["username"] = "Username is already taken.";

        $confirm = $_POST["confirm"] ?? "";

        if ($password === "")
            $errors["password"] = "Password is required.";
        elseif (strlen($password) < 8)
            $errors["password"] = "Password must be more than 8 characters.";

        if ($confirm === "")
            $errors["confirm"] = "Please confirm your password.";
        elseif ($confirm !== $password)
            $errors["confirm"] = "Passwords do not match.";

        if (empty($errors)) {
            $n = $conn->real_escape_string($name);
            $e = $conn->real_escape_string($email);
            $p = $conn->real_escape_string($phone);
            $u = $conn->real_escape_string($username);
            $w = $conn->real_escape_string($password);
            $conn->query("INSERT INTO user (name, email, phone, username, password) VALUES ('$n', '$e', '$p', '$u', '$w')");
            $success = "Account created! You can now login.";
            $_POST = [];
        }

        $conn->close();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeLess – Register</title>
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
        <?php if (!isset($_SESSION["user"])): ?>
            <a href="login.php">Login</a>
        <?php else: ?>
            <a href="account.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<main class="ck-main" style="padding-top:30px;">

    <div class="ck-hero" style="margin-bottom:36px;">
        <h1>Create Account</h1>
        <p>Join FakeLess and start detecting deepfakes</p>
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

        <form class="login-form" method="POST" action="register.php">

            <div class="field-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name"                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                <?php if (isset($errors["name"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["name"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email"                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <?php if (isset($errors["email"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["email"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" maxlength="10"
                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                <?php if (isset($errors["phone"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["phone"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                <?php if (isset($errors["username"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["username"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <?php if (isset($errors["password"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["password"]); ?></span>
                <?php endif; ?>
            </div>

            <div class="field-group">
                <label for="confirm">Confirm Password</label>
                <input type="password" id="confirm" name="confirm">
                <?php if (isset($errors["confirm"])): ?>
                <span class="field-error"><?php echo htmlspecialchars($errors["confirm"]); ?></span>
                <?php endif; ?>
            </div>

            <button type="submit" class="ck-btn" style="width:100%;height:52px;font-size:1rem;">
                Register
            </button>

        </form>

        <a class="back-btn" href="login.php">
            Already have an account? Login
        </a>

    </div>

</main>


</body>
</html>
