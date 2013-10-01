<?php namespace Skovachev\Notifier\Notifiers;

use Mail;

class EmailNotifier extends Notifier
{
    public function getNotifierKey()
    {
        return 'email';
    }

    public function prepareDestination($destination)
    {
        $destination['from_email'] = $this->getOption('from_email');
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
            
            $message->to($email, $name)->subject($subject);

            if (!is_null($from_email))
            {
                $message->from($from_email);
            }
        });
    }
}