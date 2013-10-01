<?php namespace Skovachev\Notifier\Notifiers;

use View;
use Services_Twilio;
use Log;

class SMSNotifier extends Notifier
{
    private $client;

    public function __construct()
    {
        $sid = $this->getOption('twilio.sid');
        $token = $this->getOption('twilio.token');

        $this->client = new Services_Twilio($sid, $token);
    }

    public function getNotifierKey()
    {
        return 'sms';
    }

    public function prepareDestination($destination)
    {
        $destination['phone'] = $this->obtainUserInfo('phone');
        return $destination;
    }

    public function sendNotification($destination, $view, $data)
    {
        try
        {
            $servicePhone = $this->getOption('twilio.phone_number');
            $phone = array_get($destination, 'phone');
            $content = View::make($view, $data);

            $sms_message = $this->client->account->sms_messages->create($servicePhone, $phone, $content->render());
        } 
        catch (\Exception $e)
        {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}