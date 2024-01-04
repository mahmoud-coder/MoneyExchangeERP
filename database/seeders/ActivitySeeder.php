<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('activities')->insert([
            ['activity' => 'retail'], 
            ['activity' => 'financial services'], 
            ['activity' => 'jewelry'], 
            ['activity' => 'trust fund'], 
            ['activity' => 'gambling'], 
            ['activity' => 'caffe/restaurant'], 
            ['activity' => 'hair cut/beauty center'], 
            ['activity' => 'agriculture production'], 
            ['activity' => 'industrial production'], 
            ['activity' => 'others'], 
        ]);
    }
}
