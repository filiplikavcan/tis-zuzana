<?php

class Page extends Cleopas_Pages_Base
{
    private static $can_be_root = true;

    protected function getGridFieldConfig()
    {
        return GridFieldConfig::create()->addComponents(
            new GridFieldToolbarHeader(),
            (new GridFieldAddNewButton('toolbar-header-right')),
            new GridFieldSortableHeader(),
            new GridFieldDataColumns(),
            new GridFieldPaginator(50),
            new GridFieldEditButton(),
            new GridFieldDeleteAction(),
            new GridFieldDetailForm(),
            new GridFieldOrderableRows()
        );
    }
}
