<?php

use Illuminate\Database\Seeder;
use App\Customer;
use App\Opportunity;
use App\AccountPlan;
use App\Product;


class OpportunitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers=Customer::whereIn('lob_id',[3,4])->get();
        $products=Product::get();
        $productCount=count($products);
        $i=0;
        foreach ($customers as $customer) {
            $accountPlan=AccountPlan::where([['customer_id', '=', $customer['id']],['active','=',1]])->first();

            //create an account plan if it does not exist
            if ($accountPlan==null) {
                $accountPlan = new AccountPlan;
                $accountPlan['customer_id']=$customer['id'];
                $accountPlan['active']=1;
                $accountPlan->save();
            }

            $oppCount= rand(1,10);
            for($j=0; $j<$oppCount; $j++) {
                //{"status": "Rejected", "productId": "1", "customerId": "1", "plannedDate": "null", "productName": "Vadesiz Mevduat", "rejectReason": "Does not use this product", "opportunityId": "1", "realizationDate": null}
                $opp=new Opportunity();
                $opp['accountPlan_id']=$accountPlan['id'];
                $opp->save();
                $data=[];
                $data['opportunityId']=$opp['id'];
                $data['status']="Recommended";
                $data['customerId']=$accountPlan['customer_id'];
                $data['plannedDate']=null;
                $prodId=rand(0,$productCount-1);
                $data['productId']=$products[$prodId]['id'];
                $data['productName']=$products[$prodId]['name'];
                $opp['parameters']=json_encode($data);
                $opp->save();
            }
            $i++;
            if ($i%1000==0)
                echo $i.' records processed' . PHP_EOL;
        }
    }
}
