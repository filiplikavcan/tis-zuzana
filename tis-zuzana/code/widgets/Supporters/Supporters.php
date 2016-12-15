<?php

namespace TisZuzana\Widgets\Supporters;

use Cleopas\Widgets\IRequestable;
use Cleopas\Widgets\Widget;

class Supporters extends Widget implements IRequestable
{
    const TWO_HOURS_IN_SECONDS = 7200;
    protected $is_data_cached = false;

    protected $page_size = 100;
    protected $title = '';

    public function getAllSupportersCount()
    {
        return \Supporter::confirmed()->count();
    }

    public function getSupporters()
    {
        $suporters = \Supporter::confirmed()
            ->where('Name IS NOT NULL AND `Show` = 1')
            ->limit($this->page_size);

        if ($this->param('for_hp', 'false') == 'true')
        {
            return $suporters
                ->filter(array('LastEdited:LessThan' => date('Y-m-d H:i:s', time() - self::TWO_HOURS_IN_SECONDS)))
                ->sort('RAND()');
        }
        else
        {
            return $suporters
                ->filter(array('ConfirmationID:LessThan' => $this->getLastConfirmationID()))
                ->sort('ConfirmationID DESC');
        }
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
}