<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\User;
use App\ServiceProviders\ActionType;
use Illuminate\Http\Request;

class AccountController extends Controller
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
    public function createAccount(Request $request)
    {
        $this->validate($request, config('rules.create_account'));

        $customer = Customer::where('third_party_id', $request->customer_id)->first();
        if (!$customer) {
            return handle_response("Customer not found", 404, 'error');
        }

        /*
          Check if the customer was registered with active Provider
        */
        if ($customer->created_on != $this->provider->name) {
            return error("Kindly Register Customer on our new Provider");
        }

        $response = $this->state->execute()->createAccount($request);


        if ($this->provider->name == "SUDO") {
            $response["data"]["account_id"] = $response["data"]["_id"];
            unset($response["data"]["_id"]);
        }


        if ($response["status"] == "success") {
            $d_simp = $response["data"];
            $to_save = [
                "business" => $d_simp["business"],
                "customer" => $d_simp["customer"],
                "type" => $d_simp["type"],
                "currency" => $d_simp["currency"],
                "accountName" => $d_simp["accountName"],
                "bankCode" => $d_simp["bankCode"],
                "accountType" => $d_simp["accountType"],
                "accountNumber" => $d_simp["accountNumber"],
                "currentBalance" => $d_simp["currentBalance"],
                "availableBalance" => $d_simp["availableBalance"],
                "provider" => $d_simp["provider"],
                "referenceCode" => $d_simp["referenceCode"],
                "providerReference" => $d_simp["providerReference"],
                "isDefault" => $d_simp["isDefault"],
                "third_party_id" => $d_simp["account_id"],
                "user_id" => $this->user->id,
                "created_on" => $this->provider->name
            ];

            $this->saveData($to_save, Account::class);
        }


        return  handle_response($response["message"],  $response["code"], $response["status"], $response["data"] ?? null,);
    }

    public function getCustomerAccount(){
        
    }
}
