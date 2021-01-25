<?php

namespace Nemerki\Migration\MigrationControllers;

use Nemerki\API\Classes\ApiController;
use Nemerki\Data\Models\Product;
use October\Rain\Support\Facades\Config;

class Products extends ApiController
{
    public function index()
    {
        ini_set('memory_limit', '-1');



        //SET TOKEN
        $api_token = "671fadfabdd45bc0dbc4c11405a075562a6f5c61";

        //URL FOR GET PRODUCTS
        $url = "https://cleancloudapp.com/api/getProducts";

        //PRICE LIST ID (optional)
        $priceListID = "";

        $jsonArray = array("api_token" => $api_token, "priceListID" => $priceListID);
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

        if ($json_response->Success == "True") {

            echo "Success Getting Products: " . $json_response->Success;

            //SHOW RESPONSE IN JSON


            foreach ($json_response->Products as $product) {

                Product::firstOrCreate(
                    ['app_id' => $product->id],
                    [
                        'name' => $product->name,
                        'category_id' => $product->section,
                        'price' => $product->price,
                        'pieces' => $product->pieces,
                    ]
                );
            }
            foreach ($json_response->SectionsMap as $category) {

                \Nemerki\Data\Models\Selectable::firstOrCreate(
                    ['app_id' => $category->id],
                    ['name' => $category->name, 'type_id' => Config::get('constants.type.category')]
                );
            }
            echo 'success';
        } else if ($json_response->Error != "") {

            //ERROR WITH DATA SENT TO CLEANCLOUD
            echo "Error Submitting to API: " . $json_response->Error;
            die();
        }



        return 'succes';
    }


}

