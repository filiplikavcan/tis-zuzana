<?php

class Supporter extends DataObject
{
    private static $db = array(
        'Email' => 'Varchar(100)',
        'FirstName' => 'Varchar(100)',
        'LastName' => 'Varchar(100)',
        'Note' => 'Text',
        'Link' => 'Varchar(1000)',
        'ShowInList' => 'Boolean',
    );

    private static $has_one = array(
        'Photo' => 'Image'
    );
}