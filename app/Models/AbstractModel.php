<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    public const DATATYPE_INT = 'integer';
    public const DATATYPE_BOOLEAN = 'boolean';
    public const DATATYPE_STRING = 'string';
    public const DATATYPE_FLOAT = 'float';
    public const DATATYPE_JSON = 'json';
    public const DATATYPE_ARRAY = 'array';
    public const DATATYPE_DATE = 'date';
    public const DATATYPE_DATETIME = 'datetime';
    public const DATATYPE_TIMESTAMP = 'timestamp';
    public const DATATYPE_PG_ARRAY = 'pgarray';
    public const DATATYPE_PG_DECIMAL = 'pgdecimal';
}
