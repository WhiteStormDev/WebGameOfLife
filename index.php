<?php
	header("Content-Type: text/html; charset=utf-8");
	session_start ();
	$db = mysqli_connect('localhost', 'root', 'password');
	//$queryAddW = 'INSERT INTO `Worlds` (`Name`, `Password`,`Field_Height`, `Field_Width`, ``) VALUES ("Tryworld", "password", 10, 10)';

	mysqli_select_db($db, 'GameOfLife');
?>
<!DOCTYPE html>
<html lang="en">
<head meta charset="utf-8">
<title>Game of Life</title>
<link rel="stylesheet" type="text/css" href="materialize/css/materialize.css" />
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <header>

    <div class="nave-wrapper">
			<div class="row">
        <div class="col s12">
          <div class="card cyan darken-2">
            <div class="card-content white-text">
              <span class="card-title"></span>
              <h4>Game of life</h4>
            </div>
          </div>
        </div>
      </div>


  </div>
  </header>

  <main>
		<div class="container">


		<style>

		</style>
		<div class="row">
			<div class="col s12">
				<div class="card cyan darken-2">
					<div class="card-content white-text">
						<span class="card-title"></span>
						<ul class="tabs z-depth-3">
							<li class="tab col s6">
								<a href="#gametab" class="active indigo-text text-darken-4">Игра</a>
							</li>
							<li class="tab col s6">
								<a href="#savestab" class="indigo-text text-darken-4">Сохранения</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div id="savestab">
				<div class="row">
	        <div class="col s12">
	          <div class="card cyan darken-2">
	            <div class="card-content white-text">
	              <span class="card-title"></span>
								<div class="worldtable">
									<table class="highlight">
									 <thead>
										 <tr id="worldtr">
												 <th>Название мира</th>
												 <th>Ширина поля</th>
												 <th>Высота поля</th>
										 </tr>
									 </thead>

									 <tbody>
										 <?php
										 $querySelectWorlds = 'SELECT `Name`, `Field_Width`, `Field_Height` FROM `Worlds`';
										 $worldsResult = mysqli_query($db, $querySelectWorlds);
										 while ($w = mysqli_fetch_array($worldsResult))
										 {
											 $n = $w["Name"];
											 $fw = $w["Field_Width"];
											 $fh = $w["Field_Height"];
											 printf('<tr>
																 <td class="worldnametd">%s</td>
																 <td>%s</td>
																 <td>%s</td>
															 </tr>', $n, $fw, $fh);
										 }
										 ?>
									 </tbody>
								 </table>
						 		</div>
	            </div>
	          </div>
	        </div>
				</div>
		</div>
		<div id="gametab">
				<div class="row">
	        <div class="col s12">
						<h6>Если вы хотите загрузить существующий, то введите название мира и пароль.<br/>
							Также вы можете посмотреть прогресс других людей, если введете только название мира</h6>
	          <!-- <div class="card cyan darken-2">
	            <div class="card-content white-text">
	              <span class="card-title"></span>
	              <h6>Если вы хотите загрузить существующий, то введите название мира и пароль.<br/>
	                Также вы можете посмотреть прогресс других людей, если введете только название мира</h6>
	            </div>
	          </div> -->
	        </div>
	      </div>
	      <div>
	        <form class="col s12" method='post'>
	          <div class="row">
	            <div class="input-field col s6">
	              <input placeholder="Название мира"id="wname" type="text" class="validate" required name = "world_name" maxlength="150">
	            </div>
	            <div class="input-field col s6">
	             <input placeholder="Пароль" id="password" type="password" class="autocomplete-input" required name = "password">
	           </div>
	           <div class"row">
							<div class="col s12">
								<a class="waves-effect waves-light white-text btn">
	             		<input type = "submit" id = "submitload" name = "loadgame" value = "Загрузить"/>
								</a>
	             	<input type = "submit" id = "submitsave" class = "waves-effect waves-light white-text btn" name = "savegame" value = "Сохранить"/>
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
									<input type="image" src="icons/step.png"  class = "waves-effect waves-circle waves-light btn-floating" id="step" value="Следующее поколение">
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
										<input type="number" name="width" min="10" max="160" value="128" id="field_width">
										<span class="helper-text" data-error="wrong" data-success="right">Ширина (кл.)</span>
									</div>
									<div class="input-field col s2">
										<input type="number" name="height" min="10" max="50" value="50" id="field_height">
										<span class="helper-text" data-error="wrong" data-success="right">Высота (кл.)</span>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- <hr style="color: dark-cyan;"> -->
								<div class="col s12">
										<input type="button" class="waves-effect waves-light btn" id="glider" value="Глайдер">
										<input type="button" class="waves-effect waves-light btn" id="exploder" value="Эксплодер">
										<input type="button" class="waves-effect waves-light btn" id="gosper" value="Пушка Госпера">
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


				$currentFieldWidth = $_COOKIE['grid_width'];
				$currentFieldHeight = $_COOKIE['grid_height'];

				if ($currentFieldWidth * $currentFieldHeight > 160 * 50){
					echo "<script type = 'text/javascript'>
						alert('Площадь сохраняемого поля не должна превышать 160 * 50 = 8000 клеток!');
					</script>";
					return;
				}
				$grid_values_portion_count = $_COOKIE['grid_portions_count'];

				$grid_values = "";
				for ($i = 0; $i < $grid_values_portion_count; $i++)
					$grid_values = $grid_values . $_COOKIE['grid_values_' . ($i)];

				if($tryTakeExistWorld = mysqli_fetch_array($takeExistResult))
				{
					$tryId = $tryTakeExistWorld["Id"];
					$passwordCorrect = $password == $tryTakeExistWorld["Password"];
					if ($passwordCorrect)
					{
						$queryUpdateWorldsWidth = 'UPDATE `Worlds` SET `Field_Width` = ' . $currentFieldWidth . ' WHERE `Id` = ' . $tryId;
						$queryUpdateWorldsHeight = 'UPDATE `Worlds` SET `Field_Height` = ' . $currentFieldHeight . ' WHERE `Id` = ' . $tryId;
						$queryDeleteExistCells = 'DELETE FROM `Cell` WHERE `World_Id` = ' . $tryId;

						mysqli_query($db, $queryUpdateWorldsWidth);
						mysqli_query($db, $queryUpdateWorldsHeight);
						mysqli_query($db, $queryDeleteExistCells);

						$queryUpdateWorldsMatrix = 'UPDATE `Worlds` SET `Matrix` = "' . $grid_values . '" WHERE `Id` = ' . $tryId;
						mysqli_query($db, $queryUpdateWorldsMatrix);

						$saveMessage = 'Последнее сохранение мира обновленно';
					} else {
						$saveMessage = 'Мир с таким именем уже существует, введен неверный пароль';
					}
				} else {
					$queryAddWorld = 'INSERT INTO `Worlds` (`Name`, `Password`, `Field_Width`, `Field_Height`, `Matrix`) VALUES ("'
							. $world_name .'", "' . $password . '", ' . $currentFieldWidth . ', ' . $currentFieldHeight . ', "' .  $grid_values . '")';
					$queryTakeWorldId = 'SELECT `Id` FROM `Worlds` WHERE `Name` = "' . $world_name . '"';

					mysqli_query($db, $queryAddWorld);

					$saveMessage = "Новый мир сохранен!";
				}
				echo "<script type = 'text/javascript'>
					alert('$saveMessage');
				</script>";
			}
			else
		    if (isset($_POST['loadgame']) || isset($_POST['world_name'])){

	            $takeWorldIdQuery = 'SELECT `Id`, `Password`, `Field_Width`, `Field_Height`, `Matrix` FROM `Worlds` WHERE `Name` = "' . $world_name . '"';
	            $result = mysqli_query($db, $takeWorldIdQuery);

	            if (!($currentWorld = mysqli_fetch_array($result))){
								echo "<script type = 'text/javascript'>
									alert(\"Мира с таким названием не существует\");
								</script>";
							} else {
								$gridValues = $currentWorld["Matrix"];
								$fieldWidth = $currentWorld["Field_Width"];
								$fieldHeight = $currentWorld["Field_Height"];

								echo "<script type = 'text/javascript'>
									updateTextboxes('$fieldWidth', '$fieldHeight');
									init();
									document.getElementById('autoplay').onUpdate();
								</script>";

								$c = 0; $r = 0;
								for ($i = 0; $i < strlen($gridValues); $i++){
									$currentValue = $gridValues[$i];
									echo "<script type = \"text/javascript\">
										set_cell('$c','$r','$currentValue');
									</script>";
									// after assigment increase rows index
									$r++;
									if ($r == $fieldHeight){
										$r = 0;
										$c ++;	// then increase coluemn number
									}
								}
								echo "<script type = \"text/javascript\">
									document.getElementById('autoplay').onUpdate();
								</script>";
							}
	          }
	        ?>
	      </div>
	    </div>
		</div>
  </main>
</body>
  <footer class="page-footer">
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
	  <script>
	    $(document).ready(function(){
	      $('.tabs').tabs();
				// $('input.autocomplete').autocomplete({
				// 	data: {
				// 		"" : p
				// 	},
				// });
				$("div.worldtable table").delegate('tr', 'click', function() {

					var n=$(this).children('.worldnametd');
			    var worldName=n[0].innerHTML;
					alert(worldName);
					$.post(window.location, {loadgame: true, world_name: 'Tryworld'}, function(data) {

					//alert('POST was successful. Server says: ' + data);
					});
								// $.post('index.php',
								// 	{loadgame: $('td[name = "worldnametd"]').val(), world_name: $('td[name = "worldnametd"]').val()},
								// 	function (data){alert("Мир загружен");
								// 					document.location.href = document.location;
								// 	});
				        //get <td> element values here!!??
				    });
	    });
	  </script>
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
