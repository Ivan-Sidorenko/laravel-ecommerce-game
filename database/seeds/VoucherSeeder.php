<?php

use App\Classes\Cart\VoucherType;
use App\Voucher;
use Illuminate\Database\Seeder;

/**
 * VoucherSeeder
 *
 * @author Alexander Begoon <alexander.begoon@gmail.com>
 */
class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Voucher::truncate();

        Voucher::create([
            'code' => 'test5',
            'type' => VoucherType::PERCENT,
            'amount' => 5
        ]);
        Voucher::create([
            'code' => 'test10',
            'type' => VoucherType::PERCENT,
            'amount' => 10
        ]);
        Voucher::create([
            'code' => 'gift',
            'type' => VoucherType::AMOUNT,
            'amount' => 2
        ]);
    }
}