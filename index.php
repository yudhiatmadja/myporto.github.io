<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="hero" class="hero">
            <canvas id="heroCanvas"></canvas>
            <div class="hero-content">
                <h1>Hello, I'm Yudhi</h1>
                <p>Welcome to my portfolio</p>
            </div>
        </section>
        <section id="about" class="about">
            <h1>About Me</h1>
            <p>Iam a Photographer</p>
        </section>
        <section id="projects" class="projects">
    <h2>My Projects</h2>
    <div class="project-list">
        <?php
        require 'config.php';
        $sql = "SELECT title, description, image FROM projects";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="project-item">';
                echo '<img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["title"]) . '">';
                echo '<h3>' . htmlspecialchars($row["title"]) . '</h3>';
                echo '<p>' . htmlspecialchars($row["description"]) . '</p>';
                echo '</div>';
            }
        } else {
            echo "Belum ada proyek.";
        }

        $conn->close();
        ?>
    </div>
</section>




        <section id="contact" class="contact">
            <h1>Contact Me</h1>
            <a href="#"><i class="bi bi-whatsapp"></i></a>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Yudhi Developer. All rights reserved.</p>
    </footer>
    <script src="scripts/main.js"></script>
</body>

</html>