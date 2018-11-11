<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthlyDataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //demand deposit TL
        DB::insert('
        insert into monthly_datas (customer_id, product_id, yearMonth, ownership)
select cust.id as customer_id, 1 as product_id, 201712 as yearmonth,
case when rand()>0.08 then 1 else 0 end as ownership from (select id from customers where lob_id in (3,4)) cust;
        ');

        DB::update('
        update monthly_Datas set volume_eop=case when ownership=1 then truncate(rand()*1000000,2) else 0 end where product_id=1;
        ');

        DB::update('
update monthly_Datas set volume_avg=volume_eop*(0.8+rand()*0.4) where product_id=1;
        ');

        DB::update('
update monthly_Datas set revenues=volume_avg*(0.09/12)  where product_id=1; 
 ');


        //demand deposit FX
        DB::insert('
        insert into monthly_datas (customer_id, product_id, yearMonth, ownership)
select cust.id as customer_id, 5 as product_id, 201712 as yearmonth,
case when rand()>0.85 then 1 else 0 end as ownership from (select id from customers where lob_id in (3,4)) cust;
        ');

        DB::update('
        update monthly_Datas set volume_eop=case when ownership=1 then truncate(rand()*5000000,2) else 0 end where product_id=5;
        ');

        DB::update('
update monthly_Datas set volume_avg=volume_eop*(0.8+rand()*0.4) where product_id=5;
        ');

        DB::update('
update monthly_Datas set revenues=volume_avg*(0.035/12)  where product_id=5; 
 ');

        //bch
        DB::insert('
        insert into monthly_datas (customer_id, product_id, yearMonth, ownership)
select cust.id as customer_id, 4 as product_id, 201712 as yearmonth,
case when rand()>0.65 then 1 else 0 end as ownership from (select id from customers where lob_id in (3,4)) cust;
        ');

        DB::update('
        update monthly_Datas set volume_eop=case when ownership=1 then truncate(rand()*15000000,2) else 0 end where product_id=4;
        ');

        DB::update('
update monthly_Datas set volume_avg=volume_eop*(0.8+rand()*0.4) where product_id=4;
        ');

        DB::update('
update monthly_Datas set revenues=volume_avg*(rand()*0.04/12)  where product_id=4; 
 ');
    }
}
