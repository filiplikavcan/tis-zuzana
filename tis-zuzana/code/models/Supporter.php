<?php

use Nette\Utils\Random;

class Supporter extends DataObject
{
    private static $db = array(
        'Email' => 'Varchar(100)',
        'FirstName' => 'Varchar(100)',
        'LastName' => 'Varchar(100)',
        'City' => 'Varchar(100)',
        'Country' => 'Varchar(100)',
        'Show' => 'Boolean',

        'ConfirmationHash' => 'Varchar(100)',
        'ConfirmedEmail' => 'Varchar(100)'
    );

    private static $indexes = array(
        'ConfirmationHash' => 'unique("ConfirmationHash")'
    );

    public function sendConfirmationEmail()
    {
        $email = new PHPMailer();

        $email->isSMTP();

        //$email->SMTPDebug = 3;

        $email->CharSet = 'utf-8';
        $email->Host = SMTP_HOST;
        $email->SMTPAuth = true;
        $email->Username = SMTP_LOGIN;
        $email->Password = SMTP_PASSWORD;
        $email->SMTPSecure = SMTP_PROTOCOL;
        $email->Port = SMTP_PORT;

        $email->From = 'info@odpovedztezuzane.sk';
        $email->FromName = 'Odpovedzte Zuzane';

        $email->addAddress($this->Email);

        $email->Subject = 'Odpovedzte Zuzane: Overenie emailovej adresy';
        $email->Body = "Dobrý deň\n\n";
        $email->Body .= "ďakujeme za Vašu podporu. Prosím kliknite na (alebo skopírujte ručne do prehliadača) tento link, aby ste tým overili Vašu emailovú adresu.\n\n";
        $email->Body .= $this->getConfirmationLink() . "\n\n";
        $email->Body .= "Transparency Internation SK";

        $email->send();
    }

    public static function confirmedEmailByHash($hash)
    {
        $supporter = static::get()->filter(array('ConfirmationHash' => $hash))->first();

        if ($supporter instanceof Supporter && $supporter->LastEdited > date('Y-m-d H:i:s', time() - (24 * 3600 * 7)))
        {
            $supporter->ConfirmedEmail = $supporter->Email;
            $supporter->write();

            return true;
        }
        else
        {
            return false;
        }
    }

    protected function getConfirmationLink()
    {
        return MAIL_BASE_URL . 'home/confirm/' . $this->generateConfirmationHash();
    }

    protected function generateConfirmationHash()
    {
        $hash = Random::generate(10);

        try
        {
            $this->ConfirmationHash = $hash;
            $this->write();

            return $this->ConfirmationHash;
        }
        catch (SS_DatabaseException $e)
        {
            if (strpos($e->getMessage(), 'Duplicate entry'))
            {
                echo $this->generateConfirmationHash();
            }
            else
            {
                throw $e;
            }
        }
    }
}