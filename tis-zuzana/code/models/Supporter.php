<?php

class Supporter extends DataObject
{
    private static $db = array(
        'Email' => 'Varchar(100)',
        'FirstName' => 'Varchar(100)',
        'LastName' => 'Varchar(100)',
        'City' => 'Varchar(100)',
        'Hide' => 'Boolean',

        'ConfirmedEmail' => 'Varchar(100)'
    );
}