<?php namespace Skovachev\Notifier\Notifiers;

use View;
use Services_Twilio;
use Log;

class SMSNotifier extends Notifier
{
    private $client;

    public function __construct($twilioClient = null)
    {
        if (is_null($twilioClient))
        {
            $sid = $this->getOption('twilio.sid');
            $token = $this->getOption('twilio.token');
            $twilioClient = new Services_Twilio($sid, $token);
        }

        $this->client = $twilioClient;
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