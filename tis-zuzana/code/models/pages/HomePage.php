<?php

class HomePage extends Page
{
    private static $db = array(
        'QuestionAnsweredCount' => 'Int',
        'AboutTitle' => 'Varchar(1000)',

        'QuestionsTitle' => 'Varchar(1000)',
        'QuestionsContent' => 'HTMLText',

        'SupportTitle' => 'Varchar(1000)',
        'SupportContent' => 'HTMLText',

        'GoalsTitle' => 'Varchar(1000)',
        'GoalsContent' => 'HTMLText',

        'ThankYouTitle' => 'Varchar(1000)',
        'ThankYouContent' => 'HTMLText',

        'EmailTemplate' => 'Text',
    );

    private static $has_many = array(
        'Questions' => 'Question',
        'Goals' => 'Goal',
    );

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->addFieldToTab('Root.Main', new NumericField('QuestionAnsweredCount', 'Počet zodpovedaných otázok'), 'Content');
        $fields->addFieldToTab('Root.Main', new TextField('AboutTitle', 'Nadpis sekcie s popisom kauzy'), 'Content');

        $fields->addFieldToTab('Root.Otázky', new TextField('QuestionsTitle', 'Nadpis sekcie s otázkami'));
        $fields->addFieldToTab('Root.Otázky', new HtmlEditorField('QuestionsContent', 'Obsah sekcie s otázkami'));
        $fields->addFieldToTab('Root.Otázky', new GridField('Questions', 'Questions', $this->owner->Questions(), $this->getGridFieldConfig()));

        $fields->addFieldToTab('Root.Formulár', new TextField('SupportTitle', 'Nadpis sekcie s formulárom'));
        $fields->addFieldToTab('Root.Formulár', new HtmlEditorField('SupportContent', 'Obsah sekcie s formulárom'));
        $fields->addFieldToTab('Root.Formulár', new TextField('ThankYouTitle', 'Ďakovný nadpis'));
        $fields->addFieldToTab('Root.Formulár', new HtmlEditorField('ThankYouContent', 'Ďakovný obsah'));

        $fields->addFieldToTab('Root.Ciele', new TextField('GoalsTitle', 'Nadpis sekcie s cieľmi'));
        $fields->addFieldToTab('Root.Ciele', new HtmlEditorField('GoalsContent', 'Obsah sekcie s cieľmi'));
        $fields->addFieldToTab('Root.Ciele', new GridField('Goals', 'Goals', $this->owner->Goals(), $this->getGridFieldConfig()));

        $fields->addFieldToTab('Root.Email', new TextareaField('EmailTemplate', 'Šablóna emailu'));

        $fields->renameField('Content', 'Popis kauzy');

        return $fields;
    }

    public function getQuestions()
    {
        return $this->Questions()->Sort('Sort');
    }

    public function getGoals()
    {
        return $this->Goals()->Sort('Sort');
    }

    public function getAboutHash()
    {
        return \Nette\Utils\Strings::webalize($this->AboutTitle);
    }

    public function getQuestionsHash()
    {
        return \Nette\Utils\Strings::webalize($this->QuestionsTitle);
    }

    public function getSupportHash()
    {
        return \Nette\Utils\Strings::webalize($this->SupportTitle);
    }
}
