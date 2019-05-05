'use strict'; //ES5 строгий режим
var console;

var width = 400;
var height = 200;

var CELL_SIZE = 4; //размер клетки
var cells = [], buffCells = [];
var timeout = 30; //задержка для автоплея
var canvas, game;

var length = 1; //для писоса

function init() {
    //back-grid
    canvas = document.getElementById('back').getContext('2d');
    canvas.width =  document.getElementById('back').width;
    canvas.height =  document.getElementById('back').height;

    //game
    game = document.getElementById('game').getContext('2d');

    /* Сетка */
    function Grid() {
        this.size = { x : 0, y : 0 };
        this.size.x = width;
        this.size.y = height;

        /* заполняем массив cells */
        this.fill = function () {
            var i, j;
            for (i = 0; i < this.size.x; i++) {
                cells[i] = [];
                buffCells[i] = [];
                for (j = 0; j < this.size.y; j++) {
                    cells[i][j] = false;
                    buffCells[i][j] = false;
                }
            }
        };

        /* рисуем сетку */
        this.draw = function () {
            var i;
            canvas.translate(0.5, 0.5);
            canvas.strokeStyle = "#eee"; // цвет линии
            canvas.lineWidth = 0.4;
            canvas.beginPath();
            for (i = 0; i <= this.size.x; i++) {
                canvas.moveTo(0, i * CELL_SIZE);
                canvas.lineTo(canvas.width, i * CELL_SIZE);
            }
            for (i = 0; i <= this.size.x; i++) {
                canvas.moveTo(i * CELL_SIZE, 0);
                canvas.lineTo(i * CELL_SIZE, canvas.height);
            }
            canvas.stroke();
        };
    }

    /* обновляем отрисовку */
    function Update() {
        //var upd = new Update();

        this.clear = function () {
            game.clearRect(0, 0, canvas.width, canvas.height);
        };

        this.fillCell = function (x, y) {
            game.fillRect(x * CELL_SIZE, y * CELL_SIZE, CELL_SIZE + 1, CELL_SIZE + 1);
        };

        this.fill = function () {
            var i, j, grid = new Grid(), upd = new Update();
            upd.clear();
            for (i = 0; i < grid.size.x; i++)
                for (j = 0; j < grid.size.y; j++)
                    //Тут можно устроить инверсию цвета
                    if (cells[i][j] === true)
                        upd.fillCell(i, j);
            //Перессчитываем ячейки
            upd.cells();
        };

        /* рандомная заливка для тестов */
        this.randomFill = function () {
            var i, j, fill, fillRnd, grid = new Grid(), upd = new Update();
            //очищаем предыдущий рисунок
            upd.clear();

            for (i = 0; i < grid.size.x; i++) {
                for (j = 0; j < grid.size.y; j++) {
                    //рандомизация boolean
                    fill = [true, false][Math.round(Math.random())];
                    cells[i][j] = Boolean(fill);
                }
            }

            for (i = 0; i < grid.size.x; i++) {
                for (j = 0; j < grid.size.y; j++) {
                    fill = cells[i][j];
                    if (fill === true) {
                        //заполняем новый рисунок
                        fillRnd = new Update();
                        fillRnd.fillCell(i, j);
                    }
                }
            }
        };

        this.toggleStop = function () {
            this.isPaused = true;
        }

        this.neis = new Array(-1, 0, 1);

        this.getLivingNeighbors = function (x, y) {
            var grid = new Grid(), count = 0, sx = grid.size.x, sy = grid.size.y;

            for (var i = 0; i < 3; ++i)
                for (var j = 0; j < 3; ++j)
                    if (!(i === 1 && j === 1))
                        count += cells[(x + this.neis[i] + sx) % sx][(y + this.neis[j] + sy) % sy] === true;
            return count;
        };

        this.cells = function () {
            var i, j, isAlive, count, result = false, gameUpd = new Update(), grid = new Grid();

            for (i = 0; i < grid.size.x; i++)
                for (j = 0; j < grid.size.y; j++) {
                    isAlive = cells[i][j];
                    count = gameUpd.getLivingNeighbors(i, j);
                    buffCells[i][j] =
                        (isAlive && (count === 2 || count === 3))
                        || (!isAlive && count === 3);
                }

            for (i = 0; i < grid.size.x; i++)
                for (j = 0; j < grid.size.y; j++)
                    cells[i][j] = buffCells[i][j];


        };

        /* Создаём юнитов */
        this.newUnit = function (unit) {
            var i, j, grid = new Grid(), off_x = parseInt(grid.size.x / 2, 10), off_y = parseInt(grid.size.y / 2, 10);
            //очищаем массив по тупому
            for (i = 0; i < grid.size.x; i++) {
                for (j = 0; j < grid.size.y; j++) {
                    cells[i][j] = false;
                }
            }

            //заполняем
            //хотя хорошо бы эту дичь в JSON запихнуть
            switch (unit) {
            case 'glider':
                cells[off_x + 1][off_y + 2] = true;
                cells[off_x + 2][off_y + 3] = true;
                cells[off_x + 3][off_y + 1] = true;
                cells[off_x + 3][off_y + 2] = true;
                cells[off_x + 3][off_y + 3] = true;
                break;

            case 'gosper':
                cells[off_x + 1][off_y + 5] = true;
                cells[off_x + 1][off_y + 6] = true;
                cells[off_x + 2][off_y + 5] = true;
                cells[off_x + 2][off_y + 6] = true;

                cells[off_x + 12][off_y + 5] = true;
                cells[off_x + 12][off_y + 6] = true;
                cells[off_x + 12][off_y + 7] = true;

                cells[off_x + 13][off_y + 4] = true;
                cells[off_x + 13][off_y + 8] = true;

                cells[off_x + 14][off_y + 3] = true;
                cells[off_x + 14][off_y + 9] = true;

                cells[off_x + 15][off_y + 4] = true;
                cells[off_x + 15][off_y + 8] = true;

                cells[off_x + 16][off_y + 5] = true;
                cells[off_x + 16][off_y + 6] = true;
                cells[off_x + 16][off_y + 7] = true;

                cells[off_x + 17][off_y + 5] = true;
                cells[off_x + 17][off_y + 6] = true;
                cells[off_x + 17][off_y + 7] = true;

                cells[off_x + 22][off_y + 3] = true;
                cells[off_x + 22][off_y + 4] = true;
                cells[off_x + 22][off_y + 5] = true;

                cells[off_x + 23][off_y + 2] = true;
                cells[off_x + 23][off_y + 3] = true;
                cells[off_x + 23][off_y + 5] = true;
                cells[off_x + 23][off_y + 6] = true;

                cells[off_x + 24][off_y + 2] = true;
                cells[off_x + 24][off_y + 3] = true;
                cells[off_x + 24][off_y + 5] = true;
                cells[off_x + 24][off_y + 6] = true;

                cells[off_x + 25][off_y + 2] = true;
                cells[off_x + 25][off_y + 3] = true;
                cells[off_x + 25][off_y + 4] = true;
                cells[off_x + 25][off_y + 5] = true;
                cells[off_x + 25][off_y + 6] = true;

                cells[off_x + 26][off_y + 1] = true;
                cells[off_x + 26][off_y + 2] = true;
                cells[off_x + 26][off_y + 6] = true;
                cells[off_x + 26][off_y + 7] = true;

                cells[off_x + 35][off_y + 3] = true;
                cells[off_x + 35][off_y + 4] = true;

                cells[off_x + 36][off_y + 3] = true;
                cells[off_x + 36][off_y + 4] = true;
                break;
            }

            for (i = 0; i < grid.size.x; i++) {
                for (j = 0; j < grid.size.y; j++) {
                    console.log(cells[i][j]);
                }
            }


        };
    }

    var gameGrid = new Grid(), gameUpd = new Update(), clearBtn, randBtn, stepBtn, gliderBtn;
    gameGrid.draw();
    gameGrid.fill();

    //Кнопка очистки
    clearBtn = document.getElementById('clear');
    clearBtn.onclick = function () { gameUpd.clear(); };

    //Кнопка рандомизации
    randBtn = document.getElementById('rand');
    randBtn.onclick = function () { gameUpd.randomFill(); };

    //Кнопка шага
    stepBtn = document.getElementById('step');
    stepBtn.onclick = function () { gameUpd.fill(); };

    //Кнопка autoplay
    stepBtn = document.getElementById('autoplay');
    stepBtn.working = false;
    stepBtn.onclick = function () {
        stepBtn.working = !stepBtn.working;
        if (stepBtn.working)
            stepBtn.onAutoplay();
    };

    stepBtn.onAutoplay = function(){
        setTimeout(function() {
            var upd = new Update();
            upd.fill();
            if (stepBtn.working === true)
                stepBtn.onAutoplay();
        }, timeout);
    }

    gliderBtn = document.getElementById('glider');
    gliderBtn.onclick = function () {
        gameGrid.fill();
        gameUpd.newUnit('glider');
        gameUpd.fill();
    };
    gliderBtn = document.getElementById('gosper');
    gliderBtn.onclick = function () {
        gameGrid.fill();
        gameUpd.newUnit('gosper');
        gameUpd.fill();
    };
}

window.onload = init();