<?php

class MenuAdmin extends ModelAdmin
{
    private static $managed_models = array(
        'MenuItem'
    );

    private static $url_segment = 'menu';
    private static $menu_title = 'Menu';

    function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm();

        $gridFieldConfig = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass))->getConfig();

        $gridFieldConfig->addComponent(new GridFieldOrderableRows('SortOrder'));

        return $form;
    }
}