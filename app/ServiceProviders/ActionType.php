<?php

namespace App\ServiceProviders;

use App\ServiceProviders\Sudo\SUDO;
use Illuminate\Support\Facades\Log;

class ActionType
{

    const SUDO = "SUDO";
    var $config;
    var $type;



    public function __construct($type, $config = [])
    {
        Log::debug("Action Preference Service Provider: " . $type);
        $this->config = $config;
        $this->type = $type;
    }

    public function execute()
    {
        switch ($this->type) {
            case self::SUDO:
                return new SUDO($this->config);
        }
    }
}
