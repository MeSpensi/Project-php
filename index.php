<?php
// PHP здесь чисто для формы, всю магию игры делает JavaScript в браузере
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Игра: Змейка</title>
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 30px;
        }
        canvas {
            border: 4px solid #fff;
            background-color: #000;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
        #score {
            font-size: 24px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <h1>🐍 Змейка на PHP + JS</h1>
    <div id="score">Счет: 0</div>
    <canvas id="gameCanvas" width="400" height="400"></canvas>

    <script>
        const canvas = document.getElementById("gameCanvas");
        const ctx = canvas.getContext("2d");

        const box = 20; // Размер одной клетки
        let score = 0;

        // Змейка начинается с одной "головы"
        let snake = [];
        snake[0] = { x: 9 * box, y: 10 * box };

        // Создаем еду в случайных координатах
        let food = {
            x: Math.floor(Math.random() * 19 + 1) * box,
            y: Math.floor(Math.random() * 19 + 1) * box
        };

        // Задаем стартовое направление сразу, чтобы змейка не стояла на месте
        let d = "RIGHT";
        document.addEventListener("keydown", direction);

        function direction(event) {
            // Защита от разворота на 180 градусов в обратную сторону
            if (event.keyCode == 37 && d != "RIGHT") d = "LEFT";
            else if (event.keyCode == 38 && d != "DOWN") d = "UP";
            else if (event.keyCode == 39 && d != "LEFT") d = "RIGHT";
            else if (event.keyCode == 40 && d != "UP") d = "DOWN";
        }

        // Проверка на столкновение с хвостом
        function collision(head, array) {
            for (let i = 0; i < array.length; i++) {
                if (head.x == array[i].x && head.y == array[i].y) {
                    return true;
                }
            }
            return false;
        }

        // Рендер игры
        function draw() {
            ctx.fillStyle = "#000";
            ctx.fillRect(0, 0, 400, 400);

            // Рисуем змейку
            for (let i = 0; i < snake.length; i++) {
                ctx.fillStyle = (i == 0) ? "#4CAF50" : "#8BC34A";
                ctx.fillRect(snake[i].x, snake[i].y, box, box);
                ctx.strokeStyle = "#000";
                ctx.strokeRect(snake[i].x, snake[i].y, box, box);
            }

            // Рисуем еду
            ctx.fillStyle = "#FF5722";
            ctx.fillRect(food.x, food.y, box, box);

            // Старые координаты головы
            let snakeX = snake[0].x;
            let snakeY = snake[0].y;

            // Куда движется змейка
            if (d == "LEFT") snakeX -= box;
            if (d == "UP") snakeY -= box;
            if (d == "RIGHT") snakeX += box;
            if (d == "DOWN") snakeY += box;

            // Если змейка съела еду
            if (snakeX == food.x && snakeY == food.y) {
                score++;
                document.getElementById("score").innerText = "Счет: " + score;
                food = {
                    x: Math.floor(Math.random() * 19 + 1) * box,
                    y: Math.floor(Math.random() * 19 + 1) * box
                };
            } else {
                // Удаляем хвост, если не съела
                snake.pop();
            }

            // Новая голова змейки
            let newHead = { x: snakeX, y: snakeY };

            // Условия проигрыша (стенки или собственный хвост)
            if (snakeX < 0 || snakeX >= canvas.width || snakeY < 0 || snakeY >= canvas.height || collision(newHead, snake)) {
                clearInterval(game);
                alert("💀 Игра окончена! Твой счет: " + score);
                location.reload(); // Перезапуск страницы
            }

            snake.unshift(newHead);
        }

        // Скорость игры (каждые 100 миллисекунд)
        let game = setInterval(draw, 100);
    </script>

</body>
</html>