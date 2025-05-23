<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Job Application Page">
        <meta name="keywords" content="Application form, job application">
        <meta name="author" content="Ho Le Nhu Ngoc">
        <title>Job Application  | CTRL-ALT-INNOVATE</title>

        <!-- Link css sheet -->
        <link rel="stylesheet" href="styles/styles.css">
    </head>

    <body>

          <!-- Nav bar -->
        <?php include 'navbar.inc' ?>


        <div class="content">
            <!-- decclare form -->
            <form method="post" action="process_eoi.php">

                <!-- Form header section -->
                <div class="Header">
                    <h1>Job Application Form</h1>
                    <p>Please fill in your details and a member from CTRL-ALT-INNOVATE will be in contact with you shortly.</p>
                    <br>
                </div>

                <!-- Main form content -->
                <div class="body">
                    <div class="form-section">
                        
                     <!-- Note about the required parts -->
                        <hr>
                        <br>
                        <h2 id="note"> Please fill out this form in order to apply for the job. Thanks!</h2>
                        <p id="required"> * Indicates required question </p>
                        <br>
                        <hr>

                        <!-- Job reference selection -->







                            <fieldset>
                                <legend>
                                <label for="JobReference">
                                        <span class="gray-heading">Job Reference Number</span>
                                    </label> 
                                    <input list="JobReferenceList" name="JobReference" id="JobReference" minlength="5" maxlength="5" pattern="[A-Za-z0-9]{5}"  placeholder="Enter 5-digit reference number">
                                    <datalist name="JobReference" id="JobReferenceList" required="required">
                                        <option value="">Please select</option>			
                                        <option value="ITA27">ITA27</option>
                                        <option value="SYS42">SYS42</option>
                                    </datalist>
                                </legend>
                            </fieldset>
                            



                        <hr>
                        <br>

                        <!-- Applicant details section (names, dob, gender, address, states, postcod, phone and email) -->
                        <fieldset>
                            <legend class="purple-heading"> Applicant Details</legend>
                            
                            <!-- First and Last name -->
                            <label for="FirstName"> 
                                <span class="gray-heading">First Name</span>
                                <input type="text" name="FirstName" id="FirstName" maxlength="20" pattern="[A-Za-z ]+" placeholder="Enter your first name" required="required">
                            </label>

                            <br>

                            <label for="LastName">
                                <span class="gray-heading">Last Name</span>
                                <input type="text" name="LastName" id="LastName" maxlength="20" pattern="[A-Za-z ]+" placeholder="Enter your last name" required="required">
                            </label> 

                            <br>

                            <!-- Date of birth -->
                            <p>
                                <label for="dob">
                                    <span class="gray-heading">Date of Birth</span>
                                <input type="date" id="dob" name="dob" required="required">
                                </label> 
                            </p>

                            <br>

                            <!-- user select their gender -->
                            <fieldset>
                                <legend>
                                    <span class="gray-heading">Gender</span>
                                </legend>
                                <label for="female"> <input type="radio" name="Gender" id="female" value="female" required="required"> Female </label>
                                <label for="male"> <input type="radio" name="Gender" id="male" value="male"> Male </label>
                                <label for="other"> <input type="radio" name="Gender" id="other" value="other"> Other </label>
                            </fieldset>

                            <!-- Address parts (street and suburb) -->
                            <p>
                                <label for="StreetAddress">
                                    <span class="gray-heading">Street Address</span> 
                                <input type="text" name="StreetAddress" id="StreetAddress" maxlength="40" pattern="[A-Z a-z 0-9 ]+" placeholder="e.g., 123 Elizabeth Street St" required="required">
                                </label>
                            </p>

                            <p>
                                <label for="SuburbTown">
                                    <span class="gray-heading">Suburb/town</span>
                                <input type="text" name="SuburbTown" id="SuburbTown" maxlength="40" pattern="[A-Za-z0-9 ]+" placeholder="e.g., Richmond" required="required">
                                </label> 
                            </p>

                            <!-- State dropdown -->
                            <p>
                                <label for="State">
                                    <span class="gray-heading">State</span>
                                    <select name="State" id="State" required="required">
                                        <option value="">Please select</option>
                                        <option value="ACT">ACT</option>
                                        <option value="NSW">NSW</option>
                                        <option value="NT">NT</option>
                                        <option value="QLD">QLD</option>
                                        <option value="SA">SA</option>
                                        <option value="TAS">TAS</option>
                                        <option value="VIC">VIC</option>
                                        <option value="WA">WA</option>
                                        Ascending order for dropdowns </select>
                                </label> 
                            </p>

                            <!-- Postcode -->
                            <p>
                                <label for="PostCode">
                                    <span class="gray-heading">Postcode</span>
                                <input type="text" name="PostCode" id="PostCode" pattern="\d{4}" placeholder="e.g., 3750" required="required">
                                </label>
                            </p>

                            <!-- Email detail -->
                            <p>
                                <label for="EmailAddress">
                                    <span class="gray-heading">Email address</span>
                                <input type="email" name="EmailAddress" id="EmailAddress" placeholder="e.g., 123456@student.swin.au" required="required">
                                </label>
                            </p>
                            
                            <!-- Phone detail -->
                            <p>
                                <label for="PhoneNumber">
                                    <span class="gray-heading">Phone Number</span>
                                <input type="text" name="PhoneNumber" id="PhoneNumber" maxlength="12" minlength="8" pattern="[0-9]+" placeholder="e.g., 0412345678" required="required">
                                </label>
                            </p>
                        </fieldset>


                        <br>
                        <hr>
                        <br>

                        <!-- Technical skills checkbox (with the default value: one box already being checked) -->
                        <fieldset>
                            <legend class="purple-heading">Required technical list</legend>
                            <p>
                                <div id="checkbox">
                                    <!-- Each skill with checkbox and description -->

                                    <!-- GenAI is used to summarise the skills descriptions, 
                                     which were taken from the jobs page and then summarised using ChatGPT.
                                     Prompt: paste the contents from the jobs page and ask Chatgpt "summarize the skills" 
                                     used on 6/04/2025 
                                     Credit: ChatGPT (OpenAI)-->   

                        <label for="technicalSupport">
                        <input type="checkbox" name="skills[]" id="technicalSupport" value="Technical Support" checked>
                            <strong>Technical Support</strong> - Troubleshoots hardware/software issues (Windows & macOS)
                        </label>

                        <label for="systemAdministration">
                            <input type="checkbox" name="skills[]" id="systemAdministration" value="System Administration">
                            <strong>System Administration</strong> - Proficient with Windows Server, Group Policy, DNS, DHCP
                        </label>

                        <label for="problemSolvingCommunication">
                            <input type="checkbox" name="skills[]" id="problemSolvingCommunication" value="Problem-Solving & Communication">
                            <strong>Problem-Solving & Communication</strong> - Strong troubleshooting, documentation, and user support skills
                        </label>
                    </div>
                </fieldset>

                        <hr>
                        <br>

                        <!-- Other skills textarea -->
                        <p>
                            <label for="comments">
                                <span class="purple-heading">Other skills</span>
                                <br>
                                <textarea name="comments" id="comments" rows="2" cols="10" placeholder="Write description of your other skills here ..."></textarea>
                            </label>
                        </p>
                        <hr>

                        <!-- Apply and reset form buttons -->
                        <div class="button-container"> 
    	                    <input type= "submit" value="Apply">
    	                    <input type= "reset" value="Reset">
                        </div>

                        <br><br>
                        <hr>

                    </div>
                </div>
            </form>
        </div>
        <?php include 'footer.inc' ?>
    </body>
</html>