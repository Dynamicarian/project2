<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Confirmation page of CTRL-ALT-INNOVATE">
    <meta name="keywords" content="team, MoreBugs, students, coding, technology, web development, programming, project, team members, web design, university">
    <meta name="author" content="Tristan Dinning">
    <link rel="stylesheet" href="./styles/styles.css">
    <title>Confirmed | CTRL-ALT-INNOVATE</title>
</head>
<body>
    <!--Navigation bar-->
    <?php include 'header.inc' ?>
    <div class="content">
        <div class="section-dark big-panel">
            <h1>THANK YOU FOR YOUR APPLICATION!</h1>
            <br><br><br>
            <?php
                session_start();
                if (array_key_exists('eoiNumber', $_SESSION))
                {
                    echo '<h2>Here is your expression of interest (EOI) number:</h2><br>';
                    echo '</p>' . $_SESSION['eoiNumber'] . '</p>';
                }
            ?>
        </div>
    </div>
    <!--End of content. Start of footer-->
    <?php include 'footer.inc' ?>
</body>
</html>