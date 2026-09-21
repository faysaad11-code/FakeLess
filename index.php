<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeLess – Detect AI-Generated Content</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="check-style.css">
</head>
<body>

<div id="progressOverlay">
    <div class="ck-spinner"></div>
    <p>Analyzing image…</p>
</div>

<nav class="ck-nav">
    <a class="logo" href="index.php">
        <img src="logo.png" alt="FakeLess">
        FakeLess
    </a>
    <div class="nav-links">
        <a href="index.php" style="border-color:rgba(192,66,138,.6);background:rgba(192,66,138,.15);">Check Image</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
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

<main class="ck-main">

    <div class="ck-hero">
        <h1>Detect AI-generated<br>content</h1>
        <p>Analyze images to determine if they have been generated or altered by AI</p>
    </div>

    <input id="imageInput" type="file" accept=".png,.jpg,.jpeg,.bmp,.webp" style="display:none">

    <div id="authMsg" style="display:none;margin-bottom:20px;">
        <div class="ck-alert" style="background:rgba(192,66,138,.15);border-color:rgba(192,66,138,.5);color:#f48cbf;text-align:center;">
            You must <a href="login.php" style="color:#c0428a;font-weight:600;">login</a> first to check an image.
        </div>
    </div>

    <div id="landingUpload">
        <div id="uploadBtn" role="button" tabindex="0" aria-label="Upload Image">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
                <rect x="2" y="6" width="20" height="14" rx="3" fill="rgba(255,255,255,.25)"/>
                <circle cx="12" cy="13" r="3.5" stroke="#fff" stroke-width="1.8"/>
                <path d="M9 6l1.5-2h3L15 6" stroke="#fff" stroke-width="1.8" stroke-linejoin="round"/>
                <circle cx="18.5" cy="9.5" r="1" fill="#fff"/>
            </svg>
            <span>Upload<br>Image</span>
        </div>
    </div>

    <div id="dropZone" style="display:none;">
        <img id="imagePreview" src="" alt="Preview">
    </div>

    <div id="resultCard" style="display:none;">
        <div id="errorBox" class="ck-alert" style="display:none; word-break: break-all;"></div>
        <div id="resultContent" style="display:none;">
            <div class="result-image-wrap">
                <img id="resultImage" src="" alt="Analyzed image">
            </div>
            <div id="resultLabel"></div>
            <div class="prob-row">
                <div class="prob-label"><span>FAKE</span><span id="fakeVal"></span></div>
                <div class="prog-track"><div id="fakeBar" class="prog-fill" style="background:#ef5350;width:0%"></div></div>
            </div>
            <div class="prob-row">
                <div class="prob-label"><span>REAL</span><span id="realVal"></span></div>
                <div class="prog-track"><div id="realBar" class="prog-fill" style="background:#66bb6a;width:0%"></div></div>
            </div>
            <button class="ck-btn" id="resetBtn" style="margin-top:22px;width:100%;">Analyze another image</button>
        </div>
    </div>

</main>

<script>
(function () {
    const imageInput    = document.getElementById('imageInput');
    const uploadBtn     = document.getElementById('uploadBtn');
    const landingUpload = document.getElementById('landingUpload');
    const dropZone      = document.getElementById('dropZone');
    const preview       = document.getElementById('imagePreview');
    const overlay       = document.getElementById('progressOverlay');
    const resultCard    = document.getElementById('resultCard');
    const errorBox      = document.getElementById('errorBox');
    const resultContent = document.getElementById('resultContent');
    const resetBtn      = document.getElementById('resetBtn');

    function showOverlay()  { overlay.classList.add('active');    }
    function hideOverlay()  { overlay.classList.remove('active'); }

    function showPreview(url) {
        landingUpload.style.display = 'none';
        dropZone.style.display      = 'flex';
        preview.src                 = url;
        preview.style.display       = 'block';
        dropZone.classList.add('has-image');
    }

    function reset() {
        landingUpload.style.display = '';
        dropZone.style.display      = 'none';
        resultCard.style.display    = 'none';
        preview.style.display       = 'none';
        dropZone.classList.remove('has-image');
        preview.src = '';
        imageInput.value = '';
    }

    function showError(msg) {
        hideOverlay();
        errorBox.textContent        = msg;
        errorBox.style.display      = 'block';
        resultContent.style.display = 'none';
        resultCard.style.display    = 'block';
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showResult(json) {
        hideOverlay();
        if (!json.success) { showError(json.error || 'Unknown error'); return; }

        const pred     = json.prediction;
        const fakeProb = Math.round((json.probabilities['FAKE'] || 0) * 1000) / 10;
        const realProb = Math.round((json.probabilities['REAL'] || 0) * 1000) / 10;

        document.getElementById('resultImage').src     = json.image;
        document.getElementById('fakeVal').textContent = fakeProb + '%';
        document.getElementById('realVal').textContent = realProb + '%';

        const label = document.getElementById('resultLabel');
        label.textContent = pred === 'FAKE' ? 'FAKE — Manipulated Image' : 'REAL — Authentic Image';
        label.style.color = pred === 'FAKE' ? '#ef5350' : '#66bb6a';

        errorBox.style.display      = 'none';
        resultContent.style.display = 'block';
        resultCard.style.display    = 'block';
        resultCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        requestAnimationFrame(() => {
            document.getElementById('fakeBar').style.width = fakeProb + '%';
            document.getElementById('realBar').style.width = realProb + '%';
        });
    }

    function analyze(fd) {
        resultCard.style.display = 'none';
        showOverlay();
        fetch('analyze.php', { method: 'POST', body: fd })
            .then(async r => {
                const text = await r.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Server Output: " + text);
                }
            })
            .then(showResult)
            .catch(e => showError(e.message));
    }

    function postFile(file) {
        const fd = new FormData();
        fd.append('image', file);
        analyze(fd);
    }

    uploadBtn.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function () {
        const f = this.files[0];
        if (f) { showPreview(URL.createObjectURL(f)); postFile(f); }
    });

    resetBtn.addEventListener('click', reset);
})();
</script>

</body>
</html>