<?php

function pr($arr)
{
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}

function returnSectorGas($arr,$sector,$gas)
{

	foreach($arr as $val)
	{
		if($val['sector'] == $sector && $val['gas'] == $gas)
		{
			return $val['total'];
		}
	}

	return 0;
}

function returnSectorAno($arr,$sector,$ano)
{

	foreach($arr as $val)
	{
		if($val['sector'] == $sector && $val['ano'] == $ano)
		{
			return $val['total'];
		}
	}

	return 0;
}

function returnSubactividadAno($arr,$subactividad,$ano)
{

	foreach($arr as $val)
	{
		if(trim($val['nombre']) == trim($subactividad) && $val['ano'] == $ano)
		{
			return $val['valor'];
		}
	}

	return 0;
}

function returnCategoriaAno($arr,$categoria,$ano)
{

	foreach($arr as $val)
	{
		if(trim($val['nombre']) == trim($categoria) && $val['ano'] == $ano)
		{
			return $val['valor'];
		}
	}

	return 0;
}