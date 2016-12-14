<?php

namespace TisZuzana\Widgets\Supporters;

use Cleopas\Widgets\IRequestable;
use Cleopas\Widgets\Widget;

class Supporters extends Widget implements IRequestable
{
    protected $is_data_cached = false;

    protected $page_size = 100;
    protected $title = '';

    public function getAllSupportersCount()
    {
        return \Supporter::confirmed()->count();
    }

    public function getSupporters()
    {
        return \Supporter::confirmed()
            ->filter(array('ConfirmationID:LessThan' => $this->getLastConfirmationID()))
            ->where('Name IS NOT NULL AND `Show` = 1')
            ->sort('ConfirmationID DESC')
            ->limit($this->page_size);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getPageSize()
    {
        return $this->page_size;
    }

    protected function getLastConfirmationID()
    {
        return $this->param('i', PHP_INT_MAX);
    }

    public function getUrl()
    {
        return parent::getUrl();
    }
}