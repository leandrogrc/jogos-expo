<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogos - Expofeira</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset e Estilos Globais */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            /* background: linear-gradient(135deg, rgb(0, 18, 91), rgb(10, 55, 103), rgb(22, 90, 162), rgb(74, 144, 218), rgb(121, 121, 255), rgb(255, 255, 255)); */
            background: linear-gradient(135deg, #121212, #212529, #007BFF, #00CFE8, #6F42C1, #DC3545, #FFC107);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            text-align: center;
            max-width: 1200px;
            width: 100%;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            display: inline-block;
        }

        h1::after {
            content: '';
            position: absolute;
            width: 60%;
            height: 4px;
            background: #fff;
            bottom: -10px;
            left: 20%;
            border-radius: 2px;
        }

        #buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 50px;
        }

        .game-btn {
            position: relative;
            width: 350px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .game-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(to bottom right,
                    rgba(255, 255, 255, 0.3),
                    rgba(255, 255, 255, 0));
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .game-btn:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .game-btn:hover::before {
            top: 100%;
            left: 100%;
        }

        .game-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .game-btn:hover .game-icon {
            transform: scale(1.1);
        }

        .game-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .game-desc {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 20px;
        }

        .play-btn {
            padding: 10px 25px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid #fff;
            border-radius: 50px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
        }

        .play-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            #buttons {
                gap: 20px;
            }

            .game-btn {
                width: 240px;
                height: 290px;
            }
        }

        @media (max-width: 544px) {
            h1 {
                font-size: 1.8rem;
            }

            #logo {
                width: 90%;
                height: auto;
            }

            #buttons {
                flex-direction: column;
                gap: 15px;
                align-content: center;
            }

            .game-btn {
                width: 100%;
                max-width: 300px;
                height: auto;
                padding: 30px 20px;
            }
        }

        .section-logo {
            text-align: center;
            margin: 20px 0 40px;
            animation: fadeIn 1s ease-in-out;
        }

        #logo,
        #footer-logo {
            height: 100px;
            transition: transform 0.3s;
        }

        #footer-logo {
            height: 80px;
        }

        #logo:hover,
        #footer-logo:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <section>
        <header class="section-logo">
            <img id="logo" src="{{ asset('images/jogos/memoria/expo.png') }}" alt="Logo GEA" />
        </header>

        @yield('content')

        <footer>
            <div class="section-logo">
                <img
                    id="footer-logo"
                    src="{{ asset('images/jogos/memoria/PRODAP_LOGO_branco.png') }}"
                    alt="Imagem do footer" />
            </div>
        </footer>
    </section>
</body>

</html>