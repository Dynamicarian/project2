<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Job openings at CTRL-ALT-INNOVATE">
    <meta name="keywords" content="team, MoreBugs, students, coding, technology, web development, programming, project, team members, web design, university, job">
    <meta name="author" content="Ethan Kimel">
    <title>CTRL Jobs | CTRL-ALT-INNOVATE</title>
    <link rel="stylesheet" href="./styles/styles.css"> <!--css link-->
</head>
<body class="Jobs">
        <!-- All displayed text other than the company name, links and copyright is generated with Generative AI
     Model used: ChatGPT - GPT-4-Turbo
     Prompt 1: IT support Technician job description for job application page information
     Prompt 2: 3 essential, and 3 non essential requirements for the job
     Prompt 3: generate all the information you made for the first job role for Systems Admin
     -->

    <?php include 'navbar.inc'; ?> <!-- NAVBAR PHP -->
    
    <div class="content"> <!-- start of all content -->
        <div class="section-dark top-section">
            <h1 class="heading-styling">Available Job Positions</h1>
            <p class="content-row">CTRL-ALT-INNOVATE is looking to hire! We're looking to employ driven minds that want to join the team and grow with us.</p>
        </div>
        <br>

        <section class="team-cards">
        <?php       // PHP START
        include 'settings.php'; //to include database
        $sql = "SELECT * FROM jobs";
        $result = $conn->query($sql);
        $counter = 1;


        if ($result->num_rows > 0) { //sets row data
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

                echo <<<HTML
                <div class="accordion-card"> <!-- sets up accordian internals -->
                    <input type="checkbox" id="card$counter" class="toggle-card">
                    <label for="card$counter" class="card-title">
                        $title
                    </label>
                    <div class="card-content">
                        <div class="card-layout">
                            <div>
                                <h2>Job Description</h2> <br>
                                <p><pre>       $description</pre></p>
                                <br>
                                <div class="responsibilities-image-wrapper">
                                    <div class="responsibilities-text">
                                    <h2>Key Responsibilities</h2><br>
                                    <ol>
                HTML;
                    //  requirement and skills
                foreach ($responsibilities as $task) {
                    echo "<li><p>" . htmlspecialchars($task) . "</p></li>";
                }

echo <<<HTML
                                    </ol>
                                    </div>
                                    <div class="card-image">
                                        <img src="$image" alt="Job image for $title">
                                    </div>
                                </div>
                                <br><h2>Qualifications</h2><ul><br>
HTML;

                foreach ($essential as $qual) {
                    echo "<li><strong>Essential:</strong> " . htmlspecialchars($qual) . "</li>";
                }

                echo "</ul><br><ul>";

                foreach ($ideal as $qual) {
                    echo "<li><strong>Ideal:</strong> " . htmlspecialchars($qual) . "</li>";
                }

                

                echo <<<HTML
                <!-- HTML FOR ASIDE -->
                                </ul>
                                <br>
                                <aside class="aside-display job-details">
                                    <h2>Other Information</h2>
                                    <ul>
                                        <li>Expected Salary: $salary</li>
                                        <li>Ref ID: $ref_id</li>
                                        <li>This position reports to the $reports_to</li>
                                    </ul>
                                    <a href="apply.php"><button class="applystyle">Apply</button></a>
                                </aside>
                            </div>

                        </div>
                    </div>
                </div>
                HTML;

                $counter++; //cycle
            }
        } else {
            echo "<p>No job listings found.</p>"; //if database table is empty [TESTED, IS FUNCTIONAL]
        }
        $conn->close();
        ?> <!-- END OF PHP -->
        </section>

        <br><br><br>
    </div>

    <?php include 'footer.inc'; ?> <!-- FOOTER PHP -->
</body>
</html>
