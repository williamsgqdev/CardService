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
        ]
    ];
