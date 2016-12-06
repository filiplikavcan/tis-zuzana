<?php

class SupporterFormFactory extends BaseFormFactory
{
    public function create() {
        $form = parent::create();
        //$form->enableReCaptcha();

        $form->addText('Email', 'Email*')
            ->addRule(Application\Form::EMAIL, 'A valid emaill address is required.')
            ->setRequired(true);
        $form->addText('FirstName', 'First Name*');
        $form->addText('LastName', 'Last Name*');

        $submit = $form->addSubmit('submit', 'Send')->setAttribute('class', 'btn');

        $submit->onClick[] = array($this, 'onSubmit');

        return $form;
    }

    public function onSubmit(Nette\Forms\Controls\SubmitButton $button)
    {
        /**
         * @var $form \Application\Form
         */
        $form = $button->getForm();        
        
        if ($form->isValid())
        {
            $data = $form->getValues();

            $form_data = new ContactFormData();

            $form_data->loadFromForm($data);

            $form_data->SourceName = $form->getSourceName();
            $form_data->NotificationEmailAddress = $this->site_config->NotificationEmailAddress;

            $form_data->write();
        }
        else
        {
            $form->cleanErrors();
            $form->addError('Please make sure all fields are filled out properly.');
        }
    }
}