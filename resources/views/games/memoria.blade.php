@extends('base')
@section('title', 'Jogo da Memória')
@section('content')

<style>
    main {
        display: flex;
        flex-direction: column;
        /* Removido min-width para melhor adaptação em telas pequenas */
    }

    /* Cabeçalho e Footer */
    .section-logo {
        text-align: center;
        margin: 20px 0 40px;
        animation: fadeIn 1s ease-in-out;
    }

    #logo,
    #footer-logo {
        height: 120px;
        filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.5));
        transition: transform 0.3s;
    }

    #footer-logo {
        height: 80px;
    }

    #logo:hover,
    #footer-logo:hover {
        transform: scale(1.05);
    }

    /* Timer */
    #timer-container {
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 15px 50px;
        width: 20em;
        max-height: 80px;
        margin: 0 auto 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        animation: slideDown 0.8s ease-out;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 1.2em;
    }

    #timer {
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #fff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    /* Jogo - Totalmente responsivo com CSS Grid */
    .memory-game {
        width: 100%;
        max-width: 900px;
        margin: 50px auto;
        display: grid;
        grid-template-columns: repeat(6, minmax(10px, 1fr));
        gap: 10px;
        perspective: 1000px;
        padding: 10px;
    }

    @media (max-width: 800px) {
        .memory-game {
            max-width: 600px;
        }
    }

    @media (orientation: portrait) {
        @media (max-width: 700px) {
            .memory-game {
                grid-template-columns: repeat(3, minmax(10px, 1fr));
                grid-template-rows: repeat(6, 1fr);
                gap: 1.4rem;
            }

            .memory-card {
                width: 7rem;
                height: auto;
                aspect-ratio: 1/1;
            }

            #times {
                font-size: .8rem;
            }

            #timer {
                font-size: 1rem;
            }

            #logo {
                height: 4rem;
            }
        }
    }

    .memory-card {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.5s, box-shadow 0.3s;
        cursor: pointer;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        aspect-ratio: 1 / 1;
        border: 0.5rem solid #fff;
    }

    .memory-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
    }

    .memory-card.flip {
        transform: rotateY(180deg);
    }

    .front-face,
    .back-face {
        width: 100%;
        height: 100%;
        position: absolute;
        backface-visibility: hidden;
        object-fit: contain;
        transition: all 0.3s;
        border-radius: 10px;
        padding: 5px;
    }

    .back-face {
        transform: rotateY(0deg);
        background-color: lightblue;
        border-radius: 5%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .front-face {
        transform: rotateY(180deg);
    }

    /* Modal e Tabela de Pontuação */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(5px);
        z-index: 1000;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.5s ease-out;
    }

    .modal-content {
        background: linear-gradient(145deg, #2b5876, #4e4376);
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 450px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 28px;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        transition: color 0.3s;
    }

    .close:hover {
        color: #fff;
    }

    .modal h2 {
        margin-bottom: 20px;
        color: #fff;
        font-size: 28px;
    }

    #final-time {
        display: inline-block;
        background: rgba(0, 0, 0, 0.3);
        padding: 10px 20px;
        border-radius: 50px;
        margin: 15px 0;
        font-size: 22px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    #player-name {
        width: 100%;
        padding: 12px 15px;
        margin: 20px 0;
        border: none;
        border-radius: 50px;
        background: rgba(255, 255, 255, 0.9);
        font-size: 16px;
        outline: none;
        transition: all 0.3s;
    }

    #player-name:focus {
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }

    #save-score {
        background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        font-size: 16px;
        border-radius: 50px;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        font-weight: 600;
        letter-spacing: 1px;
    }

    #save-score:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.3);
    }

    /* Animações (não foram alteradas) */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Estilos para a tabela de pontuação */
    #score-table {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 400px;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    #score-table table {
        width: 100%;
        border-collapse: collapse;
    }

    #score-table th {
        background-color: #2c3e50;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9em;
        letter-spacing: 1px;
    }

    #score-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        color: #333;
    }

    #score-table tr:last-child td {
        border-bottom: none;
    }

    #score-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #score-table tr {
        background-color: #f1f1f1;
    }

    #score-table td:last-child {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #e74c3c;
    }

    #times {
        display: flex;
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
        /* Permite que o timer e a tabela quebrem a linha */
        justify-content: center;
        /* Centraliza os itens em telas menores */
        gap: 20px;
    }
