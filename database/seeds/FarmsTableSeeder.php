<?php

use Illuminate\Database\Seeder;
use App\Farm;

class FarmsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $farm = new Farm();
        $farm->user_id = 1;
        $farm->plant_id = 3;
        $farm->plantname = '皇宮菜';
        $farm->startdate = '2016-10-06';
        $farm->save();

        $farm = new Farm();
        $farm->user_id = 1;
        $farm->plant_id = 0;
        $farm->plantname = '地瓜葉';
        $farm->startdate = '2016-09-14';
        $farm->enddate = '2016-10-05';
        $farm->save();

        $farm = new Farm();
        $farm->user_id = 1;
        $farm->plant_id = 1;
        $farm->plantname = '韭菜';
        $farm->startdate = '2016-11-30';
        $farm->save();

        $farm = new Farm();
        $farm->user_id = 2;
        $farm->plant_id = 1;
        $farm->plantname = 'Allium';
        $farm->startdate = date('Y-m-d');
        $farm->save();

        $farm = new Farm();
        $farm->user_id = 2;
        $farm->plant_id = 0;
        $farm->plantname = 'GuGu';
        $farm->startdate = date('Y-m-d');
        $farm->save();
    }
}
