<?php

class Page extends Cleopas_Pages_Base
{

}

class Page_Controller extends Cleopas_Controllers_Base
{
    protected $is_data_cached = false;

    public function setupWidgets()
    {
        $this->addWidget('Meta', Cleopas\Widgets\Meta\Basic::create()
            ->setWebsiteTitle(function() {
                return $this->getSiteConfig()->getTitle();
            })
            ->setPageTitle(function() {
                return $this->Title;
            }));

        $this->addWidget('Menu', Cleopas\Widgets\Menu\Basic::create()
            ->setItems(function() {
                return \SiteTree::get()->filter(array('ParentID' => '0', 'ShowInMenus' => '1'));
            }));
    }
}
