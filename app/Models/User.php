<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $card_number
 * @property \DateTimeImmutable created_at
 * @property \DateTimeImmutable updated_at
 * @property \DateTimeImmutable deleted_at
 * @property string $card
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 */
class User extends AbstractModel
{
    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'first_name',
        'last_name',
        'email',
        'card_number',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'id' => self::DATATYPE_INT,
        'first_name' => self::DATATYPE_STRING,
        'last_name' => self::DATATYPE_STRING,
        'email' => self::DATATYPE_STRING,
        'card_number' => self::DATATYPE_STRING,
        'created_at' => self::DATATYPE_DATETIME,
        'updated_at' => self::DATATYPE_DATETIME,
        'deleted_at' => self::DATATYPE_DATETIME,
    ];

}
