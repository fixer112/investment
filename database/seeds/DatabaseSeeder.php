<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            'name' => 'HoneyPay Admin 1',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('abula112'),
            'role' => 'admin',
            'active' => 1,
            'number' => '05172303511',
            'mentor' => '05172303511',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'HoneyPay Test',
            'email' => 'user@gmail.com',
            'password' => bcrypt('abula112'),
            'role' => 'cus',
            'active' => '1',
            'referal' => '12345',
            'number' => '05172303511',
            'mode_id' => 'idcard',
            'identity' => '/images/abula3003@gmail.com-identity.jpg',
            'acc_no' => '01211297977',
            'acc_name' => 'Abubakar Lawal',
            'bank_name' => 'Gtbank',
            'addr' => 'no 13 ifelodun',
            'city' => 'abeokuta',
            'state' => 'ogun',
            'id_change' => '2',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

/*
DB::table('historys')->insert([
'invest_amount' => 10000,
'tenure' => 30,
'invest_date' => Carbon::now(),
'return_date' => Carbon::createFromFormat('d/m/Y', '30/05/2018'),
'return_amount' => 10500,
'status' => 'active',
'user_id' => 2,
'tran_id' => '1019921',
'approved_date' => Carbon::now()->addWeekdays(4),
'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
]);

DB::table('historys')->insert([
'invest_amount' => 20000,
'tenure' => 90,
'invest_date' => Carbon::now(),
'return_date' => Carbon::createFromFormat('d/m/Y', '30/05/2018'),
'return_amount' => 40500,
'status' => 'active',
'approved_date' => Carbon::now()->addWeekdays(3),
'user_id' => 2,
'tran_id' => '10199212',
'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
]);

DB::table('historys')->insert([
'invest_amount' => 20000,
'tenure' => 90,
'invest_date' => Carbon::now(),
'tran_id' => '101900212',
'return_date' => Carbon::createFromFormat('d/m/Y', '30/05/2018'),
'return_amount' => 40500,
'status' => 'pending',
'approved_date' => Carbon::now()->addWeekdays(7),
'user_id' => 2,
'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
]);

DB::table('historys')->insert([
'invest_amount' => 30000,
'tenure' => 90,
'invest_date' => Carbon::createFromFormat('d/m/Y', '01/05/2018'),
'return_date' => Carbon::createFromFormat('d/m/Y', '02/05/2018'),
'return_amount' => 80500,
'status' => 'paid',
'paid_date' => Carbon::createFromFormat('d/m/Y', '03/03/2018'),
'approved_date' => Carbon::now()->addWeekdays(2),
'user_id' => 2,
'tran_id' => '10169212',
'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
]);

DB::table('historys')->insert([
'invest_amount' => 30000,
'tenure' => 90,
'invest_date' => Carbon::now(),
'return_date' => Carbon::createFromFormat('d/m/Y', '30/06/2018'),
'return_amount' => 80500,
'status' => 'reject',
'approved_date' => Carbon::now()->addWeekdays(2),
'user_id' => 2,
'tran_id' => '101692126',
'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
]);*/
    }
}