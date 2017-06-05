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

    public static function returnSectorAno($arr, $sector, $year)
    {
        while ($val = $arr->next()) {
            if ($val['sector'] == $sector && $val['year'] == $year) {
                return $val['total'];
            }
        }
        
        return 0;
    }

    public static function returnSubactividadAno($arr, $subactivity, $year)
    {
        foreach ($arr as $val) {
            if (trim($val['name']) == trim($subactivity) && $val['year'] == $year) {
                return $val['value'];
            }
        }

        return 0;
    }

    public static function returnCategoriaAno($arr, $category, $year)
    {
        while ($val = $arr->next()) {
            if (trim($val['name']) == trim($category) && $val['year'] == $year) {
                return $val['value'];
            }
        }

        return 0;
    }
}
