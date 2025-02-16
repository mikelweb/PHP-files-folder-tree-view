<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['oldName']) && isset($_POST['newName']))
{
    exec("mv ".escapeshellarg($_POST['oldName'])." ".escapeshellarg($_POST['newName']), $output);
	// var_dump($output);
	exit();
}

if(isset($_POST['deleteFile']))
{
    exec("rm ".escapeshellarg($_POST['deleteFile']), $output);
	var_dump($output);
	exit();
}

if(isset($_POST['newdirectory']))
{
    $nombreCarpeta = $_POST['newdirectory'];
	$nombreCarpeta = str_replace(" ", "\ ", $nombreCarpeta);
    exec("mkdir /Applications/XAMPP/xamppfiles/htdocs/".$nombreCarpeta, $output);
	if(empty($output))
	   	$response = new StdClass();
		$response->result = 1;
		$response->nombreCarpeta = $nombreCarpeta;
		echo json_encode($response);
	exit();
}


function listadoDirectorio($directorio){
    $listado = scandir($directorio);
    unset($listado[array_search('.', $listado, true)]);
    unset($listado[array_search('..', $listado, true)]);
	unset($listado[array_search('.DS_Store', $listado, true)]);
	unset($listado[array_search('._.DS_Store', $listado, true)]);
	
    if (count($listado) < 1) {
        return;
    }
    foreach($listado as $elemento){
		if(!is_dir($directorio.'/'.$elemento) && !str_starts_with($elemento, "._")) {
    		$path=str_replace('./','', $directorio);
			echo '<li>';
				echo '<img src="img/avi.png"/>';
				echo ' <a href="#modal2" data-toggle="modal" data-path='.urlencode($path.'/'.$elemento).'" target="_blank">'.$elemento.'</a>';
				echo ' <input type="text" value="'.$elemento.'">';
				echo ' <img src="img/edit2.png" class="edit" data-path="'.$directorio.'"/>';
				echo ' <img src="img/guardar.png" class="save" data-path="'.$elemento.'"/>';
				echo ' <img src="img/delete.png" class="delete" data-path="'.$directorio.'/'.$elemento.'"/>';
			echo "</li>";
		}
        if(is_dir($directorio.'/'.$elemento)) {
			echo '<li class="open-dropdown" id="'.$elemento.'">';
				echo '<img src="img/folder-close.png" class="folder"/> ';
				echo '<span>'.$elemento.'</span>';
				echo '<input type="text" value="'.$elemento.'">';
				echo ' <img src="img/edit2.png" class="edit" data-path="'.$directorio.'"/>';
				echo ' <img src="img/guardar.png" class="save" data-path="'.$elemento.'"/>';
				echo ' <input type="file" class="upload none">';
				echo ' <img src="img/plus.jpg" class="add" data-path="'.$directorio.'/'.$elemento.'"/>';
				echo ' <div class="spinner-border uploadspinner" role="status"></div>';
			echo '</li>';
			echo '<ul class="dropdown d-none">';
				listadoDirectorio($directorio.'/'.$elemento);
			echo '</ul>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Listar directorio php</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link href="style.css" rel="stylesheet">
</head>
<body>

	<div class="alert alert-success alert-dismissible fade show m-3 d-none" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>

	<button class="plus" id="newdirectory">+</button>
	<ul id="directories">
		<?php listadoDirectorio('./'); ?>
	</ul>

	<div class="modal fade" id="modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">

				</div>
			</div>
		</div>
	</div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<script src="functions.js"></script>
</body>
</html>