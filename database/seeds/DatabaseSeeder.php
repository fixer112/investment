<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

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
            'email' => 'abula@gmail.com',
            'password' => bcrypt('abula'),
            'role' => 'admin',
            'active' => 1,
            'mentor' => '12345',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);

        /*DB::table('users')->insert([
            'name' => 'HoneyPay Test',
            'email' => 'abula3003@gmail.com',
            'password' => bcrypt('abula'),
            'role' => 'cus',
            'referral' => '12345',
            'number' => '08106813749',
            'mode_id' => 'idcard',
            'identity' => '/images/abula1@gmail.com-identity.jpg',
            'acc_no' => '01211297977',
            'acc_name' => 'Abubakar Lawal',
            'bank_name' => 'Gtbank',
            'addr' => 'no 13 ifelodun',
            'city' => 'abeokuta',
            'state' => 'ogun',
            'verify' => '3rtpmMdWvSmRsjCtGccE',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
             ]);*/
    }
}
