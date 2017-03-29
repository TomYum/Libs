<?php

/**
 * Конвертирует стиль написания строки
 * StudlyCaps <=> camelCase <=> snake_case
 *
 * @author Tom.Yum. <Artem Khmilevsky hart2005@gmail.com>
 */
class CaseConverter
{
    public static function snakeToCamel($value){
        return preg_replace_callback('/[_]([a-z])/', function($val){
            return strtoupper($val[1]);
        }, $value);
    }

    public static function snakeToStudly($value){
        return ucfirst(self::snakeToCamel($value));
    }

    public static function camelToSnake($value){
        return preg_replace_callback('/(?<=[a-z])[A-Z]/', function($val){
            return '_'.strtolower($val[0]);
        }, $value);
    }

    public static function camelToStudly($value){
        return ucfirst($value);
    }

    public static function studlyToSnake($value){
        return self::camelToSnake(lcfirst($value));
    }

    public static function studlyToCamel($value){
        return lcfirst($value);
    }
}
