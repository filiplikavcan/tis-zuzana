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

    protected $cached_image = null;

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('Sort');

        return $fields;
    }

    public function getImageUrl()
    {
        $image = $this->getImage();
        return $image->exists() ? $image->getModifiedLink(array('w' => 500)) : '';
    }

    public function getImageTopPadding()
    {
        $image = $this->getImage();

        return $image->exists() ? ($image->getHeight() / $image->getWidth()) * 100 : 0;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        if (is_null($this->cached_image))
        {
            $this->cached_image = $this->Image();
        }

        return $this->cached_image;
    }
}