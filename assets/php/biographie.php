<?php
// Start the session
session_start(); 

// Check if the user is authenticated, redirect to login page if not
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header("Location: ../../index.php");
    // Stop further script execution
    exit(); 
}

// Include the biography data file
$bio = include ("../data/bio.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protected Bio</title>
    <link rel="stylesheet" href="../css/biographie.css">
    <script>
        // Function to open a specific tab and hide others
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                // Hide all tab contents
                tabcontent[i].style.display = "none"; 
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                // Remove active class from all tab links
                tablinks[i].className = tablinks[i].className.replace(" active", ""); 
            }
            // Show the selected tab content
            document.getElementById(tabName).style.display = "block"; 
            // Add active class to the clicked tab link
            evt.currentTarget.className += " active"; 
        }

        // Function to toggle the responsive menu
        function toggleMenu() {
            var x = document.getElementById("tabMenu");
            if (x.className === "tab") {
                // Make menu responsive
                x.className += " responsive"; 
            } else {
                // Revert menu to normal
                x.className = "tab"; 
            }
        }
    </script>
</head>

<body>
    <header>
        <h1 id="header">PROJECT 2 | PROTECTED PAGE</h1>
    </header>
    <div class="container">
        <div class="bio-section">

            <!-- Navigation Tabs -->
            <div class="tab" id="tabMenu">
                <button class="tablinks active" onclick="openTab(event, 'Contact')">Contact</button>
                <button class="tablinks" onclick="openTab(event, 'Education')">Education</button>
                <button class="tablinks" onclick="openTab(event, 'Languages')">Languages</button>
                <button class="tablinks" onclick="openTab(event, 'Experience')">Experience</button>
                <button class="tablinks" onclick="openTab(event, 'Skills')">Skills</button>
                <a href="javascript:void(0);" class="icon" onclick="toggleMenu()">&#9776;</a>
            </div>

            <!-- Contact Tab -->
            <div id="Contact" class="tabcontent" style="display: block;">
                <h2>Contact</h2>
                <ul>
                    <li>
                        <div class="icon-circle">
                            <img src="../svg/<?= $bio['contact']['phone']['icon'] ?>" alt="Phone" class="icon">
                        </div>
                        <a href="tel:<?= $bio['contact']['phone']['number'] ?>"><?= $bio['contact']['phone']['number'] ?></a>
                    </li>
                    <li>
                        <div class="icon-circle">
                            <img src="../svg/<?= $bio['contact']['email']['icon'] ?>" alt="Email" class="icon">
                        </div>
                        <a href="mailto:<?= $bio['contact']['email']['address'] ?>"><?= $bio['contact']['email']['address'] ?></a>
                    </li>
                    <li>
                        <div class="icon-circle">
                            <img src="../svg/<?= $bio['contact']['address']['icon'] ?>" alt="Location" class="icon">
                        </div>
                        <a href="https://www.google.com/maps/place/<?= urlencode($bio['contact']['address']['location']) ?>" target="_blank"><?= $bio['contact']['address']['location'] ?></a>
                    </li>
                </ul>
            </div>

            <!-- Education Tab -->
            <div id="Education" class="tabcontent">
                <h2>Education</h2>
                <ul>
                    <?php foreach ($bio['education'] as $edu): ?>
                        <li>
                            <div class="icon-circle">📚</div>
                            <h3><?= $edu['institution'] ?> <?= $edu['status'] == 'completed' ? '✅' : '⛔' ?></h3>
                            <p><?= $edu['duration'] ?> | <?= $edu['location'] ?></p>
                            <p><?= $edu['degree'] ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Languages Tab -->
            <div id="Languages" class="tabcontent">
                <h2>Languages</h2>
                <ul>
                    <?php foreach ($bio['languages'] as $language): ?>
                        <li>
                            <div class="icon-circle">
                                <img src="../svg/<?= $language['icon'] ?>" alt="<?= $language['name'] ?>" class="icon">
                            </div>
                            <?= $language['name'] ?> <i>(<?= $language['proficiency'] ?>)</i>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Experience Tab -->
            <div id="Experience" class="tabcontent">
                <h2>Experience</h2>
                <ul>
                    <?php foreach ($bio['experience'] as $exp): ?>
                        <li>
                            <div class="icon-circle">⚙️</div>
                            <h3><?= $exp['company'] ?> - <strong><?= $exp['role'] ?></strong></h3>
                            <p><?= $exp['duration'] ?> | <?= $exp['location'] ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Skills Tab -->
            <div id="Skills" class="tabcontent">
                <h2>Skills</h2>
                <div class="skills-section">
                    <?php foreach ($bio['skills'] as $skill): ?>
                        <div class="skill-item">
                            <div class="icon-circle">
                                <img src="../svg/<?= $skill['icon'] ?>" alt="<?= $skill['name'] ?>" class="skill-icon">
                            </div>
                            <p><?= $skill['name'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <button id="button-disco">
            <h1>LOG OUT</h1>
        </button>
    </footer>

    <audio id="logout-sound" src="../sounds/logout.mp3"></audio>

    <script>
        // Function to handle logout button click
        document.getElementById('button-disco').addEventListener('click', function () {
            // Play logout sound
            document.getElementById('logout-sound').play(); 
            // Show deleted access message
            document.querySelector('.container').innerHTML = `<h1>🔒 DELETED ACCESS ...</h1>`; 
            // Update footer
            this.parentElement.innerHTML = '<h3 id="footer">PROJECT 2 | [By @Edwyn-Dev]</h3>'; 
            document.getElementById('logout-sound').addEventListener('ended', function () {
                setTimeout(() => {
                    // Redirect to logout page after sound ends
                    window.location.href = 'logout.php'; 
                }, 1000);
            });
        })
    </script>
</body>

</html>
