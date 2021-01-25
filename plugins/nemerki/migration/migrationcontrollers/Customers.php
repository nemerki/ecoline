<?php

namespace Nemerki\Migration\MigrationControllers;

use Nemerki\API\Classes\ApiController;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;

class Customers extends ApiController
{
    public function index()
    {
        ini_set('memory_limit', '-1');


        //SET TOKEN
        $api_token = "671fadfabdd45bc0dbc4c11405a075562a6f5c61";

        //URL FOR GET PRODUCTS
        $url = "https://cleancloudapp.com/api/getCustomer";

        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        $setting = \Nemerki\Data\Models\Setting::find(1);

        for ($i = $setting->last_user_id; $i <= 1000000000; $i++) {
            set_time_limit(300);

            $jsonArray = array("api_token" => $api_token, "customerID" => $i);

            $postFields = json_encode($jsonArray);

            //SEND TO CLEANCLOUD API USING CURL
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

            $response = curl_exec($ch);
            curl_close($ch);
            $json_response = json_decode($response);

            if (isset($json_response->Error)) {
                //ERROR WITH DATA SENT TO CLEANCLOUD
                echo "Done Last user ID: " . $i - 1;
                break;

            } else {


                $isUser =  User::where('app_id', $i)->first();
                //SHOW RESPONSE IN JSON
                if ($isUser) {

                } else {
                    $user = Auth::register([
                        'name' => $json_response->Name,
                        'email' => $json_response->ID . '@mail.com',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                        'phone' => $json_response->Tel,
                        'address' => $json_response->Address,
                        'app_id' => $json_response->ID,
                        'lat' => $json_response->Lat,
                        'lng' => $json_response->Lng,
                        'notes' => $json_response->Notes,
                        'private_notes' => $json_response->privateNotes,
                        'subscription' => $json_response->Subscription,
                        'discount_percentage' => $json_response->discountPercentage,
                        'credit_available' => $json_response->creditAvailable,
                        'priceList_id' => $json_response->priceListID,
                        'qr' => $json_response->qr,

                    ], true);
                    $setting->update(['last_user_id' => $i]);
                }


            }
        }

        return 'succes';
    }


}

