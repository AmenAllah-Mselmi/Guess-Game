function submitGuess() {
    const guess = document.getElementById("guess").value;
    const message = document.getElementById("message");
    const currentPlayer = document.getElementById("current-player");
    const scores = document.getElementById("scores");
    const attempts = document.getElementById("attempts");

    if (!guess) {
        message.innerText = "Please enter a number!";
        return;
    }

    fetch("game.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ guess: parseInt(guess) }),
    })
        .then(response => response.json())
        .then(data => {
            message.innerText = data.message;
            if (data.currentPlayer) {
                currentPlayer.innerText = `Current Player: ${data.currentPlayer}`;
                scores.innerText = `Scores: Player 1 - ${data.scores['Player 1']} | Player 2 - ${data.scores['Player 2']}`;
                attempts.innerText = `Attempts Left: ${data.attemptsLeft}`;
            }
        })
        .catch(error => console.error("Error:", error));
}

function restartGame() {
    fetch("game.php?action=restart")
        .then(() => {
            window.location.reload();
        });
}
