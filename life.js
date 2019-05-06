'use strict'; //ES5 строгий режим
var console;
var CELL_SIZE = 16; //размер клетки
var cells = [], buffCells = [];
var timeout = 30; //задержка для автоплея
var canvas, game;

var get_field_width = 1;
var get_field_height = 1;

function init() {

    // get two numbers

    var field_width = parseInt(document.getElementById('field_width').value);
    var field_height = parseInt(document.getElementById('field_height').value);

    //back-grid
    canvas = document.getElementById('back').getContext('2d');
    canvas.width =  document.getElementById('back').offsetWidth;
    canvas.height =  document.getElementById('back').offsetHeight;

    document.getElementById('back').width = canvas.width;
    document.getElementById('back').height = canvas.height;
    document.getElementById('game').width = canvas.width;
    document.getElementById('game').height = canvas.height;

    CELL_SIZE = Math.min(canvas.width / field_width, canvas.height / field_height);

    //game
    game = document.getElementById('game').getContext('2d');

    //alert('ONCE: ' + CELL_SIZE+'/'+canvas.width+'/'+canvas.height);
    /* Сетка */
    function Grid() {
        this.size = { x : 0, y : 0 };
        this.width = canvas.width;
        this.height = canvas.height;

        this.size.x = field_width;//parseInt(canvas.width / CELL_SIZE, 10);
        this.size.y = field_height;//parseInt(canvas.height / CELL_SIZE, 10);

		//get_field_height = field_height;
		//get_field_width = field_width;
        //this.size.x = canvas.width;
        //this.size.y = canvas.height;

        /*Размер клеток*/

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
            canvas.translate(0, 0);
            canvas.strokeStyle = "#eee"; // цвет линии
            canvas.lineWidth = .4;
            canvas.beginPath();
            for (i = 0; i <= field_width; i++) {
                canvas.moveTo(0, i * CELL_SIZE);
                canvas.lineTo(canvas.width, i * CELL_SIZE);
            }
            for (i = 0; i <= field_width; i++) {
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
			update_save(grid);
        };

		this.redraw = function () {
            var i, j, grid = new Grid(), upd = new Update();
            upd.clear();
			for (i = 0; i < grid.size.x; i++)
                for (j = 0; j < grid.size.y; j++)
                    if (cells[i][j] === true)
                        upd.fillCell(i, j);
			update_save(grid);
		}

		this.update_save = function (grid){
            get_field_height = grid.size.y;
            get_field_width = grid.size.x;
			if (!get_field_height === 1 && get_field_width === 1))
				return;
            grid_width();
            grid_height();
            grid_values();
		}
		
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
    clearBtn.onclick = function () { init(); gameUpd.clear(); };

    //Кнопка рандомизации
    randBtn = document.getElementById('rand');
    randBtn.onclick = function () { init(); gameUpd.clear(); gameUpd.randomFill(); };

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

	stepBtn.onUpdate = function(){
        gameUpd.redraw();
	}
}
function set_cell (i, j, value){
    cells[i][j] = parseInt(value) === 1;
}
function toggle_cell (i, j){
	cells[i][j] = ! cells[i][j];
}
function grid_width (){ document.cookie = "grid_width=" + get_field_width + "; expires = 60000"; }
function grid_height (){ document.cookie = "grid_height=" + get_field_height + "; expires = 60000"; }

function grid_values(){
	var res = new Array();
	for (i = 0; i < grid_width(); i++)
        for (j = 0; j < grid_height(); j++)
          document.cookie="cell"+i+"_"+j+"=" + (cells[i][j]? 1 : 0) + "; expires = 60000";
}

function printMousePos(event) {
  //alert("clientX: " + event.clientX +
  //  " - clientY: " + event.clientY);
  var bound = document.getElementById('back').getBoundingClientRect();

  var mouseX = Math.round((event.x - Math.round(bound.left)) / CELL_SIZE) - 1;
  var mouseY = Math.round((event.y - Math.round(bound.top)) / CELL_SIZE) - 1;

  toggle_cell(mouseX, mouseY);
  document.getElementById('autoplay').onUpdate();

}
document.getElementById('back').addEventListener("click", printMousePos);

window.onload = init();
