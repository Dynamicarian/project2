<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Home page of CTRL-ALT-INNOVATE">
    <meta name="keywords" content="team, MoreBugs, students, coding, technology, web development, programming, project, team members, web design, university">
    <meta name="author" content="Tristan Dinning">
    <link rel="stylesheet" href="./styles/styles.css">
    <title>Home | CTRL-ALT-INNOVATE</title>
</head>
<body>
    <!--Navigation bar-->
    <?php include 'navbar.inc' ?>
    <!-- Main content is in seperate div so that grid layout can keep the footer at the bottom of the page even when there is limited content-->
    <!-- All displayed text other than the company name, links and copyright is generated with Generative AI
     Model used: DeepSeek
     Prompt 1: Write a tagline, short description of and action line for a the main webpage of a tech companie's website. The company name it CTRL-ALT-INNOVATE.
     Prompt 2: I want a bit of text to be put in the top lef tof the footer. What should be written there?
     Prompt 3: Come up with three hypothetical services this company would provide
     -->
    <div class="content">
        <!-- title and description section. "top-section" removes top boarder curves-->
        <div class="section-dark top-section">
            <br>
            <h1>CTRL-ALT-INNOVATE</h1>
            <h2>Rewriting the Future of Technology</h2>
            <p class="content-row">CTRL-ALT-INNOVATE is a cutting-edge tech company dedicated to disruptive innovation. We engineer bold solutions, redefine industry standards, and empower businesses through next-gen software, AI, and digital transformation.
                <br><br>Press start on progress.</p> <!--Press Start on progress will allways be on new line-->
        </div>
        <!--Our Services Section-->
        <div class="section-dark">
            <h2>Our Services</h2>
            <!-- Lays out contained content into a row. It switches to a column when the veiw width is too small-->
            <div class="content-row">
                <div>
                    <h3>AI-Powered Digital Transformation</h3>
                    <!--image from https://www.vecteezy.com/png/55810405-ai-technology-brain-icon-illustration-isolated-->
                    <img class="service-icon" src="./images/vecteezy_ai-technology-brain-icon-illustration-isolated_55810405.png" alt="Digital Brain Icon">
                    <p>We design custom AI ecosystems. From predictive analytics to autonomous workflows that evolve with your needs.</p>
                </div>
                <div>
                    <h3>Quantum-Ready Cybersecurity</h3>
                    <!--image from https://www.vecteezy.com/png/12675199-lock-icon-clipart-->
                    <img class="service-icon" src="./images/vecteezy_lock-icon-clipart_12675199.png" alt="Lock Icon">
                    <p>Next-gen encryption and post-quantum security frameworks to protect data in an era of exponential risks.</p>
                </div>
                <div>
                    <h3>Edge Computing Orchestration</h3>
                    <!--image from https://www.vecteezy.com/png/58274027-ai-and-data-cluster-network-icon-->
                    <img class="service-icon" src="./images/vecteezy_ai-and-data-cluster-network-icon_58274027.png" alt="Network Icon">
                    <p>Decentralized, low-latency edge networks for industries where real-time decisions matter.</p>
                </div>
            </div>
        </div>
        <!--Trusted by leading brands section-->
        <div class="section-dark">
            <h2>Trusted By Leading Brands</h2>
            <div class="content-row">
                <!--Images from https://www.freepik.com/-->
                <div>
                    <img class="supporting-brand-logos" src="./images/brand-logo-A.png" alt="Brand image A">
                </div>
                <div>
                    <img class="supporting-brand-logos" src="./images/brand-logo-B.png" alt="Brand image B">
                </div>
                <div>
                    <img class="supporting-brand-logos" src="./images/brand-logo-C.png" alt="Brand image C">
                </div>
                <div>
                    <img class="supporting-brand-logos" src="./images/brand-logo-D.png" alt="Brand image D">
                </div>
                <div>
                    <img class="supporting-brand-logos" src="./images/brand-logo-E.png" alt="Brand image E">
                </div>
            </div>
        </div>
    </div>
    <!--End of content. Start of footer-->
    <?php include 'footer.inc' ?>
</body>
</html>