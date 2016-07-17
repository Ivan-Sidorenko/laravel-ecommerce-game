<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

/**
 * Voucher
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class Voucher extends Model
{
    /**
     * @var string
     */
    protected $table = 'vouchers';

    /**
     * @var array
     */
    protected $fillable = [
        'code',
        'type',
        'amount'
    ];
}