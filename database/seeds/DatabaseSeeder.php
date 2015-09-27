<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        factory(App\Step::class, 10)->create();
        factory(App\Emailstat::class, 10)->create();
        factory(App\Date::class, 10)->create();
        Model::reguard();
    }
}
