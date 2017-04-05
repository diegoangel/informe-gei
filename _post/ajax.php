<?php

header('Content-Type: application/json');

include "init.php";

if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'run' )
{

}

if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'distribucion-sector' && !empty($_REQUEST['sector_id']) && $_REQUEST['sector_id'] == 'all')
{
	$ano = (int)$_REQUEST['ano'];

	$arrReturn = array();

	$sql = "SELECT SUM(e.valor) as total, e.sector_id, s.nombre, s.color, s.descripcion
			FROM emision e 
			INNER JOIN sector s ON e.sector_id = s.id
			WHERE e.ano = $ano
			GROUP BY e.sector_id";

	$arr = $db->get_results($sql,ARRAY_A);

	$i = 1;

	foreach($arr as $a)
	{
		$arrReturn['sector_'.$i][] 		= $a['nombre'];
		$arrReturn['sector_'.$i][] 		= $a['total'];
		$arrReturn['colores'][] 		= $a['color'];
		$arrReturn['descripciones'][] 	= $a['descripcion'];

		$i++;
	}

	echo json_encode($arrReturn);

}

if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'distribucion-sector' && !empty($_REQUEST['sector_id']) && $_REQUEST['sector_id'] != 'all')
{
	$ano = (int)$_REQUEST['ano'];

	$sql = "SELECT SUM(e.valor) as total, e.sector_id, s.nombre
			FROM emision e 
			INNER JOIN sector s ON e.sector_id = s.id
			WHERE e.ano = $ano
			GROUP BY e.sector_id";

	$arr = $db->get_results($sql,ARRAY_A);

	$arrNombres = array();
	$arrValor = array();

	foreach($arr as $a)
	{
		$arrReturn['sector_1'][] 	= $a['nombre'];
		$arrReturn['sector_1'][] 	= $a['total'];

	}

	echo json_encode($arrReturn);

}


if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'distribucion-gases' )
{

	

	$ano = (int)$_REQUEST['ano'];

	$arrReturn = array();

	$sql = "SELECT e.gas_id, g.nombre, g.color, sum(e.valor) as total
			FROM emision e
			LEFT JOIN gas g ON (e.gas_id = g.id)
			where e.ano = $ano GROUP BY e.gas_id ORDER BY total DESC";

	$arr = $db->get_results($sql,ARRAY_A);


	$arrReturn['gases'][] = 'x';
	$arrReturn['valores'][] = 'Gases';

	foreach($arr as $a)
	{
		$arrReturn['gases'][] 	= (strpos($a['nombre'], ',')) ? '"'.$a['nombre'].'"' : $a['nombre'];
		$arrReturn['valores'][] = round($a['total']);
		$arrReturn['colores'][] = $a['color'];
	}

	echo json_encode($arrReturn);


}

if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'distribucion-gases-sector' )
{


	$ano = (int)$_REQUEST['ano'];

	$arrReturn = array();

	// PRIMERA FILA LA DE LOS GASES

	$sql = "SELECT g.nombre, sum(e.valor) as total
			FROM emision e
			LEFT JOIN gas g ON (e.gas_id = g.id)
			where e.ano = $ano GROUP BY e.gas_id ORDER BY total DESC";

	$arrGases = $db->get_results($sql,ARRAY_A);
	
	
	
	// ['x', 'CH4', 'CO2', 'N2O', 'SF6'],
	// ['Energia', 980, 439, 274, 390, 30],
	// ['Residuos', 980, 439, 274, 390, 30],
	// ['Procesos Industriales y Uso de Productos', 980, 439, 274, 390, 30],
	// ['Agricultura, Ganadería, Silvicultura y Otros Usos de la Tierra', 980, 439, 274, 390, 30],

	$sql = "SELECT s.nombre
			FROM sector s 
			ORDER BY s.nombre ";

	$arrSectores = $db->get_col($sql);

	$sql = "SELECT s.nombre as sector, g.nombre as gas, sum(e.valor) as total
			FROM emision e
			LEFT JOIN gas g ON (e.gas_id = g.id)
			LEFT JOIN sector s ON (e.sector_id = s.id)
			where e.ano = $ano GROUP BY e.gas_id, e.sector_id ORDER BY total DESC";

	$arr = $db->get_results($sql,ARRAY_A);

	$column = 2;



	foreach($arrSectores as $sector)
	{
		$arrReturn['column_'.$column][] = $sector;

		foreach($arrGases as $gas)
		{

			$arrReturn['column_'.$column][] = returnSectorGas($arr,$sector,$gas['nombre']);
			
		}

		$column++;
	}

	$arrReturnGases = array('x');
	
	foreach($arrGases as $gas)
	{

		$arrReturnGases[] = $gas['nombre'];
		
	}

	$arrReturn['column_1'] = $arrReturnGases;

	echo json_encode($arrReturn);


}









