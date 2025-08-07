@extends('base')
@section('title', 'Jogo das Palavras')
@section('content')

<style>
    .container {
        font-size: 16px;
        background-color: #ffffff;
        width: 100%;
        max-width: 34em;
        padding: 2em;
        border-radius: 0.6em;
        box-shadow: 0 1.2em 2.4em rgba(111, 85, 0, 0.25);
        margin: 20px 0;
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
        padding: 0.6em 1.2em;
        border: 3px solid #000000;
        background-color: #ffffff;
        color: #000000;
        border-radius: 0.3em;
        text-transform: capitalize;
        cursor: pointer;
        margin: 5px 0;
        flex-grow: 1;
        min-width: 120px;
    }

    #options-container button:disabled {
        border: 3px solid #808080;
        color: #808080;
        background-color: #efefef;
    }

    #options-container button.active {
        background-color: #8c52ff;
        border: 3px solid #000000;
        color: #fff;
    }

    .letter-container {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.6em;
    }

    #letter-container button {
        height: 2.4em;
        width: 2.4em;
        border-radius: 0.3em;
        background-color: #ffffff;
        cursor: pointer;
        font-size: 1em;
    }

    .new-game-popup {
        background-color: #ffffff;
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        border-radius: 0;
        z-index: 10;
        padding: 20px;
    }

    #user-input-section {
        display: flex;
        justify-content: center;
        font-size: 1.8em;
        margin: 0.6em 0 1.2em 0;
        flex-wrap: wrap;
    }

    canvas {
        display: block;
        margin: auto;
        border: 10px solid #8c52ff;
        border-radius: 10px;
        max-width: 100%;
        height: auto;
    }

    .hide {
        display: none;
    }

    #result-text h2 {
        font-size: 1.8em;
        text-align: center;
    }

    #result-text p {
        font-size: 1.25em;
        margin: 1em 0 2em 0;
        text-align: center;
    }

    #result-text span {
        font-weight: 600;
    }

    #new-game-button {
        font-size: 1.25em;
        padding: 0.5em 1em;
        background-color: #8c52ff;
        border: 3px solid #000000;
        color: #fff;
        border-radius: 0.2em;
        cursor: pointer;
    }

    .win-msg {
        color: #39d78d;
    }

    .lose-msg {
        color: #fe5152;
    }

    /* Media Queries para responsividade */
    @media screen and (max-width: 768px) {
        .container {
            padding: 1.5em;
        }

        #options-container button {
            min-width: 100px;
            padding: 0.5em 0.8em;
            font-size: 0.9em;
        }

        #letter-container button {
            height: 2em;
            width: 2em;
            font-size: 0.9em;
        }

        #result-text h2 {
            font-size: 1.5em;
        }

        #result-text p {
            font-size: 1.1em;
        }
    }

    @media screen and (max-width: 480px) {
        .container {
            padding: 1em;
        }

        #options-container div {
            margin: 0.8em 0 1.6em 0;
        }

        #options-container button {
            min-width: 80px;
            padding: 0.4em 0.6em;
            font-size: 0.8em;
        }

        #letter-container button {
            height: 1.8em;
            width: 1.8em;
            font-size: 0.8em;
        }

        #user-input-section {
            font-size: 1.4em;
        }

        canvas {
            border-width: 5px;
        }

        #result-text h2 {
            font-size: 1.3em;
        }

        #result-text p {
            font-size: 1em;
        }

        #new-game-button {
            font-size: 1em;
        }
    }
</style>

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

