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
            </div>
            <div class="input-field col s6">
             <input placeholder="Пароль" id="password" type="password" class="validate" required name = "password">
           </div>
           <div class"row">
						<div class="col s12">
             	<input type = "image" src="icons/load.png" id = "submitload" class = "waves-effect waves-light btn" name = "loadgame" value = "submit"/>
             	<input type = "image" src="icons/save.png" id = "submitsave" class = "waves-effect waves-light btn" name = "savegame" value = "submit"/>
					 	</div>
						 <!-- <input type = "button" class = "waves-effect waves-light btn" name = "newgame" onclick = "spawnGameField()" Value = "Новая игра"/> -->
           </div>
          </div>
        </form>


				<div class="card hoverable">
					<canvas id="game" class="game"></canvas>
					<canvas id="back" class="back"></canvas>
					<div class="card" style="margin: 5px;" >
						<div class="row">
							<div class="col s6">
								<input type="image" src="icons/new.png" class = "waves-effect waves-light btn" id="clear">
								<input type="image" src="icons/random.png" class = "waves-effect waves-light btn" id="rand">

							</div>
							<div class="col s6">
								<input type="image" src="icons/step.png"  class = "waves-effect waves-light btn" id="step" value="Следующее поколение">
								<input type="image" src="icons/autoplay.png"  class = "waves-effect waves-light btn" id="autoplay" value="Автовоспроизведение">
								
								<input type="image" src="icons/touch.png"  class = "waves-effect waves-light btn" id="touch">
								<input type="image" src="icons/pencil.png"  class = "waves-effect waves-light btn" id="pencil">
								<input type="image" src="icons/fill.png"  class = "waves-effect waves-light btn" id="square">
								<input type="image" src="icons/eraser.png" class = "waves-effect waves-light btn" id="erase">

							</div>
						</div>
						<div class="row">
							<div class="col s12">
								<div class="input-field col s2">
									<input value="128" id="field_width" type="text" class="validate">
									<span class="helper-text" data-error="wrong" data-success="right">Ширина (кл.)</span>
								</div>
								<div class="input-field col s2">
									<input value="50" id="field_height" type="text" class="validate">
									<span class="helper-text" data-error="wrong" data-success="right">Высота (кл.)</span>
								</div>
							</div>
						</div>
						<div class="row">
							<!-- <hr style="color: dark-cyan;"> -->
							<div class="col s12">
								<input type="button" class = "waves-effect waves-light btn" id="glider" value="Глайдер">
								<input type="button" class = "waves-effect waves-light btn" id="exploder" value="Эксплодер">
								<input type="button" class = "waves-effect waves-light btn" id="gosper" value="Пушка Госпера">
							</div>
						</div>
					</div>
					<p></p>
					<script src="life.js" type="application/javascript"></script>
				</div>
				<script type = "text/javascript">
					function updateTextboxes(value1, value2){
					  document.getElementById('field_width').value = value1;
						document.getElementById('field_height').value = value2;
						return false;

					}
					// $(document).ready(function(){
					// 	$('#submitbtn').click (function(){
					// 		$.post('saveGame.php',
					// 		{id: <php? echo ?>, world_name: $('input[name = "world_name"]').val(), password: $('input[name = "password"]').val()},
					// 		function (data){alert("Игра сохранена");
					// 						document.location.href = document.location;
					// 		});
					// 	});
					// })
				</script>
        <?php
          if (isset($_POST['world_name'])) {$world_name = $_POST['world_name'];}
          if (isset($_POST['password'])) {$password = $_POST['password'];}

          if (isset($_POST['savegame']))
          {
						$rowIndex;
						$columnIndex;
						$value;

						$queryTryTakeExistWorld = 'SELECT `Id`, `Password`, `Field_Width`, `Field_Height` FROM `Worlds` WHERE `Name` = "' . $world_name . '"';
						$takeExistResult = mysqli_query($db, $queryTryTakeExistWorld);

						if($tryTakeExistWorld = mysqli_fetch_array($takeExistResult))
						{
							$tryId = $tryTakeExistWorld["Id"];
							$passwordCorrect = $password == $tryTakeExistWorld["Password"];


							if ($passwordCorrect)
							{
								// echo "<script type = 'text/javascript'>
								// 	grid_width();
								// 	grid_height();
								// 	grid_values();
								// </script>";
								$currentFieldWidth = $_COOKIE['grid_width'];
								$currentFieldHeight = $_COOKIE['grid_height'];

								printf('<p>%s | %s</p>', $currentFieldWidth, $currentFieldHeight);
								$queryUpdateWorldsWidth = 'UPDATE `Worlds` SET `Field_Width` = ' . $currentFieldWidth . ' WHERE `Id` = ' . $tryId;
								$queryUpdateWorldsHeight = 'UPDATE `Worlds` SET `Field_Height` = ' . $currentFieldHeight . ' WHERE `Id` = ' . $tryId;
								$queryDeleteExistCells = 'DELETE FROM `Cell` WHERE `World_Id` = ' . $tryId;

								mysqli_query($db, $queryUpdateWorldsWidth);
								mysqli_query($db, $queryUpdateWorldsHeight);
								mysqli_query($db, $queryDeleteExistCells);

								$grid_values = $_COOKIE['grid_values'];
								
								//for ($c = 0; $c < $currentFieldWidth; $c++)
								//	for ($r = 0; $r < $currentFieldHeight; $r++)
								//	{
										//$currentCellValue = $_COOKIE['cell' . $c . '_' . $r];
										//printf('<p>%s | </p>', $currentCellValue);
								//		$queryAddCell = 'INSERT INTO `Cell` (`Row_Index`, `Column_Index`, `Value`, `World_Id`) VALUES (' . $c . ', ' . $r . ', ' . $currentCellValue . ', ' . $tryId . ')';
								//		mysqli_query($db, $queryAddCell);
								//	}
								$c = 0; $r = 0;
								for ($i = 0; $i < strlen($grid_values); $i++){
									$queryAddCell = 'INSERT INTO `Cell` (`Row_Index`, `Column_Index`, `Value`, `World_Id`) VALUES (' 
													. $c . ', ' . $r . ', ' . $grid_values[$i] . ', ' . $tryId . ')';
									mysqli_query($db, $queryAddCell);
									// after assigment increase rows index
									$r++;
									if ($r == $currentFieldHeight){
										$r = 0;
										$c ++;	// then increase coluemn number
									}
								}
								
								$saveMessage = 'Последнее сохранение мира обновленно';
							} else {
								$saveMessage = 'Мир с таким именем уже существует, введен неверный пароль';
							}
						} else {
							$currentFieldWidth = $_COOKIE['grid_width'];
							$currentFieldHeight = $_COOKIE['grid_height'];

							$queryAddWorld = 'INSERT INTO `Worlds` (`Name`, `Password`, `Field_Width`, `Field_Height`) VALUES ("' . $world_name .'", "' . $password . '", ' . $currentFieldWidth . ', ' . $currentFieldHeight . ')';
							$queryTakeWorldId = 'SELECT `Id` FROM `Worlds` WHERE `Name` = "' . $world_name . '"';

							mysqli_query($db, $queryAddWorld);
							$newWorldResult = mysqli_query($db, $queryTakeWorldId);
							if ($newWorldId = mysqli_fetch_array($newWorldResult))
							{
								$tryId = $newWorldId["Id"];
								// for ($c = 0; $c < $currentFieldWidth; $c++)
								// {
									// for ($r = 0; $r < $currentFieldHeight; $r++)
									// {
										// $currentCellValue = $_COOKIE['cell' . $c . '_' . $r];
										// $queryAddCell = 'INSERT INTO `Cell` (`Row_Index`, `Column_Index`, `Value`, `World_Id`) VALUES (' . $c . ', ' . $r . ', ' . $currentCellValue . ', ' . $tryId . ')';
										// mysqli_query($db, $queryAddCell);
									// }
								// }
								$grid_values = $_COOKIE['grid_values'];
								$c = 0; $r = 0;
								for ($i = 0; $i < strlen($grid_values); $i++){
									$queryAddCell = 'INSERT INTO `Cell` (`Row_Index`, `Column_Index`, `Value`, `World_Id`) VALUES (' 
													. $c . ', ' . $r . ', ' . $grid_values[$i] . ', ' . $tryId . ')';
									mysqli_query($db, $queryAddCell);
									// after assigment increase rows index
									$r++;
									if ($r == $currentFieldHeight){
										$r = 0;
										$c ++;	// then increase coluemn number
									}
								}
								$saveMessage = "Новый мир сохранен!";
							}
						}
						echo "<script type = 'text/javascript'>
							alert('$saveMessage');
						</script>";
          }
					else
          if (isset($_POST['loadgame']))
          {
            $takeWorldIdQuery = 'SELECT `Id`, `Password`, `Field_Width`, `Field_Height` FROM `Worlds` WHERE `Name` = "' . $world_name . '"';
            $result = mysqli_query($db, $takeWorldIdQuery);
            if ($currentWorld = mysqli_fetch_array($result))
						{
							if ($password != $currentWorld["Password"])
							{
								echo "<script type = 'text/javascript'>
									alert(\"Неверный пароль\");
								</script>";
								return;
							}
						} else {
							echo "<script type = 'text/javascript'>
								alert(\"Мира с таким названием не существует\");
							</script>";
							return;
						}


            $takeMatrixQuery = 'SELECT `Row_Index`, `Column_Index`, `Value` FROM `Cell` WHERE `World_Id` = "' . $currentWorld["Id"] . '" ORDER BY `Row_Index`, `Column_Index`';
            $matrixResult = mysqli_query($db, $takeMatrixQuery);

            $field_width = $currentWorld["Field_Width"];
						$field_height = $currentWorld["Field_Height"];

						echo "<script type = 'text/javascript'>
							updateTextboxes('$field_width', '$field_height');
							init();
							document.getElementById('autoplay').onUpdate();
						</script>";
						while ($cell = mysqli_fetch_array($matrixResult))
						{
							$cellRowInd = $cell["Row_Index"];
							$cellColInd = $cell["Column_Index"];
							$cellValue = $cell["Value"];
							print($cellValue);
							echo "<script type = \"text/javascript\">set_cell('$cellRowInd','$cellColInd','$cellValue');</script>";
						}
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
