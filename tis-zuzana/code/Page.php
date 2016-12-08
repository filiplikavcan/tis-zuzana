<?php

class Page extends Cleopas_Pages_Base
{
    private static $can_be_root = true;
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

        $this->addWidget('SocialNetworks', Cleopas\Widgets\SocialNetworks\Basic::create()
            ->setItems(function() {
                return $this->getSiteConfig()->SocialNetworks()->Sort('Sort ASC');
            }));

        $this->addWidget('Copyright', Cleopas\Widgets\Copyright\Basic::create()
            ->setOwnerName('TIS')
            ->setFromYear(2016));
    }

    protected function setupForms()
    {
        $this->addFormWidget('Supporter');
    }
}
