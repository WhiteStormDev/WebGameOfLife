<?php
	header("Content-Type: text/html; charset=utf-8");
	session_start ();
	$db = mysqli_connect('localhost', 'root', 'password');
	mysqli_select_db($db, 'GameOfLife');
?>
<!DOCTYPE html>
<html lang="en">
<head meta charset="utf-8">
<title>Game of Life</title>
<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="materialize/css/materialize.css" />
</head>
<body>
  <header>
    <div class="container">

      <div class="row">
        <div class="col s12">
          <div class="card cyan darken-2">
            <div class="card-content white-text">
              <span class="card-title"></span>
              <h6>Если вы хотите загрузить существующий, то введите название мира и пароль.<br/>
                Также вы можете посмотреть прогресс других людей, если введете только название мира</h6>
            </div>
          </div>
        </div>
      </div>
  </div>
  </header>

  <main>
		<script src=\"spawn.js\" type=\"application/javascript\"></script>
    <div class="container">
      <div>
        <form class="col s12" method='post'>
          <div class="row">
            <div class="input-field col s6">
              <input placeholder="Название мира"id="wname" type="text" class="validate" required name = "world_name" maxlength="150">
              <!-- <label for="wname">Название мира</label> -->
							<!-- <span class="helper-text" data-error="wrong" data-success="right">Название мира</span> -->
            </div>
            <div class="input-field col s6">
             <input placeholder="Пароль" id="password" type="password" class="validate" required name = "password">
             <!-- <label for="password">Пароль</label> -->
						 <!-- <span class="helper-text" data-error="wrong" data-success="right">Пароль</span> -->
           </div>
           <div class"row">
             <input type = "submit" class = "waves-effect waves-light btn" name = "loadgame" Value = "Загрузить"/>
             <input type = "submit" class = "waves-effect waves-light btn" name = "savegame" Value = "Сохранить"/>
						 <!-- <input type = "button" class = "waves-effect waves-light btn" name = "newgame" onclick = "spawnGameField()" Value = "Новая игра"/> -->
           </div>
          </div>
        </form>


				<div class="card hoverable">
					<canvas id="game" class="game"></canvas>
					<canvas id="back" class="back"></canvas>
					<div class="card" style="margin: 5px;" >
						<div class="row">
							<div class="col s3">
								<input type="button" class = "waves-effect waves-light btn" id="clear" value="Очистить поле">
							</div>
							<div class="col s3">
								<input type="button" class = "waves-effect waves-light btn" id="rand" value="Случайное поле">
							</div>
							<div class="col s3">
								<input type="button" class = "waves-effect waves-light btn" id="step" value="Следующее поколение">
							</div>
							<div class="col s3">
								<input type="button" class = "waves-effect waves-light btn" id="autoplay" value="Автовоспроизведение">
							</div>

						</div>
						<div class="row">
							<div class="input-field col s2">
								<input value="1280" id="field_width" type="text" class="validate">
								<span class="helper-text" data-error="wrong" data-success="right">Ширина (кл.)</span>
							</div>
							<div class="input-field col s2">
								<input value="400" id="field_height" type="text" class="validate">
								<span class="helper-text" data-error="wrong" data-success="right">Высота (кл.)</span>
							</div>
						</div>
						<div class="row">
							<!-- <hr style="color: dark-cyan;"> -->
							<input type="button" class = "waves-effect waves-light btn" id="glider" value="Глайдер">
							<input type="button" class = "waves-effect waves-light btn" id="exploder" value="Эксплодер">
							<input type="button" class = "waves-effect waves-light btn" id="gosper" value="Пушка Госпера">
						</div>
					</div>
					<p></p>
					<script src="life.js" type="application/javascript"></script>
				</div>
        <?php
          if (isset($_POST['world_name'])) {$world_name = $_POST['world_name'];}
          if (isset($_POST['password'])) {$password = $_POST['password'];}

          if (!empty($_POST['save']))
          {
            printf("<p>Мир сохранен: %s | %s</p>", $world_name, $password);
          }

          if (!empty($_POST['loadgame']))
          {
            $takeWorldIdQuery = 'SELECT `Id` FROM `Worlds` WHERE `Name` = "' . $world_name . '" AND `Password` = "' . $password . '"';
            $result = mysqli_query($db, $takeWorldIdQuery);
            $currentWorldId = mysqli_fetch_array($result);


            $takeMatrixQuery = 'SELECT `Row_Index`, `Column_Index`, `Value` FROM `Cell` WHERE `World_Id` = "' . $currentWorldId["Id"] . '" ORDER BY `Row_Index`, `Column_Index`';
            $matrixResult = mysqli_query($db, $takeMatrixQuery);

            $takeHeightWidthQuery = 'SELECT MAX(`Row_Index`), MAX(`Column_Index`) FROM `Cell`';
            $heightWidthResult = mysqli_query($db, $takeHeightWidthQuery);

            $oldColumnIndex = 0;
            $oldRowIndex = 0;


            //namespace Life;
            // $game = new Game([]);
            // $wh = mysqli_fetch_array($heightWidthResult);
            // $game->createNewGrid(
            //   $wh["MAX(`Column_Index`)"] + 1,
            //   $wh["MAX(`Row_Index`)"] + 1);
            //
            // printf("<p1>Width=%s, Heigth=%s </p1>", $wh["MAX(`Column_Index`)"], $wh["MAX(`Row_Index`)"]);
            //
            // while ($cell = mysqli_fetch_array($matrixResult))
            //   $game->setValue($cell);

            // printf(
            // "
						// <div class=\"map\">
		        //   <canvas id=\"game\">
						// 	</canvas>
						// 	<div class=\"filter\">
						// 		<canvas id=\"back\">
						// 		</canvas>
						// 	</div>
            // </div>
						//
            // <br>
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"clear\" value=\"Очистить поле\">
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"rand\" value=\"Случайное поле\">
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"step\" value=\"Следующее поколение\">
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"autoplay\" value=\"Автовоспроизведение\">
            // <hr>
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"glider\" value=\"Глайдер\">
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"exploder\" value=\"Эксплодер\">
            // <input type=\"button\" class = \"waves-effect waves-light btn\" id=\"gosper\" value=\"Пушка Госпера\">
						//
            // <script src=\"life.js\" type=\"application/javascript\"></script>
						// ");



          }
        ?>
      </div>
    </div>
  </main>
</body>
  <footer class="page-footer">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">Работу выполнили:</h5>
          <p class="grey-text text-lighten-4">Тутельян Максим и Прохорченко Леонид</p>
        </div>
      </div>
    </div>
  </footer>
</html>
