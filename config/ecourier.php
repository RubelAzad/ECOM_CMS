<?php

return [
    "sandbox"    => env("ECOURIER_SANDBOX", false), // for sandbox mode use true
        "app_key"    => env("ECOURIER_API_KEY", ""),
        "app_secret" => env("ECOURIER_API_SECRET", ""),
        "user_id"    => env("ECOURIER_USER_ID", "")
];
