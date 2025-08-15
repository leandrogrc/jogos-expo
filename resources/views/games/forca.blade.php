@extends('base')
@section('title', 'Jogo das Palavras')
@section('content')

<style>
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

    .container {
        font-size: 16px;
        background-color: #ffffff;
        width: 100%;
        max-width: 34em;
        padding: 2em;
        border-radius: 0.6em;
        box-shadow: 0 1.2em 2.4em rgba(111, 85, 0, 0.25);
        margin: 20px auto;
        color: #333;
    }

    #options-container {
        text-align: center;
    }

    #options-container div {
        width: 100%;
        display: flex;
        justify-content: space-between;
        margin: 1.2em 0 2.4em 0;
        flex-wrap: wrap;
    }

    #options-container button {
        padding: 0.8em 1.5em;
        border: none;
        background: #00c0f2;
        color: #fff;
        border-radius: 0.5em;
        text-transform: capitalize;
        cursor: pointer;
        margin: 5px 0;
        flex-grow: 1;
        min-width: 150px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #options-container button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    #options-container button:disabled {
        background: #e0e0e0;
        color: #a0a0a0;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .letter-container {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.6em;
        margin: 1em 0;
    }

    #letter-container button {
        height: 2.8em;
        width: 2.8em;
        border-radius: 0.5em;
        background: #f5f5f5;
        border: 2px solid #e0e0e0;
        cursor: pointer;
        font-size: 1em;
        font-weight: bold;
        transition: all 0.2s ease;
    }

    #letter-container button:hover:not(:disabled) {
        background: #e0e0e0;
    }

    #letter-container button:disabled {
        background: #e0e0e0;
        color: #a0a0a0;
        cursor: not-allowed;
    }

    .new-game-popup {
        background-color: rgba(255, 255, 255, 0.95);
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        z-index: 100;
        padding: 20px;
        text-align: center;
    }

    #user-input-section {
        display: flex;
        justify-content: center;
        font-size: 1.8em;
        margin: 0.6em 0 1.2em 0;
        flex-wrap: wrap;
        gap: 5px;
        min-height: 2em;
    }

    .dashes {
        display: inline-block;
        text-align: center;
        min-width: 25px;
        font-weight: bold;
    }

    .space {
        min-width: 20px;
    }

    .hide {
        display: none;
    }

    #result-text h2 {
        font-size: 1.8em;
        text-align: center;
        margin-bottom: 0.5em;
    }

    #result-text p {
        font-size: 1.25em;
        margin: 1em 0 2em 0;
        text-align: center;
    }

    #result-text span {
        font-weight: 600;
        color: #8c52ff;
    }

    #new-game-button {
        font-size: 1.25em;
        padding: 0.8em 1.8em;
        background: linear-gradient(145deg, #8c52ff, #6d3aff);
        border: none;
        color: #fff;
        border-radius: 0.5em;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #new-game-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .win-msg {
        color: #39d78d;
    }

    .lose-msg {
        color: #fe5152;
    }

    #attempts-text {
        font-size: 1.2em;
        font-weight: 600;
        text-align: center;
        margin: 1em 0;
        color: #555;
    }

    /* Botão de voltar */
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

    #back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    #back-button i {
        margin-right: 8px;
    }

    /* Layout de pontuação */
    #times {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        flex-wrap: wrap;
        justify-content: center;
        gap: 30px;
        margin: 20px auto;
        max-width: 1200px;
    }

    #score-table {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        width: 100%;
        max-width: 34em;
        margin: 0;
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
        background: #0ab04a;
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
        background-color: #f9f9f9;
    }

    #score-table td:last-child {
        font-weight: bold;
        color: #8c52ff;
    }

    /* Timer */
    #timer-container {
        background: #ec008a;
        border-radius: 10px;
        padding: 15px 25px;
        width: 100%;
        max-width: 34em;
        margin: 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        color: white;
        text-align: center;
    }

    #timer {
        font-family: 'Courier New', monospace;
        font-size: 1.5em;
        font-weight: bold;
        letter-spacing: 1px;
    }

    /* Modal de vitória */
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

    /* Animações */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    /* Responsividade */
    @media screen and (max-width: 768px) {
        .container {
            padding: 1.5em;
            max-width: 100%;
        }

        #options-container button {
            min-width: 120px;
            padding: 0.7em 1em;
            font-size: 0.9em;
        }

        #letter-container button {
            height: 2.4em;
            width: 2.4em;
            font-size: 0.9em;
        }

        #user-input-section {
            font-size: 1.5em;
        }

        #times {
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        #timer-container,
        #score-table {
            max-width: 100%;
            width: 100%;
        }
    }

    @media screen and (max-width: 544px) {

        .container {
            padding: 1.5em;
            max-width: 80%;
        }

        #times,
        #back-button-container {
            max-width: 80%;
        }

        #back-button-container {
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 80%;
        }

        #options-container button {
            min-width: 100%;
            margin: 5px 0;
        }

        #letter-container button {
            height: 2.2em;
            width: 2.2em;
            font-size: 0.8em;
        }

        #user-input-section {
            font-size: 1.3em;
        }

        #result-text h2 {
            font-size: 1.5em;
        }

        #result-text p {
            font-size: 1.1em;
        }

        #new-game-button {
            font-size: 1.1em;
            padding: 0.7em 1.5em;
        }
    }

    #back-button-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
    }
