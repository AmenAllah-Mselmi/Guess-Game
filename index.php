<?php
session_start();
if (!isset($_SESSION['target'])) {
    $_SESSION['target'] = rand(1, 100); // Random target number
    $_SESSION['players'] = ['Player 1' => 0, 'Player 2' => 0]; // Player scores
    $_SESSION['attempts'] = ['Player 1' => 0, 'Player 2' => 0]; // Attempt counters
    $_SESSION['currentPlayer'] = 'Player 1'; // Current player's turn
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Two-Player Guessing Game</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="game-container">
        <h1>Two-Player Guessing Game</h1>
        <div class="game-info">
            <p id="current-player">Current Player: <?php echo $_SESSION['currentPlayer']; ?></p>
            <p id="scores">Scores: Player 1 - <?php echo $_SESSION['players']['Player 1']; ?> | Player 2 - <?php echo $_SESSION['players']['Player 2']; ?></p>
            <p id="attempts">Attempts Left: <?php echo 10 - $_SESSION['attempts'][$_SESSION['currentPlayer']]; ?></p>
        </div>
        <p>Guess a number between 1 and 100.</p>
        <input type="number" id="guess" placeholder="Enter your guess" min="1" max="100">
        <button onclick="submitGuess()">Submit</button>
        <p id="message"></p>
        <button onclick="restartGame()" class="restart-btn">Restart Game</button>
    </div>
    <script src="script.js"></script>
</body>
</html>
