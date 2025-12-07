<?php
session_start();

// ===================== API Cat Facts =====================
$catFactApi = "https://catfact.ninja/fact";
$response = file_get_contents($catFactApi);
$data = json_decode($response, true);
$fact_en = $data["fact"] ?? "Unable to retrieve cat fact.";

// ===================== API Terjemahan =====================
$translateApi = "https://api.mymemory.translated.net/get?q=" . urlencode($fact_en) . "&langpair=en|id";
$translation = file_get_contents($translateApi);
$transData = json_decode($translation, true);
$fact_id = $transData["responseData"]["translatedText"] ?? $fact_en;

// =============== History Sementara (Session) ===============
if (!isset($_SESSION["history"])) {
    $_SESSION["history"] = [];
}

// Simpan history ketika halaman dimuat
$_SESSION["history"][] = $fact_id;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RestClient4 - Cat Facts</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body.dark-mode {
            background-color: #121212 !important;
            color: #ffffff !important;
        }
        .dark-mode .card, 
        .dark-mode .card-body {
            background-color: #1f1f1f !important;
            color: #ffffff !important;
        }
        .dark-mode .alert-info {
            background-color: #333333 !important;
            border-color: #555555 !important;
            color: #ffffff !important;
        }
        .dark-mode .navbar {
            background-color: #1f1f1f !important;
        }
        .page-section {
            display: none;
        }
        /* background history mengikuti mode */
        .history-item {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .light-history {
            background: #f1f1f1;
            color: #000;
        }
        .dark-history {
            background: #2a2a2a;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

<!-- ==================== NAVBAR ======================= -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3" id="navbar">
    <a class="navbar-brand fw-bold" href="#" onclick="showPage('home')">RestClient4</a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('home')">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('history')">History</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('contact')">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('about')">About</a></li>
        </ul>

        <button id="themeToggle" class="btn btn-outline-dark">Dark Mode</button>
    </div>
</nav>

<!-- ==================== HOME SECTION ======================= -->
<div id="home" class="page-section container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <h3 class="text-center mb-3">🐾 Fakta Kucing</h3>
                    <p class="text-center text-muted">Diterjemahkan ke Bahasa Indonesia</p>

                    <div class="alert alert-info fs-5"><?= htmlspecialchars($fact_id) ?></div>

                    <div class="text-center">
                        <a href="index.php" class="btn btn-primary">Ambil Fakta Baru</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== JS ======================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ==================== PAGE SWITCHER =======================
function showPage(page) {
    document.querySelectorAll(".page-section").forEach(div => div.style.display = "none");
    document.getElementById(page).style.display = "block";
    window.scrollTo(0, 0);
}
showPage("home");

// ==================== DARK / LIGHT MODE =======================
document.addEventListener("DOMContentLoaded", function () {
    const theme = localStorage.getItem("theme");
    const body = document.body;
    const navbar = document.getElementById("navbar");
    const btn = document.getElementById("themeToggle");

    if (theme === "dark") {
        body.classList.add("dark-mode");
        navbar.classList.add("navbar-dark");
        navbar.classList.remove("navbar-light","bg-white");
        btn.textContent = "Light Mode";
        btn.classList.add("btn-outline-light");
        btn.classList.remove("btn-outline-dark");

        document.querySelectorAll(".history-item").forEach(i => {
            i.classList.remove("light-history");
            i.classList.add("dark-history");
        });
    }
});

// Toggle mode
document.getElementById("themeToggle").addEventListener("click", function () {
    const body = document.body;
    const navbar = document.getElementById("navbar");
    const btn = document.getElementById("themeToggle");

    body.classList.toggle("dark-mode");

    document.querySelectorAll(".history-item").forEach(i => {
        if (body.classList.contains("dark-mode")) {
            i.classList.remove("light-history");
            i.classList.add("dark-history");
        } else {
            i.classList.remove("dark-history");
            i.classList.add("light-history");
        }
    });

    if (body.classList.contains("dark-mode")) {
        localStorage.setItem("theme", "dark");
        navbar.classList.remove("navbar-light","bg-white");
        navbar.classList.add("navbar-dark");

        btn.textContent = "Light Mode";
        btn.classList.remove("btn-outline-dark");
        btn.classList.add("btn-outline-light");

    } else {
        localStorage.setItem("theme", "light");
        navbar.classList.remove("navbar-dark");
        navbar.classList.add("navbar-light","bg-white");

        btn.textContent = "Dark Mode";
        btn.classList.remove("btn-outline-light");
        btn.classList.add("btn-outline-dark");
    }
});
</script>

</body>
</html>
