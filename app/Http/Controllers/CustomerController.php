<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Provider;
use App\Models\User;
use App\ServiceProviders\ActionType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    var $user;
    var $provider_credentials;
    var $provider;
    var $state;

    public function __construct(Request $request)
    {

        $api_key = $request->header("api-key");
        $user =  User::where("api_key", $api_key)->first();
        $this->user = $user;
        $this->provider =  Provider::where("active", 1)->first();
        if ($this->provider) {
            $this->provider_credentials =  config("providers." . strtolower($this->provider->name));
            $this->state =  new ActionType($this->provider->name, $this->provider_credentials);
        }
    }

    public function createCustomer(Request $request)
    {

        $this->validate($request, config('rules.create_customer'));

        if ($request->type === 'individual') {
            $this->validate($request, config('rules.create_customer'));
        }

        $response = $this->state->execute()->createCustomer($request);

        if ($this->provider->name == "SUDO") {
            $response["data"]["customer_id"] = $response["data"]["_id"];
            unset($response["data"]["_id"]);
        }
     

        if ($response["status"] == "success") {
            $d_simp = $response["data"];
            $to_save = [
                "name" => $d_simp["name"],
                "business" => $d_simp["business"],
                "status" => $d_simp["status"],
                "type" => $d_simp["type"],
                "individual" => isset($d_simp["individual"]) ? json_encode($d_simp["individual"]) : null,
                "billingAddress" => json_encode($d_simp["billingAddress"]),
                "company" => isset($d_simp["company"]) ? json_encode($d_simp["company"]) : null,
                "third_party_id" => $d_simp["customer_id"],
                "user_id" => $this->user->id,
                "created_on" => $this->provider->name
            ];

            $this->saveData($to_save, Customer::class);
        }


        return  handle_response($response["message"],  $response["code"], $response["status"], $response["data"] ?? null,);
    }

    public function getCustomers()
    {
        $customers = Customer::where('user_id', $this->user->id)
            ->select('id', 'name', 'type', 'status', 'billingAddress', 'individual', 'company', 'third_party_id AS customer_id')
            ->paginate(10);


        foreach ($customers as $value) {
            if (!is_null($value->individual)) {
                $value->individual = json_decode($value->individual);
            }
            if (!is_null($value->company)) {
                $value->company = json_decode($value->company);
            }
            if (!is_null($value->billingAddress)) {
                $value->billingAddress = json_decode($value->billingAddress);
            }
        }

        return handle_response("Customers fetched successfully.", 200, "success", $customers);
    }

    public function getCustomer(Request $request)
    {
        $customer = Customer::where("third_party_id", $request->customer_id)
            ->select('id', 'name', 'type', 'status', 'billingAddress', 'individual', 'company', 'third_party_id AS customer_id')
            ->first();

        if (!is_null($customer->individual)) {
            $customer->individual = json_decode($customer->individual);
        }
        if (!is_null($customer->company)) {
            $customer->company = json_decode($customer->company);
        }
        if (!is_null($customer->billingAddress)) {
            $customer->billingAddress = json_decode($customer->billingAddress);
        }

        return handle_response("Customer fetched successfully.", 200, "success", $customer);
    }
}
