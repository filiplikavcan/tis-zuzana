<?php

class SupporterFormFactory extends BaseFormFactory
{
    public function create() {
        $form = parent::create();

        $form->addText('Email', 'Email*')
            ->addRule(Application\Form::EMAIL, 'A valid emaill address is required.')
            ->setRequired(true);
        $form->addText('FirstName', 'First Name');
        $form->addText('LastName', 'Last Name');
        $form->addText('City', 'City');
        $form->addCheckbox('Hide', 'Nechcem, aby bolo moje meno zverejnené');

        $submit = $form->addSubmit('submit', 'Odoslať');

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
            if (!$form->isStored())
            {
                $data = $form->getValues();

                $supporter = new Supporter();

                $supporter->Email = $data->Email;
                $supporter->FirstName = $data->FirstName;
                $supporter->LastName = $data->LastName;
                $supporter->City = $data->City;
                $supporter->Hide = $data->Hide;

                $supporter->write();

                //$supporter->confirm();

                $form->markAsStored();
            }
        }
        else
        {
            $form->cleanErrors();
            $form->addError('Please make sure all fields are filled out properly.');
        }
    }
}