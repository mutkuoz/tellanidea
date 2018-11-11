<?php

use Illuminate\Database\Seeder;

class KpisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kpis=[
            ['id'=>1,'name'=>'Number of Customers','queryWord'=>'count(id)','calculationQuery'=>null, 'color'=>'#3097D1', 'link'=>'customers' ],
            ['id'=>2,'name'=>'Revenues (YTD)','queryWord'=>'sum(%X%)',
                'calculationQuery'=>'UPDATE customers C left outer join (select customer_id, sum(revenues) as revenues from monthly_datas where product_id=0 and yearMonth>=%MIN_YEARMONTH% and yearMonth<=%MAN_YEARMONTH% group by customer_id) MD on C.id=MD.customer_id SET C.%COLUMN_NAME%= MD.revenues;',
                'color'=>'#8eb4cb'
            ]
        ];
        DB::table('kpis')->insert($kpis);
    }
}
