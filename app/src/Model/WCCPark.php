<?php

namespace Doggo\Model;

use SilverStripe\ORM\DataObject;

class WCCPark extends Park{

    private static $table_name = 'WCCPark';

    private static $singular_name = "Wellington City Council";

    private static $db = [
        'ProviderCode' => 'Varchar(100)'
    ];

}