<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::fixTree();

        /*DB::table('strategies')->insert([
            'id' => 1,
            'name' => 'Acquire',
            'color' => '#7FFF7C'
        ]); //Acquire

        DB::table('strategies')->insert([
            'id' => 2,
            'name' => 'Grow',
            'color' => '#3097D1'
        ]); //Gwo

        DB::table('strategies')->insert([
            'id' => 3,
            'name' => 'Nurture',
            'color' => '#FFC063'
        ]); //Acquire

        DB::table('strategies')->insert([
            'id' => 4,
            'name' => 'Recover',
            'color' => '#FF0015'
        ]); //Acquire


        $kpis=[
            ['id'=>1,'name'=>'Revenues'],
            ['id'=>2,'name'=>'Cash SoW'],
            ['id'=>3,'name'=>'Non Cash SoW'],
            ['id'=>4,'name'=>'RoAC'],
            ['id'=>5,'name'=>'ROC'],
            ['id'=>6,'name'=>'RaROC']
        ];

        DB::table('kpis')->insert($kpis);

        $targetKpis=[
            ['kpi_id'=>1, 'accountPlan_id'=>1, 'targetValue'=>10000],
            ['kpi_id'=>2, 'accountPlan_id'=>1, 'targetValue'=>0.15]
        ];

        $accountplans=[
            ['customer_id'=>1,'active'=>true]
        ];

        DB::table('target_kpis')->insert($targetKpis);
        DB::table('account_plans')->insert($accountplans);
        */
    }
}
