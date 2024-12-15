<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'restart') {
    // Reset the game
    session_unset();
    session_destroy();
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$guess = $data['guess'] ?? null;

if ($guess === null) {
    echo json_encode(['message' => 'Invalid input.']);
    exit;
}

$currentPlayer = $_SESSION['currentPlayer'];
$target = $_SESSION['target'];
$message = '';

// Check if player has remaining attempts
if ($_SESSION['attempts'][$currentPlayer] >= 10) {
    $_SESSION['currentPlayer'] = $currentPlayer === 'Player 1' ? 'Player 2' : 'Player 1';

    // If both players are out of attempts
    if ($_SESSION['attempts']['Player 1'] >= 10 && $_SESSION['attempts']['Player 2'] >= 10) {
        $winner = null;

        // Determine the winner
        if ($_SESSION['players']['Player 1'] > $_SESSION['players']['Player 2']) {
            $winner = 'Player 1';
        } elseif ($_SESSION['players']['Player 2'] > $_SESSION['players']['Player 1']) {
            $winner = 'Player 2';
        }

        if ($winner) {
            $message = "{$winner} wins with the highest score!";
        } else {
            $message = "It's a tie! Both players scored the same.";
        }

        // Reset the game
        $message .= " The number was {$target}. Restarting game...";
        session_unset();
        session_destroy();

        echo json_encode(['message' => $message]);
        exit;
    }

    $message = "{$currentPlayer} is out of attempts! Switching turns.";
    echo json_encode([
        'message' => $message,
        'currentPlayer' => $_SESSION['currentPlayer'],
        'scores' => $_SESSION['players'],
        'attemptsLeft' => 10 - $_SESSION['attempts'][$_SESSION['currentPlayer']],
    ]);
    exit;
}

// Increment attempts for the current player
$_SESSION['attempts'][$currentPlayer]++;
if ($guess < $target) {
    $message = 'Too low!';
} elseif ($guess > $target) {
    $message = 'Too high!';
} else {
    $message = "{$currentPlayer} guessed correctly! The number was {$target}.";
    $_SESSION['players'][$currentPlayer] += 10 - $_SESSION['attempts'][$currentPlayer]; // Score based on remaining attempts
    $_SESSION['target'] = rand(1, 100); // New target number
    $_SESSION['attempts']['Player 1'] = 0;
    $_SESSION['attempts']['Player 2'] = 0;
}

// Switch to the next player
$_SESSION['currentPlayer'] = $currentPlayer === 'Player 1' ? 'Player 2' : 'Player 1';

echo json_encode([
    'message' => $message,
    'currentPlayer' => $_SESSION['currentPlayer'],
    'scores' => $_SESSION['players'],
    'attemptsLeft' => 10 - $_SESSION['attempts'][$_SESSION['currentPlayer']],
]);
?>
