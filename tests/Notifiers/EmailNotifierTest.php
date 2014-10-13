<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailNotifierTest extends TestCase 
{
    public function testSendEmailNotification()
    {
        Mail::shouldReceive('queue')->once();

        $email = new \Skovachev\Notifier\Notifiers\EmailNotifier();
        
        Config::shouldReceive('get')->once()->with('notifier::email.enabled')->andReturn(true);
        Config::shouldReceive('get')->once()->with('notifier::views_folder')->andReturn('viewsFolder');
        Config::shouldReceive('get')->once()->with('notifier::email.from_email')->andReturn('fromEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.cc_email')->andReturn('ccEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.bcc_email')->andReturn('bccEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.getter_email')->andReturn(function($user){
            return 'getterEmail';
        });
        Config::shouldReceive('get')->once()->with('notifier::email.getter_name')->andReturn(function($user){
            return 'userName';
        });

        $email->notify('fooUser', 'fooView', array('foo' => 'bar'));
    }

    public function testPassesCorrectEmailParameters()
    {
        $email = Mockery::mock('Skovachev\Notifier\Notifiers\EmailNotifier')->makePartial();
        
        Config::shouldReceive('get')->once()->with('notifier::email.enabled')->andReturn(true);
        Config::shouldReceive('get')->once()->with('notifier::views_folder')->andReturn('viewsFolder');
        Config::shouldReceive('get')->once()->with('notifier::email.from_email')->andReturn('fromEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.cc_email')->andReturn('ccEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.bcc_email')->andReturn('bccEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.getter_email')->andReturn(function($user){
            return 'getterEmail';
        });
        Config::shouldReceive('get')->once()->with('notifier::email.getter_name')->andReturn(function($user){
            return 'userName';
        });

        $destination = array(
            'email' => 'getterEmail',
            'name' => 'userName',
            'subject' => null,
            'from_email' => 'fromEmail',
            'cc_email' => 'ccEmail',
            'bcc_email' => 'bccEmail'
        );

        $email->shouldReceive('sendNotification')->once()->with($destination, 'viewsFolder.email.fooView', array('foo' => 'bar', 'user' => 'fooUser'));

        $email->notify('fooUser', 'fooView', array('foo' => 'bar'));
    }

}