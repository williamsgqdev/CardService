<?php

return
    [

        "create_customer" => [
            "name" => "required",
            "type" => "required|in:individual,company",
            "phoneNumber" => "required",
            "status" => "required|in:active,inactive",
            "line1" => "required",
            "city" => "required",
            "state" => "required",
            "postalCode" => "required",
            "country" => "required",
        ],
        "individual" => [
            "firstName" => "required",
            "lastName" => "required",
            "dob" => "required",
        ],
        "create_account" => [
            "type" => "required|in:account,wallet",
            "currency" => "required|in:NGN,USD",
            "accountType" => "required|in:Savings,Current",
            "customer_id" => "required"
        ],
        "get_customer_accounts" => [
            "customer_id" => "required"
        ],
        "card_request" => [
            "customer_id" => "required",
            "account_id" => "required",
            "type" => "required|in:physical,virtual",
            "brand" => "required|in:Verve,MasterCard,Visa",
            "currency" => "required|in:NGN,USD",
            "issuerCountry" => "required|in:NGA,USA",
            "status" => "required|in:active,inactive",
        ]
    ];
