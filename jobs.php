<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Job openings at CTRL-ALT-INNOVATE" />
    <meta name="keywords" content="team, MoreBugs, students, coding, technology, web development, programming, project, team members, web design, university, job" />
    <meta name="author" content="Ethan Kimel" />
    <title>CTRL Jobs | CTRL-ALT-INNOVATE</title>
    <link rel="stylesheet" href="./styles/styles.css" />
</head>
<body class="Jobs">

    <?php include 'navbar.inc'; ?>

    <div class="content">
        <div class="section-dark top-section">
            <h1 class="heading-styling">Available Job Positions</h1>
            <p class="content-row">
                CTRL-ALT-INNOVATE is looking to hire! We're looking to employ driven minds that want to join the team and grow with us.
            </p>
        </div>

        <br />

        <section class="team-cards">

        <?php
        include 'settings.php'; // DB connection

        $sql = "SELECT * FROM jobs";
        $result = $conn->query($sql);
        $counter = 1;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $title = htmlspecialchars($row['title']);
                $description = htmlspecialchars($row['description']);
                $responsibilities = explode("\n", $row['responsibilities']);
                $essential = explode("\n", $row['essential_qualifications']);
                $ideal = explode("\n", $row['ideal_qualifications']);
                $salary = htmlspecialchars($row['salary']);
                $ref_id = htmlspecialchars($row['ref_id']);
                $reports_to = htmlspecialchars($row['reports_to']);
                $image = htmlspecialchars(trim($row['image'], '"'));
                ?>

                <div class="accordion-card">
                    <input type="checkbox" id="card<?= $counter ?>" class="toggle-card" />
                    <label for="card<?= $counter ?>" class="card-title"><?= $title ?></label>
                    <div class="card-content">
                        <div class="card-layout">
                            <div class="card-text">

                                <h2>Job Description</h2>
                                <p><?= nl2br($description) ?></p>

                                <h2>Key Responsibilities</h2>
                                <ol>
                                    <?php foreach ($responsibilities as $task): ?>
                                        <li><?= htmlspecialchars(trim($task)) ?></li>
                                    <?php endforeach; ?>
                                </ol>

                                <h2>Qualifications</h2>
                                <ul>
                                    <?php foreach ($essential as $qual): ?>
                                        <li><strong>Essential:</strong> <?= htmlspecialchars(trim($qual)) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <ul>
                                    <?php foreach ($ideal as $qual): ?>
                                        <li><strong>Ideal:</strong> <?= htmlspecialchars(trim($qual)) ?></li>
                                    <?php endforeach; ?>
                                </ul>

                            </div>

                            <div class="card-image">
                                <img src="<?= $image ?>" alt="Job image for <?= $title ?>" />
                                <aside class="aside-display job-details">
                                    <h2>Other Information</h2>
                                    <ul>
                                        <li>Expected Salary: <?= $salary ?></li>
                                        <li>Ref ID: <?= $ref_id ?></li>
                                        <li>This position reports to the <?= $reports_to ?></li>
                                    </ul>
                                    <a href="apply.php"><button class="applystyle">Apply</button></a>
                                </aside>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $counter++;
            }
        } else {
            echo "<p>No job listings found.</p>";
        }

        $conn->close();
        ?>

        </section>

        <br /><br /><br />
    </div>

    <?php include 'footer.inc'; ?>

</body>
</html>
