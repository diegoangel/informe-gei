<?php

namespace Api\Helper;

final class Utils
{
    public static function returnSectorGas($arr, $sector, $gas)
    {
        foreach ($arr as $val) {
            if ($val['sector'] == $sector && $val['gas'] == $gas) {
                return $val['total'];
            }
        }
        return 0;
    }

    public static function returnSectorAno($arr, $sector, $ano)
    {
        while($val = $arr->next()) {

            if ($val['sector'] == $sector && $val['ano'] == $ano) {

                return $val['total'];
            }
        }
        
        return 0;
    }

    public static function returnSubactividadAno($arr, $subactividad, $ano)
    {
        foreach ($arr as $val) {
            if (trim($val['nombre']) == trim($subactividad) && $val['ano'] == $ano)
            {
                return $val['valor'];
            }
        }

        return 0;
    }

    public static function returnCategoriaAno($arr, $categoria, $ano)
    {
        while($val = $arr->next()) {
            if (trim($val['nombre']) == trim($categoria) && $val['ano'] == $ano) {
                return $val['valor'];
            }
        }

        return 0;
    }
}
