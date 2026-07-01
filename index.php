<?php
$api = "40b305991281c6f4a3a0d837ed77f2a6";
$city = $_POST['city'] ?? "";
$url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$api&units=metric";

if ($city) {
    $weatherdata = @file_get_contents($url);
    $weatherarray = json_decode($weatherdata, true);

    if ($weatherarray && $weatherarray['cod'] == 200) {
        $cityname = $weatherarray['name'];
        // $temp = $weatherarray['main']['temp'];
        $humidity = $weatherarray['main']['humidity'];
        $temparature = $weatherarray['main']['temp'];
        $description = ucfirst($weatherarray['weather'][0]['description']);
        $speed = $weatherarray['wind']['speed'];
        $iconCode = $weatherarray['weather'][0]['icon'];
        $iconUrl = "https://openweathermap.org/img/wn/{$iconCode}@2x.png";



    } else {
        $error = "city not found";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <!-- Modern Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sky-top: #0f2027;
            --sky-bottom: #203a43;
            --sky-deep: #2c5364;
            --accent: #00d8d6;
            --glass-bg: rgba(255, 255, 255, 0.08);
            --glass-border: rgba(255, 255, 255, 0.15);
        }

        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }

        /* Flexbox wrapper to keep footer at the bottom */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--sky-top), var(--sky-bottom), var(--sky-deep));
            color: #fff;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        /* Modern Glass Navbar */
        .custom-navbar {
            background: rgba(15, 32, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            color: #fff !important;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent) !important;
        }

        .navbar-toggler {
            border: 1px solid var(--glass-border);
            padding: 6px 10px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        /* Main Content Center Area */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .dashboard {
            width: 100%;
            max-width: 400px;
            padding: 35px 28px;
            border-radius: 24px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .dashboard h2 {
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: -0.5px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0.95;
        }

        /* Search Controls */
        .search-row {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .search-row input {
            flex: 1;
            border-radius: 16px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.07);
            height: 50px;
            padding: 0 20px;
            font-size: 15px;
            color: #fff;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-row input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .search-row input:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.05);
        }

        .search-row button {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            border: none;
            background-color: #fff;
            color: var(--sky-bottom);
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .search-row button:hover {
            background-color: var(--accent);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 216, 214, 0.4);
        }

        /* Results Card Animation */
        .result-card {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .city-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .weather-icon {
            width: 110px;
            height: 110px;
            margin: -5px 0;
            filter: drop-shadow(0 8px 12px rgba(0,0,0,0.15));
        }

        .temp-big {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 6px;
            letter-spacing: -2px;
        }

        .desc {
            text-transform: capitalize;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 500;
            margin-bottom: 28px;
        }

        /* Metrics grid */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            transition: background 0.3s ease;
        }

        .stat-box:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .stat-box i {
            font-size: 22px;
            color: var(--accent);
        }

        .stat-box span {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 500;
        }

        .stat-box text {
            font-size: 1rem;
            font-weight: 600;
        }

        .error-msg {
            background: rgba(255, 76, 76, 0.15);
            border: 1px solid rgba(255, 76, 76, 0.3);
            border-radius: 14px;
            padding: 14px;
            color: #ff8888;
            font-size: 14px;
            font-weight: 500;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Glass Footer */
        .custom-footer {
            background: rgba(15, 32, 39, 0.5);
            border-top: 1px solid var(--glass-border);
            padding: 20px 0;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Fine-tuning for tiny devices */
        @media screen and (max-width: 400px) {
            .dashboard { padding: 28px 20px; }
            .stats-grid { grid-template-columns: 1fr; } /* Stack boxes on ultra-small screens */
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-md custom-navbar">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="bi bi-cloud-sun-fill" style="color: var(--accent);"></i> WeatherSky
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Radar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Body Content -->
        <div class="main-content">
            <div class="dashboard">
                <h2><i class="bi bi-cloud-sun-fill" style="color: var(--accent);"></i> Weather Forecast</h2>

                <form action="index.php" method="post">
                    <div class="search-row">
                        <input type="text" placeholder="Search city..." name="city"
                            value="<?php echo isset($city) ? htmlspecialchars($city) : ''; ?>" required autocomplete="off">
                        <button type="submit" aria-label="Search">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

               <?php
                    if (!empty($city)) {
                        if (isset($weatherarray) && $weatherarray['cod'] == 200) {
                            echo "<div class='result-card'>";
                            echo "<h4 class='city-name'><i class='bi bi-geo-alt-fill' style='color: var(--accent); font-size: 1.1rem;'></i>" . htmlspecialchars($cityname) . "</h4>";
                            echo "<img src='" . htmlspecialchars($iconUrl) . "' alt='Weather icon' class='weather-icon'>";
                            echo "<p class='temp-big'>" . htmlspecialchars($temparature) . "&deg;C</p>";
                            echo "<p class='desc'>" . htmlspecialchars($description) . "</p>";
                            echo "<div class='stats-grid'>
                                    <div class='stat-box'>
                                        <i class='bi bi-wind'></i>
                                        <span>Wind Speed</span>
                                        <text>" . htmlspecialchars($speed) . " km/h</text>
                                    </div>
                                    <div class='stat-box'>
                                        <i class='bi bi-droplet-half'></i>
                                        <span>Humidity</span>
                                        <text>" . htmlspecialchars($humidity) . "%</text>
                                    </div>
                                  </div>";
                            echo "</div>";
                        } else {
                            echo "<div class='error-msg'><i class='bi bi-exclamation-circle-fill'></i> Location not found</div>";
                        }
                    }
               ?>
            </div>
        </div>

        <!-- Footer -->
        <footer class="custom-footer">
            <div class="container">
                <p class="m-0">&copy; 2026 Iman Kalyan Bhunia — Weather -Dashboard.</p>
            </div>
        </footer>

    </div>

    <!-- Bootstrap JS Bundle for Hamburger Menu Functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>