if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'evolucion-sector')
{

	$ano = (int)$_REQUEST['ano'];

	$arrReturn = array();

	$sql = "SELECT s.nombre, s.color
			FROM sector s 
			WHERE 1 ";

	$sql.= 	(!empty($_REQUEST['sector_id']) && $_REQUEST['sector_id'] != 'all') ? "AND s.id = ".$_REQUEST['sector_id'] : '';

	$sql.= " ORDER BY s.nombre ";

	$arrSectores = $db->get_results($sql,ARRAY_A);

	$sql = "SELECT s.nombre as sector, e.ano, sum(e.valor) as total
			FROM emision e
			LEFT JOIN sector s ON (e.sector_id = s.id)
			where 1 ";

	$sql.= 	(!empty($_REQUEST['sector_id']) && $_REQUEST['sector_id'] != 'all') ? "AND e.sector_id = ".$_REQUEST['sector_id'] : '';

	$sql.= " GROUP BY e.ano, s.nombre
			ORDER BY e.ano, s.nombre";

	// echo $sql;

	$arr = $db->get_results($sql,ARRAY_A);

	$arrAnos = array();
	$arrValores = array();
	$arrColores = array();


	for($i=1990;$i<=2014;$i++)
	{
		$arrAnos[] = $i;
	}
		
	

	$column = 2;

	foreach($arrSectores as $sector)
	{
		$arrReturn['column_'.$column][] = $sector['nombre'];
		$arrReturn['colores'][] =  $sector['color'];

		foreach($arrAnos as $ano)
		{

			$arrReturn['column_'.$column][] = returnSectorAno($arr,$sector['nombre'],$ano);
			
		}

		$column++;
	}


	$arrAnos = array_merge(array('x'), $arrAnos);
	$arrReturn['column_1'] = $arrAnos;

	// pr($arrReturn);


	echo json_encode($arrReturn);
	


}




if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'evolucion-sector-subactividad')
{

	$ano = (int)$_REQUEST['ano'];

	$arrReturn = array();

	$sql = "SELECT sub.nombre
			FROM subactividad sub
			INNER JOIN actividad a ON (a.id = sub.actividad_id)
			INNER JOIN sector s ON (a.sector_id = s.id)
			WHERE 1 ";

	$sql.= 	(!empty($_REQUEST['sector_nombre']) && $_REQUEST['sector_nombre'] != 'all') ? "AND s.nombre = '".$_REQUEST['sector_nombre']."'" : '';

	$sql.= " ORDER BY sub.nombre ";

	$arrSubactividades = $db->get_results($sql,ARRAY_A);

	$sql = "SELECT sub.nombre, e.ano, e.valor
			FROM emision e
			INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
			INNER JOIN sector s ON (e.sector_id = s.id)";

	$sql.= 	(!empty($_REQUEST['sector_nombre']) && $_REQUEST['sector_nombre'] != 'all') ? "AND s.nombre = '".$_REQUEST['sector_nombre']."'" : '';

	$sql.= " GROUP BY e.ano, sub.nombre
			ORDER BY e.ano, sub.nombre";

	// echo $sql;

	$arr = $db->get_results($sql,ARRAY_A);

	$arrAnos = array();
	$arrValores = array();
	$arrColores = array();


	for($i=1990;$i<=2014;$i++)
	{
		$arrAnos[] = $i;
	}

	$column = 2;

	// pr($arrSubactividades);
	// pr($arr);

	foreach($arrSubactividades as $subactividad)
	{
		$arrReturn['column_'.$column][] = $subactividad['nombre'];
		$arrReturn['groups'][] = $subactividad['nombre'];

		foreach($arrAnos as $ano)
		{
			$arrReturn['column_'.$column][] = returnSubactividadAno($arr,$subactividad['nombre'],$ano);
		}

		$column++;
	}


	$arrAnos = array_merge(array('x'), $arrAnos);
	$arrReturn['column_1'] = $arrAnos;

	

	// pr($arrReturn);


	echo json_encode($arrReturn);
	


}