<script>
    // Referências
    const letterContainer = document.getElementById("letter-container");
    const optionsContainer = document.getElementById("options-container");
    const userInputSection = document.getElementById("user-input-section");
    const newGameContainer = document.getElementById("new-game-popup");
    const newGameButton = document.getElementById("new-game-button");
    const resultText = document.getElementById("result-text");
    const attemptsText = document.createElement("div"); // Elemento para exibir as tentativas
    attemptsText.id = "attempts-text";
    userInputSection.before(attemptsText); // Adiciona o texto de tentativas antes da seção de entrada do usuário

    // Opções
    let options = [
        "Cultura de Inovação",
        "Experiência do Usuário",
        "Atendimento Humanizado",
        "Acessibilidade Digital",
        "Portal do Cidadão",
        "Segurança Digital",
        "Transformação Digital",
        "Inovação",
        "Tecnologia",
        "Gestão Eletrônica",
        "Transparência",
    ];

    let winCount = 0;
    let attempts = 5; // Variável de tentativas, substituindo o 'count'
    let chosenWord = "";

    const displayOptions = () => {
        optionsContainer.innerHTML = `<h3>Selecione uma opção</h3>`;
        let buttonCon = document.createElement("div");
        for (let value in options) {
            buttonCon.innerHTML += `<button class="options" onclick="generateWord('${value}')">${value}</button>`;
        }
        optionsContainer.appendChild(buttonCon);
    };

    const blocker = () => {
        document.querySelectorAll(".options, .letters").forEach((btn) => {
            btn.disabled = true;
        });
        newGameContainer.classList.remove("hide");
    };

    const generateWord = () => {
        letterContainer.classList.remove("hide");
        userInputSection.innerText = "";
        attemptsText.classList.remove("hide"); // Mostra as tentativas

        chosenWord =
            options[Math.floor(Math.random() * options.length)].toUpperCase();

        let displayItem = chosenWord
            .split("")
            .map((char) =>
                char === " " ?
                `<span class="dashes space" style="min-width:20px;"> </span>` :
                `<span class="dashes">_</span>`
            )
            .join("");

        userInputSection.innerHTML = displayItem;
        attemptsText.innerHTML = `Tentativas restantes: ${attempts}`;
    };

    const initializer = () => {
        winCount = 0;
        attempts = 5; // Reseta as tentativas
        userInputSection.innerHTML = "";
        optionsContainer.innerHTML = "";
        letterContainer.classList.add("hide");
        newGameContainer.classList.add("hide");
        letterContainer.innerHTML = "";
        resultText.innerHTML = "";
        attemptsText.classList.add("hide"); // Esconde as tentativas no início

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

            if (letterVariations[letter]) {
                btn.addEventListener("contextmenu", (e) => {
                    e.preventDefault();
                    showLetterVariations(e, letter, letterVariations[letter]);
                });
            }

            btn.addEventListener("click", () => handleLetterClick(btn, letter));
            letterContainer.append(btn);
        }

        generateWord();
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
        menu.style.border = "1px solid #ccc";
        menu.style.padding = "5px";
        menu.style.zIndex = "1000";

        variations.forEach((v) => {
            const item = document.createElement("div");
            item.innerText = v;
            item.style.padding = "5px";
            item.style.cursor = "pointer";
            item.onclick = () => {
                handleLetterClick(event.target, v);
                menu.remove();
            };
            menu.appendChild(item);
        });

        document.body.appendChild(menu);
        document.addEventListener("click", () => menu.remove(), {
            once: true
        });
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
                winCount++;
                matched = true;
            }
        });

        if (!matched) {
            attempts--;
            attemptsText.innerHTML = `Tentativas restantes: ${attempts}`;
            if (attempts === 0) {
                resultText.innerHTML = `<h2 class="lose-msg">Você perdeu!!</h2><p>A resposta era: <span>${chosenWord}</span></p>`;
                blocker();
            }
        } else if (winCount === charArray.filter((c) => c !== " ").length) {
            resultText.innerHTML = `<h2 class="win-msg">Você venceu!!</h2><p>A resposta era: <span>${chosenWord}</span></p>`;
            blocker();
        }

        button.disabled = true;
    }

    newGameButton.addEventListener("click", initializer);
    window.onload = initializer;
</script>

@endsection