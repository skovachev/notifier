<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class SMSNotifierTest extends TestCase 
{

    public function testSendSMSNotification()
    {
        $twilioClient = new stdClass;
        $twilioClient->account = new stdClass;
        $messageService = Mockery::mock('stdClass');
        $twilioClient->account->sms_messages = $messageService;
        $messageService->shouldReceive('create')->once()->with('1234', 'fooUser', 'renderedContent');

        $viewContent = Mockery::mock('stdClass');
        $viewContent->shouldReceive('render')->once()->andReturn('renderedContent');

        $sms = new \Skovachev\Notifier\Notifiers\SMSNotifier($twilioClient);
        
        Config::shouldReceive('get')->once()->with('notifier::sms.enabled')->andReturn(true);
        Config::shouldReceive('get')->once()->with('notifier::sms.getter_phone')->andReturn(function($user){
            return $user;
        });
        Config::shouldReceive('get')->once()->with('notifier::sms.twilio.phone_number')->andReturn('1234');
        Config::shouldReceive('get')->once()->with('notifier::views_folder')->andReturn('foobar');
        
        View::shouldReceive('make')->once()->with('foobar.sms.fooView', array('foo' => 'bar', 'user' => 'fooUser'))->andReturn($viewContent);

        $sms->notify('fooUser', 'fooView', array('foo' => 'bar'));
    }

}