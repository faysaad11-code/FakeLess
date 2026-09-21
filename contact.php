<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeLess – Contact Us</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="check-style.css">
    <link rel="stylesheet" href="contact-style.css">
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
        <a href="contact.php" style="border-color:rgba(192,66,138,.6);background:rgba(192,66,138,.15);">Contact</a>
        <?php
        session_start();
        if (!isset($_SESSION["user"])): ?>
            <a href="login.php">Login</a>
        <?php else: ?>
            <a href="account.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<main class="ck-main" style="padding-top:30px;">

    <div class="ck-hero" style="margin-bottom:36px;">
        <h1>Contact Us</h1>
        <p>Have a question or feedback? We'd love to hear from you</p>
    </div>

    <div class="contact-grid" style="grid-template-columns:1fr;max-width:480px;">

        <div class="contact-info">
            <div>
                <p class="sub">We're here to help anytime</p>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="#c0428a" stroke-width="1.8"/>
                        <circle cx="12" cy="9" r="2.5" stroke="#c0428a" stroke-width="1.8"/>
                    </svg>
                </div>
                <div class="info-text">
                    <strong>Office Address</strong>
                    <span>Kingdom of Saudi Arabia</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z" stroke="#7b2fff" stroke-width="1.8"/>
                    </svg>
                </div>
                <div class="info-text">
                    <strong>Phone</strong>
                    <a href="tel:+9660501114447">0501114447</a>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <rect x="2" y="4" width="20" height="16" rx="3" stroke="#c0428a" stroke-width="1.8"/>
                        <path d="M2 8l10 6 10-6" stroke="#c0428a" stroke-width="1.8" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="info-text">
                    <strong>Email</strong>
                    <a href="mailto:info@truelens.com">info@truelens.com</a>
                </div>
            </div>

            <a class="back-btn" href="index.php">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to detector
            </a>
        </div>

    </div>

</main>

</script>

</body>
</html>
