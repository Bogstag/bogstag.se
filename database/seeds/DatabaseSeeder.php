<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Step::class, 10)->create();
        factory(App\Emailstat::class, 10)->create();
        factory(App\Date::class, 10)->create();
        factory(App\Emaildrop::class, 10)->create();
    }
}
