<?php

namespace App\ServiceProviders\Sudo;

class SUDO
{

    var $api_key;
    var $base_url;

    function __construct($config)
    {
        $this->api_key =  $config["api_key"];
        $this->base_url = $config["base_url"];
    }

    private function api_call($endpoint, $payload = null,)
    {

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key
        ];

        $data = json_encode($payload);

        $curl = curl_init();
        $opt_array =  array(
            CURLOPT_URL => $this->base_url . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $headers
        );
        if ($payload) {
            $opt_array[CURLOPT_POSTFIELDS] = $data;
            $opt_array[CURLOPT_CUSTOMREQUEST] = 'POST';
        }

        curl_setopt_array($curl, $opt_array);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public function createCustomer($data)
    {
        $endpoint = '/customers';

        $payload = (object) [
            "type" => $data->type,
            "name" => $data->name,
            "status" => $data->status,
            "billingAddress" => (object) [
                "line1" => $data->line1,
                "line2" => $data->line2 ?? '',
                "city" => $data->city,
                "state" => $data->state,
                "country" => $data->country,
                "postalCode" => $data->postalCode,
            ]
        ];

        switch ($payload->type) {
            case 'individual':
                $individual = (object)[
                    "firstName" => $data->firstName,
                    "lastName" => $data->lastName,
                    "emailAddress" => $data->emailAddress ?? '',
                    "phoneNumber" => $data->phoneNumber ?? '',
                ];

                $payload->individual = $individual;
                break;
            case 'company':
                $company = (object)[
                    "name" => $data->name
                ];
                $payload->company = $company;
                if (isset($data->officer)) {
                    $payload->officer = $data->officer;
                }
                break;
        }

        if (isset($data->identity)) {
            $payload->identity = $data->identity;
        }
        if (isset($data->documents)) {
            $payload->documents = $data->documents;
        }



        $response = $this->api_call($endpoint, $payload);


        $response = json_decode($response, true);

        $data = $this->process_sudo_code($response);



        return $data;
    }

    public function createAccount($data)
    {
        $endpoint = '/accounts';

        $payload = (object) [
            "type" => $data->type,
            "currency" => $data->currency,
            "accountType" => $data->accountType,
            "customerId" => $data->customer_id
        ];

        $response = $this->api_call($endpoint, $payload);

        $response = json_decode($response, true);

        $data = $this->process_sudo_code($response);


        return $data;
    }

    public function getAccount($id)
    {
        $endpoint = '/accounts/' . $id;
        $response = $this->api_call($endpoint);
        $response = json_decode($response, true);

        $data = $this->process_sudo_code($response);


        return $data;
    }

    public function getCustomer($id)
    {
        $endpoint = '/customers/' . $id;
        $response = $this->api_call($endpoint);
        $response = json_decode($response, true);

        $data = $this->process_sudo_code($response);


        return $data;
    }

    public function process_sudo_code($response)
    {

        $bad_request_user = [
            400, 402, 409, 429
        ];

        $bad_request_sudo = [
            401, 403, 404, 500, 502, 503, 504
        ];

        $data = [];

        if ($response["statusCode"] == 200) {
            $data["status"] = "success";
            $data["message"] = $response["message"];
            $data["code"] = $response["statusCode"];
        } else if (in_array($response["statusCode"], $bad_request_user)) {
            $data["status"] = "error";
            $data["message"] = $response["message"];
            $data["code"] = $response["statusCode"];
        } else if (in_array($response["statusCode"], $bad_request_sudo)) {
            $data["status"] = "error";
            $data["message"] = "Something went wrong , Try again.";
            $data["code"] = $response["statusCode"];
        }

        if (isset($response["data"])) {
            $response = $response["data"];

            unset($response["message"]);
            unset($response["isDeleted"]);
            unset($response["createdAt"]);
            unset($response["updatedAt"]);
            unset($response["statusCode"]);
            unset($response["statusCode"]);
            unset($response["__v"]);

            $data["data"] = $response;
        }
        return $data;
    }
}