if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'evolucion-sector-subactividad-categoria')
{

	$ano = (int)$_REQUEST['ano'];

	$arrReturn = array();

	$sql = "SELECT DISTINCT c.nombre
			FROM emision e
			INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
			INNER JOIN sector s ON (e.sector_id = s.id) 
			INNER JOIN categoria c ON (e.categoria_id = c.id)
			WHERE 1 ";

	$sql.= 	(!empty($_REQUEST['sector_nombre']) && $_REQUEST['sector_nombre'] != 'all') ? "AND s.nombre = '".$_REQUEST['sector_nombre']."' " : '';
	$sql.= 	(!empty($_REQUEST['subactividad_nombre']) && $_REQUEST['subactividad_nombre'] != 'all') ? "AND sub.nombre = '".$_REQUEST['subactividad_nombre']."' " : '';

	$sql.= "ORDER BY c.nombre";

	$arrReturn['sql1'] = $sql;

	$arrCategorias = $db->get_results($sql,ARRAY_A);



	

	$sql = "SELECT sub.nombre as subcategoria, e.ano, c.nombre, e.valor
			FROM emision e
			INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
			INNER JOIN sector s ON (e.sector_id = s.id) 
			INNER JOIN categoria c ON (e.categoria_id = c.id)
			WHERE 1 ";
	$sql.= 	(!empty($_REQUEST['sector_nombre']) && $_REQUEST['sector_nombre'] != 'all') ? "AND s.nombre = '".$_REQUEST['sector_nombre']."' " : '';
	$sql.= 	(!empty($_REQUEST['subactividad_nombre']) && $_REQUEST['subactividad_nombre'] != 'all') ? "AND sub.nombre = '".$_REQUEST['subactividad_nombre']."' " : '';
	$sql.= 	" GROUP BY e.ano, c.nombre
			ORDER BY sub.nombre, e.ano, c.nombre";

	$arrReturn['sql2'] = $sql;

	$arr = $db->get_results($sql,ARRAY_A);

	// echo $sql;die();

	$arrAnos = array();
	$arrValores = array();
	$arrColores = array();


	for($i=1990;$i<=2014;$i++)
	{
		$arrAnos[] = $i;
	}

	$column = 2;

	// pr($arrCategorias);
	// pr($arr);

	foreach($arrCategorias as $categoria)
	{
		$arrReturn['column_'.$column][] = $categoria['nombre'];
		$arrReturn['groups'][] = $categoria['nombre'];

		foreach($arrAnos as $ano)
		{
			$arrReturn['column_'.$column][] = returnCategoriaAno($arr,$categoria['nombre'],$ano);
		}

		$column++;
	}


	$arrAnos = array_merge(array('x'), $arrAnos);
	$arrReturn['column_1'] = $arrAnos;

	

	// pr($arrReturn);


	echo json_encode($arrReturn);
	


}



if( !empty($_REQUEST['f']) && $_REQUEST['f'] == 'indicador')
{
	$indicador_id = (int)$_REQUEST['indicador_id'];

	$sql = "SELECT * FROM indicador WHERE id = $indicador_id";	
	$arrIndicador = $db->get_row($sql,ARRAY_A);

	$arrReturn = array();

	$arrReturn['indicador'] = $arrIndicador;

	$sql = "SELECT * FROM indicador_valor 
			WHERE indicador_id = $indicador_id
			ORDER BY ano ASC";

	$arr = $db->get_results($sql,ARRAY_A);



	foreach($arr as $a)
	{
		// SI EL NOMBRE TIENE UNA COMA LO TENGO QUE PONER ENTRE COMILLAS
		$arrAnos[]	= $a['ano'];
		$arrValor[] = $a['valor'];
	}

	$arrAnos = array_merge( array('x'), $arrAnos );
	$arrReturn['column_1'] = $arrAnos;

	$arrValor = array_merge( array($arrIndicador['nombre']), $arrValor );
	$arrReturn['column_2'] = $arrValor;

	$arrReturn['unidad'] = $arrIndicador['unidad'];
	$arrReturn['descripcion'] = nl2br($arrIndicador['descripcion']);

	$arrReturn['colores'] = "#8064a2";

	echo json_encode($arrReturn);

}