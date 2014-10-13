<?php namespace Skovachev\Notifier\Notifiers;

use Illuminate\Support\Facades\Mail;

class EmailNotifier extends Notifier
{
    public function getNotifierKey()
    {
        return 'email';
    }

    public function prepareDestination($destination)
    {
        $destination['from_email'] = $this->getOption('from_email');
        $destination['cc_email'] = $this->getOption('cc_email');
        $destination['bcc_email'] = $this->getOption('bcc_email');
        $destination['email'] = $this->obtainUserInfo('email');
        $destination['name'] = $this->obtainUserInfo('name');

        return $destination;
    }

    public function sendNotification($destination, $view, $data)
    {
        Mail::queue($view, $data, function($message) use ($destination)
        {
            $email = array_get($destination, 'email');
            $name = array_get($destination, 'name');
            $subject = array_get($destination, 'subject');
            $from_email = array_get($destination, 'from_email');
            $cc_email = array_get($destination, 'cc_email');
            $bcc_email = array_get($destination, 'bcc_email');

            $message->to($email, $name)->subject($subject);

            if (!is_null($from_email))
            {
                $message->from($from_email);
            }

            if (!is_null($bcc_email))
            {
                $message->bcc($bcc_email);
            }

            if (!is_null($cc_email))
            {
                $message->cc($cc_email);
            }
        });
    }
}