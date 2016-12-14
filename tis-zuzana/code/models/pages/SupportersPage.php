<?php

class SupportersPage extends Page
{
    private static $db = array(
        'VipSupportersTitle' => 'Varchar(255)'
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $config = $this->getGridFieldConfig();
        $config->removeComponentsByType('GridFieldOrderableRows');

        $fields->addFieldToTab('Root.Signatári', new GridField('Supporters', 'Signatári', Supporter::get(), $config));
        $fields->addFieldToTab('Root.Podporovatelia', new TextField('VipSupportersTitle', 'Nadpis sekcie Podporovatelia'));
        $fields->addFieldToTab('Root.Podporovatelia', new GridField('VipSupporters', 'Podporovatelia', VipSupporter::get(), $this->getGridFieldConfig()));
        $fields->removeByName('Content');

        return $fields;
    }
}
