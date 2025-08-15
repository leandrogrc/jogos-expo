@extends('base')
@section('title', 'Jogo da Memória')
@section('content')

<style>
    main {
        display: flex;
        flex-direction: column;
    }

    /* Timer - GRADIENTE REMOVIDO */
    #timer-container {
        background-color: #ec008a;
        border-radius: 10px;
        padding: 15px 25px;
        width: 100%;
        max-width: 34em;
        margin: 0 auto 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        color: white;
        text-align: center;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: 1.2em;
    }

    #timer {
        font-family: 'Courier New', monospace;
        font-size: 1.5em;
        font-weight: bold;
        letter-spacing: 1px;
        color: #fff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    /* Jogo */
    .memory-game {
        width: 100%;
        max-width: 900px;
        margin: 50px auto;
        display: grid;
        grid-template-columns: repeat(6, minmax(120px, 1fr));
        gap: 10px;
        perspective: 1000px;
        padding: 10px;
    }

    .memory-card {
        position: relative;
        transform-style: preserve-3d;
        transition: transform 0.5s, box-shadow 0.3s, opacity 0.5s ease;
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

    /* Verso da carta */
    .back-face {
        transform: rotateY(0deg);
        background-color: #00c0f2;
        border-radius: 5%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .front-face {
        transform: rotateY(180deg);
    }

    /* Modal - GRADIENTE REMOVIDO */
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
        background-color: #09b049;
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
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 50px;
        margin: 15px 0;
        font-size: 22px;
        font-weight: bold;
        letter-spacing: 1px;
        color: #fff;
    }

    #player-name {
        width: 100%;
        padding: 12px 20px;
        margin: 15px 0;
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

    /* Botão Salvar - GRADIENTE REMOVIDO */
    #save-score {
        background-color: #09b049;
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
        padding: 12px 30px;
        font-size: 16px;
        border-radius: 50px;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-weight: 600;
    }

    #save-score:hover {
        background-color: #0ad157;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Tabela de pontuação - GRADIENTE REMOVIDO */
    #score-table {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        width: 100%;
        max-width: 34em;
        margin: 20px auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
    }

    #score-table table {
        width: 100%;
        border-collapse: collapse;
    }

    #score-table th {
        background-color: #0ab04a;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 0.9em;
    }

    #score-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        color: #333;
    }

    #score-table tr:last-child td {
        border-bottom: none;
    }

    #score-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #score-table td:last-child {
        font-weight: bold;
        color: #f3702a;
    }

    #times {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    /* Botão Voltar - GRADIENTE REMOVIDO */
    #back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: #f3702a;
        color: #fff;
        border: none;
        border-radius: 0.5em;
        padding: 0.8em 1.5em;
        font-size: 1em;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        margin: 1.2rem auto;
        width: 100%;
        max-width: 34em;
    }

    #back-button-container {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    /* Animações */
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

    /* Responsividade */
    @media (max-width: 800px) {
        .memory-game {
            max-width: 600px;
        }
    }

    @media (orientation: portrait) {
        @media (max-width: 810px) {
            .memory-game {
                grid-template-columns: repeat(3, minmax(10px, 1fr));
                grid-template-rows: repeat(6, 1fr);
                gap: 1.4rem;
            }
        }

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

    /* Estilo para cartas encontradas */
    .memory-card.matched {
        opacity: 0.6;
        filter: brightness(0.8) saturate(0.5);
        cursor: default;
        transform: scale(0.95);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border: 0.5rem solid rgba(255, 255, 255, 0.5);
        transition: all 0.5 ease;
    }

    /* Efeito de destaque quando a carta é encontrada */
    .memory-card.matched .front-face {
        animation: matchedPulse 0.5s ease-out;
    }

    @keyframes matchedPulse {
        0% {
            transform: rotateY(180deg) scale(1);
        }

        50% {
            transform: rotateY(180deg) scale(1.1);
        }

        100% {
            transform: rotateY(180deg) scale(1);
        }
    }

    /* Garante que o efeito de hover seja desativado para os cards já encontrados */
    .memory-card.matched:hover {
        transform: scale(0.95);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>

<main>
    <div id="back-button-container">
        <a id="back-button" href="/">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
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
            <form id="save-score-form" action="{{ route('store.memory') }}" method="POST">
                @csrf
                @method('POST')
                <p>Seu tempo: <span id="final-time"></span></p>
                <input name="time" type="hidden" id="final-time-input" />
                <input name="name" type="text" id="player-name" placeholder="Digite seu nome" required />
                <button type="submit" id="save-score">Salvar Pontuação</button>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.memory-card');
        const timerElement = document.getElementById('timer');
        const winModal = document.getElementById('win-modal');
        const finalTimeElement = document.getElementById('final-time');
        const finalTimeInput = document.getElementById('final-time-input');
        const playerNameInput = document.getElementById('player-name');
        const saveScoreForm = document.getElementById('save-score-form');
        const closeBtn = document.querySelector('.close');

        let hasFlippedCard = false;
        let lockBoard = false;
        let firstCard, secondCard;
        let timer;
        let startTime;
        let timerRunning = false;
        let matchedPairs = 0;
        const totalPairs = cards.length / 2;

        // Inicializa o jogo
        function initGame() {
            resetTimer();
            shuffleCards();
            resetBoard();
            matchedPairs = 0;
            winModal.style.display = 'none';

            cards.forEach(card => {
                card.classList.remove('flip', 'matched');
                card.style.opacity = '1';
                card.style.pointerEvents = 'auto';
                card.addEventListener('click', flipCard);
            });
        }

        // Timer functions
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
            timerElement.textContent = '00:00:000';
        }

        function updateTimer() {
            const elapsedTime = Date.now() - startTime;
            const milliseconds = Math.floor(elapsedTime % 1000);
            const seconds = Math.floor((elapsedTime / 1000) % 60);
            const minutes = Math.floor((elapsedTime / (1000 * 60)) % 60);

            timerElement.textContent =
                `${String(minutes).padStart(2, '0')}:` +
                `${String(seconds).padStart(2, '0')}:` +
                `${String(milliseconds).padStart(3, '0')}`;

            timer = requestAnimationFrame(updateTimer);
        }

        // Game functions
        function flipCard() {
            if (lockBoard || this === firstCard || this.classList.contains('matched')) return;

            if (!timerRunning && !hasFlippedCard) {
                startTimer();
            }

            this.classList.add('flip');

            if (!hasFlippedCard) {
                hasFlippedCard = true;
                firstCard = this;
                return;
            }

            secondCard = this;
            lockBoard = true;
            checkForMatch();
        }

        function checkForMatch() {
            const isMatch = firstCard.dataset.framework === secondCard.dataset.framework;

            if (isMatch) {
                // Match encontrado - mostra por 1s antes de esmaecer
                setTimeout(() => {
                    firstCard.classList.add('matched');
                    secondCard.classList.add('matched');
                    firstCard.style.pointerEvents = 'none';
                    secondCard.style.pointerEvents = 'none';

                    // Efeito visual de esmaecimento
                    firstCard.style.opacity = '0.4';
                    secondCard.style.opacity = '0.4';

                    matchedPairs++;
                    resetBoard();
                    checkForWin();
                }, 400);
            } else {
                // Não é match - vira de volta após 1.5s
                setTimeout(() => {
                    firstCard.classList.remove('flip');
                    secondCard.classList.remove('flip');
                    resetBoard();
                }, 500);
            }
        }

        function resetBoard() {
            [hasFlippedCard, lockBoard] = [false, false];
            [firstCard, secondCard] = [null, null];
        }

        function checkForWin() {
            if (matchedPairs === totalPairs) {
                stopTimer();
                triggerConfetti();

                setTimeout(() => {
                    finalTimeElement.textContent = timerElement.textContent;
                    finalTimeInput.value = timerElement.textContent;
                    winModal.style.display = 'flex';
                    playerNameInput.focus();
                }, 500);
            }
        }

        function triggerConfetti() {
            confetti({
                particleCount: 150,
                spread: 70,
                origin: {
                    y: 0.6
                }
            });
        }

        function shuffleCards() {
            cards.forEach(card => {
                const randomPos = Math.floor(Math.random() * cards.length);
                card.style.order = randomPos;
            });
        }

        // Event listeners
        closeBtn.addEventListener('click', () => {
            winModal.style.display = 'none';
            initGame();
        });

        saveScoreForm.addEventListener('submit', function(e) {
            if (!playerNameInput.value.trim()) {
                e.preventDefault();
                alert('Por favor, digite seu nome para salvar a pontuação.');
                playerNameInput.focus();
            } else {
                setTimeout(initGame, 1000);
            }
        });

        // Inicializa o jogo
        initGame();
    });
</script>

@endsection