<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnalyticsModelOutputTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert('
insert into analytics_model_outputs (customer_id, product_id, opportunityType, parameters)
select cust.id, prod.id, \'penetration\', JSON_OBJECT(\'probability\',RAND()) from
(select id from customers where lob_id in(3,4)) cust,
(select id from products where penetrationActionEnabled>0) prod    
    ');

        DB::insert('
insert into analytics_model_outputs (customer_id, product_id, opportunityType, parameters)
select cust.id, prod.id, \'volume\', JSON_OBJECT(\'targetVolume\',truncate(rand()*100000000,2)) from
(select id from customers where lob_id in(3,4)) cust,
(select id from products where volumeActionEnabled>0) prod    
    ');

        DB::insert('
insert into analytics_model_outputs (customer_id, product_id, opportunityType, parameters)
select cust.id, prod.id, \'spread\', JSON_OBJECT(\'targetSpread\', truncate(rand()*4,2)) from
(select id from customers where lob_id in(3,4)) cust,
(select id from products where volumeActionEnabled>0) prod    
    ');
    }
}