</style>

<main>
    <div id="back-button-container">
        <a id="back-button" href="/">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
    <div id="times">
        <div id="timer-container">
            <div>Tempo:</div>
            <div id="timer">00:00:000</div>
        </div>

        @if($scores->isNotEmpty())
        <div id="score-table">
            <table>
                <thead>
                    <tr>
                        <th>Posição</th>
                        <th>Nome</th>
                        <th>Tempo</th>
                        <th>Erros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $index => $score)
                    <tr>
                        <td>{{ $index + 1 }}º</td>
                        <td>{{ $score->name }}</td>
                        <td>{{ $score->time }}</td>
                        <td>{{ 5 - $score->attempts  }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="container">
        <div id="options-container"></div>
        <div id="letter-container" class="letter-container hide"></div>
        <div id="attempts-text" class="hide"></div>
        <div id="user-input-section"></div>
        <div id="new-game-popup" class="new-game-popup hide">
            <div id="result-text"></div>
            <button id="new-game-button">Novo Jogo</button>
        </div>
    </div>

    <!-- Modal de Vitória -->
    <div id="win-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Parabéns! Você venceu!</h2>
            <form id="save-score-form" action="{{ route('store.words') }}" method="POST">
                @csrf
                @method('POST')
                <p>Seu tempo: <span id="final-time"></span></p>
                <input name="time" type="hidden" id="final-time-input" />
                <input name="attempts" type="hidden" id="attempts" value="" />
                <input name="name" type="text" id="player-name" placeholder="Digite seu nome" required />
                <button type="submit" id="save-score">Salvar Pontuação</button>
            </form>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    // Referências
    const letterContainer = document.getElementById("letter-container");
    const optionsContainer = document.getElementById("options-container");
    const userInputSection = document.getElementById("user-input-section");
    const newGameContainer = document.getElementById("new-game-popup");
    const newGameButton = document.getElementById("new-game-button");
    const resultText = document.getElementById("result-text");
    const attemptsText = document.getElementById("attempts-text");

    // Opções
    const options = [
        "Cultura de Inovação",
        "Experiência do Usuário",
        "Atendimento Humanizado",
        "Acessibilidade Digital",
        "Portal do Cidadão",
        "Segurança Digital",
        "Transformação Digital",
        "Inovação",
        "Gestão Eletrônica",
        "Transparência",
        "Tecnologia",
        // "A"
    ];

    // Variáveis do jogo
    let winCount = 0;
    let attempts = 5;
    let chosenWord = "";
    let timerRunning = false;
    let startTime;
    let timer;

    // Elementos do timer e modal
    const timerElement = document.getElementById("timer");
    const winModal = document.getElementById("win-modal");
    const finalTimeElement = document.getElementById("final-time");
    const finalTimeInput = document.getElementById("final-time-input");
    const playerNameInput = document.getElementById("player-name");
    const saveScoreForm = document.getElementById("save-score-form");
    const closeBtn = document.querySelector(".close");
    const attemptsInput = document.getElementById("attempts");

    // Event listeners
    closeBtn.addEventListener("click", () => {
        winModal.style.display = "none";
        initializer();
    });

    saveScoreForm.addEventListener("submit", (e) => {
        const playerName = playerNameInput.value.trim();
        if (!playerName) {
            e.preventDefault();
            alert("Por favor, digite seu nome para salvar a pontuação.");
            playerNameInput.focus();
        }
    });

    // Funções do timer
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

    function showWinModal() {
        const currentTime = getCurrentTime();
        finalTimeElement.textContent = currentTime;

        // Formatando o tempo para o formato MM:SS:MMM antes de enviar
        const [minutes, seconds, milliseconds] = currentTime.split(':');
        const formattedTime = `${minutes.padStart(2, '0')}:${seconds.padStart(2, '0')}:${milliseconds.padStart(3, '0')}`;

        finalTimeInput.value = formattedTime;

        // Calculando tentativas usadas (5 - tentativas restantes)
        const attemptsUsed = 5 - attempts;
        attemptsInput.value = attemptsUsed;

        winModal.style.display = "flex";
        playerNameInput.focus();
    }

    // Efeitos visuais
    function triggerConfetti() {
        confetti({
            particleCount: 150,
            spread: 70,
            origin: {
                y: 0.6
            },
            colors: ["#8c52ff", "#6d3aff", "#4facfe", "#00f2fe"],
        });
    }

    // Lógica do jogo
    const displayOptions = () => {
        optionsContainer.innerHTML = `
            <h3>Aperte em começar jogo</h3>
            <p>O cronômetro iniciará automaticamente</p>
        `;

        let buttonCon = document.createElement("div");
        buttonCon.innerHTML = `
            <button class="options" onclick="startGame()">
                Começar Jogo
            </button>
        `;
        optionsContainer.appendChild(buttonCon);
    };

    const startGame = () => {
        // Esconde o botão e texto de instrução
        const startButton = document.querySelector('#options-container button');
        const instructionText = document.querySelectorAll('#options-container h3, #options-container p');

        if (startButton) startButton.style.display = 'none';
        instructionText.forEach(el => el.style.display = 'none');

        generateWord();
        startTimer();
    };

    const blocker = () => {
        document.querySelectorAll(".options, .letters").forEach((btn) => {
            btn.disabled = true;
        });
        newGameContainer.classList.remove("hide");
        stopTimer();
    };

    const generateWord = () => {
        letterContainer.classList.remove("hide");
        userInputSection.innerText = "";
        attemptsText.classList.remove("hide");
        attemptsText.innerHTML = `Tentativas restantes: <span style="color: #8c52ff; font-weight: bold;">${attempts}</span>`;

        // Escolhe uma palavra aleatória
        chosenWord = options[Math.floor(Math.random() * options.length)].toUpperCase();

        // Cria os traços para cada letra
        let displayItem = chosenWord
            .split("")
            .map((char) =>
                char === " " ?
                `<span class="dashes space" style="min-width:20px;"> </span>` :
                `<span class="dashes">_</span>`
            )
            .join("");

        userInputSection.innerHTML = displayItem;
    };

    const initializer = () => {
        winCount = 0;
        attempts = 5;
        userInputSection.innerHTML = "";
        optionsContainer.innerHTML = "";
        letterContainer.classList.add("hide");
        newGameContainer.classList.add("hide");
        letterContainer.innerHTML = "";
        resultText.innerHTML = "";
        attemptsText.classList.add("hide");
        resetTimer();
        winModal.style.display = "none";

        // Mostra novamente o botão e texto de instrução
        const startButton = document.querySelector('#options-container button');
        const instructionText = document.querySelectorAll('#options-container h3, #options-container p');

        if (startButton) startButton.style.display = '';
        instructionText.forEach(el => el.style.display = '');

        // Cria os botões de letras
        const letterVariations = {
            A: ["A", "Á", "À", "Â", "Ã"],
            E: ["E", "É", "Ê"],
            I: ["I", "Í"],
            O: ["O", "Ó", "Ô", "Õ"],
            U: ["U", "Ú"],
            C: ["C", "Ç"],
        };

        for (let i = 65; i <= 90; i++) {
            let letter = String.fromCharCode(i);
            let btn = document.createElement("button");
            btn.classList.add("letters");
            btn.innerText = letter;

            // Adiciona menu de variações para letras com acentos
            if (letterVariations[letter]) {
                btn.addEventListener("contextmenu", (e) => {
                    e.preventDefault();
                    showLetterVariations(e, letter, letterVariations[letter]);
                });
            }

            btn.addEventListener("click", () => handleLetterClick(btn, letter));
            letterContainer.append(btn);
        }

        displayOptions();
    };

    function showLetterVariations(event, baseLetter, variations) {
        const existing = document.getElementById("letter-variations-menu");
        if (existing) existing.remove();

        const menu = document.createElement("div");
        menu.id = "letter-variations-menu";
        menu.style.position = "absolute";
        menu.style.left = `${event.clientX}px`;
        menu.style.top = `${event.clientY}px`;
        menu.style.backgroundColor = "white";
        menu.style.border = "1px solid #ddd";
        menu.style.borderRadius = "5px";
        menu.style.padding = "5px";
        menu.style.zIndex = "1000";
        menu.style.boxShadow = "0 2px 10px rgba(0,0,0,0.1)";

        variations.forEach((v) => {
            const item = document.createElement("div");
            item.innerText = v;
            item.style.padding = "8px 12px";
            item.style.cursor = "pointer";
            item.style.transition = "background 0.2s";
            item.onmouseenter = () => item.style.background = "#f0f0f0";
            item.onmouseleave = () => item.style.background = "transparent";
            item.onclick = () => {
                handleLetterClick(event.target, v);
                menu.remove();
            };
            menu.appendChild(item);
        });

        document.body.appendChild(menu);

        // Remove o menu quando clicar fora
        const clickHandler = () => {
            menu.remove();
            document.removeEventListener("click", clickHandler);
        };
        document.addEventListener("click", clickHandler);
    }

    function handleLetterClick(button, letter) {
        let charArray = chosenWord.split("");
        let dashes = document.getElementsByClassName("dashes");

        let matched = false;
        charArray.forEach((char, index) => {
            if (
                char === letter ||
                (letter === "C" && char === "Ç") ||
                (letter === "Ç" && char === "C") ||
                (["A", "Á", "À", "Â", "Ã"].includes(letter) && ["A", "Á", "À", "Â", "Ã"].includes(char)) ||
                (["E", "É", "Ê"].includes(letter) && ["E", "É", "Ê"].includes(char)) ||
                (["I", "Í"].includes(letter) && ["I", "Í"].includes(char)) ||
                (["O", "Ó", "Ô", "Õ"].includes(letter) && ["O", "Ó", "Ô", "Õ"].includes(char)) ||
                (["U", "Ú"].includes(letter) && ["U", "Ú"].includes(char))
            ) {
                dashes[index].innerText = char;
                dashes[index].style.color = "#8c52ff";
                winCount++;
                matched = true;

                // Efeito visual quando acerta
                dashes[index].style.animation = "pulse 0.3s";
                setTimeout(() => {
                    dashes[index].style.animation = "";
                }, 300);
            }
        });

        if (!matched) {
            attempts--;
            attemptsText.innerHTML = `Tentativas restantes: <span style="color: ${attempts > 2 ? '#8c52ff' : '#fe5152'}; font-weight: bold;">${attempts}</span>`;

            // Efeito visual quando erra
            button.style.animation = "shake 0.5s";
            setTimeout(() => {
                button.style.animation = "";
            }, 500);

            if (attempts === 0) {
                resultText.innerHTML = `
                    <h2 class="lose-msg">Você perdeu!</h2>
                    <p>A palavra era: <span>${chosenWord}</span></p>
                `;
                blocker();
            }
        } else if (winCount === charArray.filter((c) => c !== " ").length) {
            resultText.innerHTML = `
                <h2 class="win-msg">Parabéns!</h2>
                <p>Você acertou: <span>${chosenWord}</span></p>
            `;
            triggerConfetti();
            setTimeout(() => {
                showWinModal();
            }, 1000);
        }

        button.disabled = true;
        button.style.background = matched ? "#d4edda" : "#f8d7da";
        button.style.color = matched ? "#155724" : "#721c24";
    }

    // Inicializa o jogo
    newGameButton.addEventListener("click", initializer);
    window.onload = initializer;

    // Adiciona animação de shake
    document.head.insertAdjacentHTML("beforeend", `
        <style>
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
        </style>
    `);
</script>

@endsection