</style>
<main>

    <div id="times">
        <div id="timer-container">Tempo: <span id="timer">00:00:000</span></div>
        @if($scores->isNotEmpty())
        <div id="score-table">
            <table>
                <tr>
                    <th>Nº</th>
                    <th>Nome</th>
                    <th>Tempo</th>
                </tr>
                @foreach($scores as $index => $score)
                <tr>
                    <td>{{ $index + 1 }}º</td>
                    <td>{{ $score->name }}</td>
                    <td>{{ $score->time }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

    </div>
    <section class="memory-game">
        <!-- Cartas do jogo -->
        <div class="memory-card" data-framework="apconsig">
            <img class="front-face" src="{{ asset('images/jogos/memoria/apconsig.png') }}" alt="APCONSIG" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="apconsig">
            <img class="front-face" src="{{ asset('images/jogos/memoria/apconsig.png') }}" alt="APCONSIG" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="seloamapa">
            <img class="front-face" src="{{ asset('images/jogos/memoria/seloamapa.jpeg') }}" alt="Selo AMAPA" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="seloamapa">
            <img class="front-face" src="{{ asset('images/jogos/memoria/seloamapa.jpeg') }}" alt="Selo AMAPA" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="tea">
            <img class="front-face" src="{{ asset('images/jogos/memoria/tea.jpg') }}" alt="TEA" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="tea">
            <img class="front-face" src="{{ asset('images/jogos/memoria/tea.jpg') }}" alt="TEA" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="apdigital">
            <img class="front-face" src="{{ asset('images/jogos/memoria/apdigital.jpg') }}" alt="AP Digital" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="apdigital">
            <img class="front-face" src="{{ asset('images/jogos/memoria/apdigital.jpg') }}" alt="AP Digital" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="sigdocs_logo">
            <img class="front-face" src="{{ asset('images/jogos/memoria/sigdocs.jpg') }}" alt="SIGDOCS" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="sigdocs_logo">
            <img class="front-face" src="{{ asset('images/jogos/memoria/sigdocs.jpg') }}" alt="SIGDOCS" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="prodap_logo">
            <img class="front-face" src="{{ asset('images/jogos/memoria/PRODAP_LOGO.png') }}" alt="PRODAP" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="prodap_logo">
            <img class="front-face" src="{{ asset('images/jogos/memoria/PRODAP_LOGO.png') }}" alt="PRODAP" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="wifigov">
            <img class="front-face" src="{{ asset('images/jogos/memoria/wifi-gov.png') }}" alt="wifigov" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="wifigov">
            <img class="front-face" src="{{ asset('images/jogos/memoria/wifi-gov.png') }}" alt="wifigov" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="observatorio">
            <img class="front-face" src="{{ asset('images/jogos/memoria/observatorio.jpeg') }}" alt="observatorio" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="observatorio">
            <img class="front-face" src="{{ asset('images/jogos/memoria/observatorio.jpeg') }}" alt="observatorio" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>

        <div class="memory-card" data-framework="prodoc">
            <img class="front-face" src="{{ asset('images/jogos/memoria/prodoc.jpeg') }}" alt="prodoc" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
        <div class="memory-card" data-framework="prodoc">
            <img class="front-face" src="{{ asset('images/jogos/memoria/prodoc.jpeg') }}" alt="prodoc" />
            <img class="back-face" src="{{ asset('images/jogos/memoria/logo_gea.png') }}" alt="Verso Carta" />
        </div>
    </section>

    <!-- Modal de Vitória -->
    <div id="win-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Parabéns! Você venceu!</h2>
            <form id="save-score-form" action="{{ route('store.score') }}" method="POST">
                @csrf
                @method('POST')
                <p>Seu tempo: <span id="final-time"></span></p>
                <input name="time" type="hidden" id="final-time-input" />
                <input name="name" type="text" id="player-name" placeholder="Digite seu nome" />
                <button type="submit" id="save-score">Salvar Pontuação</button>
            </form>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    const cards = document.querySelectorAll(".memory-card");
    const timerElement = document.getElementById("timer");
    const winModal = document.getElementById("win-modal");
    const finalTimeElement = document.getElementById("final-time");
    const finalTimeInput = document.getElementById("final-time-input");
    const playerNameInput = document.getElementById("player-name");
    const saveScoreBtn = document.getElementById("save-score");
    const saveScoreForm = document.getElementById("save-score-form");
    const closeBtn = document.querySelector(".close");

    let hasFlippedCard = false;
    let lockBoard = false;
    let firstCard, secondCard;
    let timer;
    let startTime;
    let timerRunning = false;

    // Timer functions with milliseconds precision
    function startTimer() {
        if (!timerRunning) {
            timerRunning = true;
            startTime = Date.now();
            timer = requestAnimationFrame(updateTimer);
        }
    }

    function stopTimer() {
        cancelAnimationFrame(timer);
        timerRunning = false;
    }

    function resetTimer() {
        stopTimer();
        timerElement.textContent = "00:00:000";
    }

    function updateTimer() {
        const elapsedTime = Date.now() - startTime;
        const milliseconds = Math.floor(elapsedTime % 1000);
        const seconds = Math.floor((elapsedTime / 1000) % 60);
        const minutes = Math.floor((elapsedTime / (1000 * 60)) % 60);

        const formattedMilliseconds = String(milliseconds).padStart(3, "0");
        const formattedSeconds = String(seconds).padStart(2, "0");
        const formattedMinutes = String(minutes).padStart(2, "0");

        timerElement.textContent = `${formattedMinutes}:${formattedSeconds}:${formattedMilliseconds}`;
        timer = requestAnimationFrame(updateTimer);
    }

    function getCurrentTime() {
        return timerElement.textContent;
    }

    // Game functions
    function flipCard() {
        if (lockBoard) return;
        if (this === firstCard) return;

        // Start timer on first card flip
        if (!timerRunning && !hasFlippedCard) {
            startTimer();
        }

        this.classList.add("flip");

        if (!hasFlippedCard) {
            hasFlippedCard = true;
            firstCard = this;
            return;
        }

        secondCard = this;
        checkForMatch();
    }

    function checkForMatch() {
        let isMatch = firstCard.dataset.framework === secondCard.dataset.framework;
        isMatch ? disableCards() : unflipCards();

        if (isMatch) {
            checkForWin();
        }
    }

    function disableCards() {
        firstCard.removeEventListener("click", flipCard);
        secondCard.removeEventListener("click", flipCard);
        resetBoard();
    }

    function unflipCards() {
        lockBoard = true;
        setTimeout(() => {
            firstCard.classList.remove("flip");
            secondCard.classList.remove("flip");
            resetBoard();
        }, 500);
    }

    function resetBoard() {
        [hasFlippedCard, lockBoard] = [false, false];
        [firstCard, secondCard] = [null, null];
    }

    function checkForWin() {
        const flippedCards = document.querySelectorAll(".flip");
        if (flippedCards.length === cards.length) {
            stopTimer();
            setTimeout(() => {
                triggerConfetti();
                showWinModal();
            }, 500);
        }
    }

    function showWinModal() {
        finalTimeElement.textContent = getCurrentTime();
        finalTimeInput.value = finalTimeElement.textContent;
        winModal.style.display = "flex";
    }

    function resetGame() {
        resetTimer();
        cards.forEach((card) => {
            card.classList.remove("flip");
            card.addEventListener("click", flipCard);
        });
        setTimeout(() => {
            shuffle();
            resetBoard();
        }, 250);
    }

    function shuffle() {
        cards.forEach((card) => {
            let randomPos = Math.floor(Math.random() * 12);
            card.style.order = randomPos;
        });
    }

    function triggerConfetti() {
        confetti({
            particleCount: 2000,
            spread: 200,
            origin: {
                y: 0.6
            },
            colors: ["#ff0000", "#00ff00", "#0000ff", "#ffff00", "#ff00ff", "#00ffff"],
        });
    }

    // Event listeners
    closeBtn.addEventListener("click", () => {
        winModal.style.display = "none";
        resetGame();
    });

    saveScoreForm.addEventListener("submit", (e) => {
        const playerName = playerNameInput.value.trim();
        if (!playerName) {
            e.preventDefault();
            alert("Por favor, digite seu nome para salvar a pontuação.");
            return
        }
        console.log(playerName);
        resetGame();
    });

    // Initialize game
    resetTimer();
    shuffle();
    cards.forEach((card) => card.addEventListener("click", flipCard));
</script>


@endsection