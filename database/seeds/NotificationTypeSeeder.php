<?php

use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('notification_types')->insert([
			'name' => 'new_message',
			'icon' => 'far fa-envelope',
		]);
		DB::table('notification_types')->insert([
			'name' => 'change_data',
			'icon' => 'fas fa-exchange-alt',
		]);
    }
}
