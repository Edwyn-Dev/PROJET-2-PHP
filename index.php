<?php
session_start(); // Start the session
// Include the riddle data file
include ("assets/data/devinette_data.php");

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    // Initialize the authenticated session variable to false
    $_SESSION['authenticated'] = false;
}

// If the user is authenticated, redirect to the biography page
if ($_SESSION['authenticated'] === true) {
    header("Location: assets/php/biographie.php");
    // Stop further script execution
    exit();
}

// Initialize attempts and used riddles if not already set
if (!isset($_SESSION['attempts'])) {
    // Set the number of attempts
    $_SESSION['attempts'] = 3;
    // Initialize used riddles array
    $_SESSION['used_riddles'] = [];
}

// Select a new riddle if no riddle is set or the current riddle is invalid
if (!isset($_SESSION['riddle_index']) || !isset($guess[$_SESSION['riddle_index']])) {
    do {
        // Randomly select a riddle
        $_SESSION['riddle_index'] = array_rand($guess);
        // Ensure it's not a used riddle
    } while (in_array($_SESSION['riddle_index'], $_SESSION['used_riddles']));
    // Add the riddle to used riddles
    $_SESSION['used_riddles'][] = $_SESSION['riddle_index'];
    if (count($_SESSION['used_riddles']) == count($guess)) {
        // Reset used riddles if all have been used
        $_SESSION['used_riddles'] = [];
    }
}

// Get the current riddle
$riddle = $guess[$_SESSION['riddle_index']];
// Initialize sound variable
$playSound = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the guessed password from the form
    $input_password = $_POST['guessing'];
    if ($_SESSION['attempts'] > 1) {
        if ($input_password == $riddle['password']) {
            $_SESSION['guessed_password'] = true;
            // Authenticate the user
            $_SESSION['authenticated'] = true;
            // Set sound to success
            $playSound = 'success';
        } else {
            // Decrement attempts
            $_SESSION['attempts'] -= 1;
            // Set sound to error
            $playSound = 'error';
            // Set error message
            $error = "Incorrect password. Try again.";
        }
    } else {
        // Reset attempts and select a new riddle if no attempts left
        $_SESSION['attempts'] = 3;
        do {
            // Randomly select a new riddle
            $_SESSION['riddle_index'] = array_rand($guess);
            // Ensure it's not a used riddle
        } while (in_array($_SESSION['riddle_index'], $_SESSION['used_riddles']));
        // Add the riddle to used riddles
        $_SESSION['used_riddles'][] = $_SESSION['riddle_index'];
        if (count($_SESSION['used_riddles']) == count($guess)) {
            // Reset used riddles if all have been used
            $_SESSION['used_riddles'] = [];
        }
        // Get the new riddle
        $riddle = $guess[$_SESSION['riddle_index']];
        // Set sound to error
        $playSound = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bio Protected</title>
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
    <header>
        <h1 id="header">PROJECT 2 | PROTECTED PAGE</h1>
    </header>
    <div class="container">
        <h1 id="container-title-h1">WELCOME TO MY SITE</h1>
        <p>
            Your goal is to find the password using a riddle to access my Biography ^^<br>
            <strong>But beware!</strong><br>
            You only have <strong>3 attempts</strong>. If none of your attempts succeed, a new riddle will appear, and
            the password will change :)
            <hr>
        </p>
        <h3 id="container-title-h3">
            <div id="divGuess"><?php echo htmlspecialchars($riddle['guessing'], ENT_QUOTES, 'UTF-8'); ?></div>
            <form method="post" id="password-form">
                <input type="text" name="guessing" id="guessing"
                    placeholder="<?= $_SESSION['attempts'] ?> attempts remaining"><br>
                <?php if (isset($error)): ?>
                    <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
                <br>
                <input id="submit" type="submit" value="SUBMIT MY ANSWER" name="submit">
            </form>
        </h3>
    </div>
    <footer>
        <h3 id="footer">PROJECT 2 | [By @Edwyn-Dev]</h3>
    </footer>

    <audio id="error-sound" src="assets/sounds/error.mp3"></audio>
    <audio id="success-sound" src="assets/sounds/success.mp3"></audio>

    <script>
        document.getElementById('submit').addEventListener('click', function (event) {
            document.getElementById('button-sound').play();
        });

        <?php if ($playSound === 'error'): ?>
            document.getElementById('error-sound').play();
        <?php elseif ($playSound === 'success'): ?>
            document.querySelector('.container').innerHTML = `<h1>🔓 LOADING ACCESS ...</h1>`
            document.getElementById('success-sound').play();
            document.getElementById('success-sound').addEventListener('ended', function () {
                setTimeout(() => {
                    window.location.href = 'assets/php/biographie.php';
                }, 1000);
            });
        <?php endif; ?>
    </script>
</body>

</html>