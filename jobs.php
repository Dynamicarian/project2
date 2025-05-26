<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Job openings at CTRL-ALT-INNOVATE">
    <meta name="keywords" content="team, MoreBugs, students, coding, technology, web development, programming, project, team members, web design, university, job">
    <meta name="author" content="Ethan Kimel">
    <title>CTRL Jobs | CTRL-ALT-INNOVATE</title>
    <link rel="stylesheet" href="./styles/styles.css">
</head>


<!--
All displayed text other than the company name, links and copyright is generated with Generative AI
Model used: ChatGPT - GPT-4-Turbo
Prompt 1: IT support Technician job description for job application page information
Prompt 2: 3 essential, and 3 non essential requirements for the job
Prompt 3: generate all the information you made for the first job role for Systems Admin
-->


<body class="Jobs">

    <?php include 'header.inc'; ?> <!--navbar inclusion-->

    <div class="content">
        <div class="section-dark top-section">
            <h1 class="heading-styling">Available Job Positions</h1>
            <p class="content-row">
                CTRL-ALT-INNOVATE is looking to hire! We're looking to employ driven minds that want to join the team and grow with us.
            </p>
        </div><!--title for page-->
        <br>
        <section class="team-cards">

        <?php include 'settings.php'; // DB connection

        $sql = "SELECT * FROM jobs"; // access jobs
        $result = $conn->query($sql);
        $counter = 1; //looping / index counter

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
                ?> <!-- access table data-->

                <div class="accordion-card">
                    <input type="checkbox" id="card<?= $counter ?>" class="toggle-card">
                    <label for="card<?= $counter ?>" class="card-title"><?= $title ?></label>
                    <div class="card-content">
                        <div class="card-layout">
                            <div class="card-text"> <!--sets up accordian with aside and text formatting with image-->

                                <h2>Job Description</h2>
                                <p><?= nl2br($description) ?></p> <br>

                                <h2>Key Responsibilities</h2>
                                <ol> <!--key responsibilities in an ordered list -->
                                    <?php foreach ($responsibilities as $task): ?>
                                        <li><?= htmlspecialchars(trim($task)) ?></li>
                                    <?php endforeach; ?>
                                </ol>
                                    <br>
                                <h2>Qualifications</h2>
                                <ul> <!-- essential quals-->
                                    <?php foreach ($essential as $qual): ?>
                                        <li><strong>Essential:</strong> <?= htmlspecialchars(trim($qual)) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <ul> <!--ideal quals-->
                                    <?php foreach ($ideal as $qual): ?>
                                        <li><strong>Ideal:</strong> <?= htmlspecialchars(trim($qual)) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <br> <!--formatting breaks-->
                                <br>
                                <button class="applystyle" onclick="window.location.href='apply.php?ref=<?= urlencode($ref_id) ?>'">Apply</button> <!--button that links to apply page-->

                            </div>
                            
                            <div class="card-image">
                                <img src="<?= $image ?>" alt="Job image for <?= $title ?>"> <!--image above aside -->
                                <aside class="aside-display job-details"> <!--aside element -->
                                    <h2>Other Information</h2>
                                    <ul>
                                        <li>Expected Salary: <?= $salary ?></li>
                                        <li>Ref ID: <?= $ref_id ?></li>
                                        <li>This position reports to the <?= $reports_to ?></li>
                                    </ul>
                                </aside>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $counter++; //increment index for loop
            }
        } else {
            echo "<p>No job listings found.</p>"; //displays if table is empty
        }

        $conn->close();
        ?>

        </section>

        <br>
        <br>
        <br>
    </div>

    <?php include 'footer.inc'; ?> <!-- footer -->

</body>
</html>
