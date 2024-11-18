<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hola Mundo desde el Servidor</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .rainbow-text span {
            font-size: 2em;
            font-weight: bold;
            margin: 0 5px;
            text-align: center;
            display: inline-block;
            animation: rainbow 2s infinite alternate;
        }
        
        /* Definimos los colores del arcoíris en la animación */
        @keyframes rainbow {
            0% { color: #FF0000; }
            14% { color: #FF7F00; }
            28% { color: #FFFF00; }
            42% { color: #00FF00; }
            57% { color: #0000FF; }
            71% { color: #4B0082; }
            85% { color: #9400D3; }
            100% { color: #FF0000; }
        }

        /* Retrasos para que cada palabra cambie en diferente momento */
        .rainbow-text .word1 { animation-delay: 0s; }
        .rainbow-text .word2 { animation-delay: 0.3s; }
        .rainbow-text .word3 { animation-delay: 0.6s; }
        .rainbow-text .word4 { animation-delay: 0.9s; }
        .rainbow-text .word5 { animation-delay: 1.2s; }
    </style>
</head>
<body>
    <h1 class="rainbow-text">
        <span class="word1">Hola</span>
        <span class="word2">mundo</span>
        <span class="word3">desde</span>
        <span class="word4">el</span>
        <span class="word5">servidor</span>
    </h1>
</body>
</html>
