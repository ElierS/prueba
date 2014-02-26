<?php
	
	require 'medoo.php';
	require('pdf/fpdf.php');
	
	$pdf = new FPDF();

	function trimString($str, $len, $sub){
		if (strlen($str) >= $len) return substr($str, 0, $sub). "...";
		else return $str;
	}

	function createTable($header, $obj){
		$w = [75,40,30,30,18];
		
		$database = new medoo([
		    'database_type' => 'mysql',
		    'database_name' => 'catalogo',
		    'server' => 'localhost',
		    'username' => 'root',
		    'password' => 'root'
		]);

		$data = $database->select("tb_libros", [
		  "id",
		  "titulo", 
		  "idautor", 
		  "idcategoria", 
		  "isbn", 
		  "cantidad"
		]);
		$i=0;
	    foreach($header as $col){
	    	$obj->Cell($w[$i],10,$col,1);
	    	$i++;
		}
	    $obj->Ln();
    $datcat=$database->select("tb_categoria",[
		"id"
		],[
		 "tb_categoria.nombre_cat"=>$categoria
		]);
		$dataut=$database->select("tb_autor",[
		"id"
		],[
		 "tb_autor.nombre"=>$autor
		]);
	    foreach($data as $row){
			$obj->Cell(75,8,trimString($row["titulo"], 43, 37), 1);
			

			$dataut=$database->select("tb_autor",[
			"nombre"
		],[
		 "id"=>$row["idautor"]
		]);
			$obj->Cell(40,8,trimString($dataut[0]["nombre"], 27, 20), 1);

		$datcat=$database->select("tb_categoria",[
		"nombre_cat"
		],[
		 "id"=>$row["idcategoria"]
		]);
			$obj->Cell(30,8,$datcat[0]["nombre_cat"], 1);
			$obj->Cell(30,8,$row["isbn"], 1);
			$obj->Cell(18,8,$row["cantidad"], 1);

			$obj->Ln();

		}
      $obj->Output();
   }

	$pdf->SetFont('Arial','I',10);
	$header = ['Titulo','Autor','Categoria','ISBN', 'Cantidad'];

	$pdf->AddPage();
	createTable($header, $pdf);

?>
