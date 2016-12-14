<?php

class VipSupporter extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(255)',
        'Description' => 'Text',
        'Link' => 'Varchar(1000)',
        'Sort' => 'Int',
    );

    private static $summary_fields = array(
        'Title' => 'Názov',
    );

    //private static $default_sort = 'Sort';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Sort');

        return $fields;
    }
}