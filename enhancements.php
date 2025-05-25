<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="Website enhancements and additional features implemented by CTRL-ALT-INNOVATE"/>
    <meta name="keywords" content="enhancements, features, improvements, database, security, sorting, authentication, website development"/>
    <meta name="author" content="Christina Lian Fernandez"/>
    <title>Enhancements | CTRL-ALT-INNOVATE</title>
    <link rel="stylesheet" href="./styles/styles.css"/>
</head>

<body class="enhancements-open">

    <?php include 'header.inc'; ?> <!--navbar inclusion-->

    <div class="content">
        <div class="section-dark top-section">
            <h1 class="heading-styling">Website Enhancements</h1>
            <p class="content-row">
                CTRL-ALT-INNOVATE has implemented several advanced features to improve functionality, security, and user experience. Explore the enhancements we've added to our website below.
            </p>
        </div><!--title for page-->
        <br>
        
        <section class="team-cards">

            <!-- Enhancement 1: Dynamic Sorting -->
            <div class="accordion-card">
                <input type="checkbox" id="card1" class="toggle-card" />
                <label for="card1" class="card-title">Dynamic EOI Sorting System</label>
                <div class="card-content">
                    <div class="card-layout">
                        <div class="card-text">
                            <h2>Feature Description</h2>
                            <p>We implemented a comprehensive sorting system that allows managers to dynamically sort EOI records based on multiple criteria.</p>

                            <h2>Key Features</h2>
                            <ul>
                                <li>Dropdown selection for sorting fields (Name, Email, Phone, Job Reference, Status, Date Submitted)</li>
                                <li>Ascending and descending order options</li>
                                <li>User-friendly interface integrated into the management dashboard</li>
                            </ul>
                        </div>
                        
                        <div class="card-image">
                            <img src="images/sorting-function.png" alt="Dynamic Sorting System Interface" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhancement 2: Manager Registration System -->
            <div class="accordion-card">
                <input type="checkbox" id="card2" class="toggle-card" />
                <label for="card2" class="card-title">Secure Manager Registration System</label>
                <div class="card-content">
                    <div class="card-layout">
                        <div class="card-text">
                            <h2>Feature Description</h2>
                            <p>A registration system specifically designed for managers, featuring comprehensive server-side validation and secure credential storage. This system ensures only authorized personnel can access management functions.</p>

                            <h2>Key Features</h2>
                            <ul>
                                <li>Unique username validation with real-time availability checking</li>
                                <li>Advanced password requirements (minimum 8 characters, mixed case, numbers, special characters)</li>
                                <li>Secure password hashing using PHP's password_hash() function</li>
                                <li>Registration confirmation system</li>
                            </ul>
                            <br>
                        </div>
                        
                        <div class="card-image">
                            <img src="images/manager-registration.png" alt="Manager Registration Interface" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhancement 3: Access Control System -->
            <div class="accordion-card">
                <input type="checkbox" id="card3" class="toggle-card" />
                <label for="card3" class="card-title">Advanced Authentication & Access Control</label>
                <div class="card-content">
                    <div class="card-layout">
                        <div class="card-text">
                            <h2>Feature Description</h2>
                            <p>A comprehensive authentication system that protects the management portal through secure login verification.</p>

                            <h2>Key Features</h2>
                            <ul>
                                <li>Secure login form with encrypted credential transmission</li>
                            </ul>
                        </div>
                        
                        <div class="card-image">
                            <img src="images/manager-login.png" alt="Login and Access Control System" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhancement 4: Account Lockout System -->
            <div class="accordion-card">
                <input type="checkbox" id="card4" class="toggle-card" />
                <label for="card4" class="card-title">Intelligent Account Lockout Protection</label>
                <div class="card-content">
                    <div class="card-layout">
                        <div class="card-text">
                            <h2>Feature Description</h2>
                            <p>A security feature that automatically disables accounts after multiple failed login attempts, protecting against brute force attacks.</p>

                            <h2>Key Features</h2>
                            <ul>
                                <li>Progressive lockout system (3 failed attempts = 10 min lockout)</li>
                            </ul>
                        </div>
                        
                        <div class="card-image">
                            <img src="images/account-lockout.png" alt="Account Lockout Protection System" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhancement 5: Normalized Database -->
            <div class="accordion-card">
                <input type="checkbox" id="card5" class="toggle-card" />
                <label for="card5" class="card-title">Optimized Database Architecture</label>
                <div class="card-content">
                    <div class="card-layout">
                        <div class="card-text">
                            <h2>Feature Description</h2>
                            <p>A completely redesigned database structure following Third Normal Form (3NF) principles, eliminating data redundancy and improving performance, scalability, and data integrity across the entire system.</p>

                            <h2>Key Improvements</h2>
                            <ul>
                                <li>Separated user data into dedicated tables (applicants, jobs, skills)</li>
                                <li>Normalized job and applicant data with proper foreign key relationships</li>
                                <li>Created lookup tables for states, job categories, and skill sets</li>
                                <li>Implemented proper indexing strategy for optimal query performance</li>
                                <li>Added referential integrity constraints to prevent data corruption</li>
                            </ul>
                        </div>
                        
                        <div class="card-image">
                            <img src="images/normalised-database.png" alt="Normalized Database Schema" />
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <br><br>
    </div>

    <?php include 'footer.inc'; ?> <!-- footer -->

</body>
</html>