<?php

class Media extends DataObject
{
    private static $db = array(
        'Title' => 'Varchar(255)',
        'Description' => 'Text',
        'Link' => 'Varchar(1000)',
        'Sort' => 'Int',
    );

    private static $has_one = array(
        'Category' => 'MediaCategory',
        'Image' => 'Image'
    );

    private static $summary_fields = array(
        'Title' => 'Názov',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Sort');

        return $fields;
    }

    public function getImageUrl()
    {
        return $this->Image()->getModifiedLink(array('w' => 500));
    }
}