<?php

use Illuminate\Support\Facades\Config;

class NotifierTest extends TestCase 
{

    public function testGetOption()
    {
        $notifier = Mockery::mock('Skovachev\Notifier\Notifiers\Notifier')->makePartial();
        $notifier->shouldReceive('getNotifierKey')->andReturn('key');

        $optionValue = 'foo';
        $optionKey = 'bar';

        Config::shouldReceive('get')->once()->with('notifier::key.' . $optionKey)->andReturn($optionValue);

        $value = $notifier->getOption($optionKey);

        $this->assertEquals($value, $optionValue);
    }

    public function testDoesNotNotifyIfNotificationsDisabled()
    {
        $notifier = Mockery::mock('Skovachev\Notifier\Notifiers\Notifier')->makePartial();
        $notifier->shouldReceive('getNotifierKey')->andReturn('key');

        Config::shouldReceive('get')->once()->with("notifier::key.enabled")->andReturn(false);

        $notifier->notify('fooUser', 'fooView', 'fooData');
    }

    public function testNotify()
    {
        $notifier = Mockery::mock('Skovachev\Notifier\Notifiers\Notifier')->makePartial();
        $notifier->shouldReceive('getNotifierKey')->andReturn('key');
        $notifier->shouldReceive('prepareDestination')->once()->with(array('subject'=>null))->andReturn('fooDestination');
        $notifier->shouldReceive('sendNotification')->once()->with('fooDestination', 'foobar.key.fooView', array('user' => 'fooUser'));

        Config::shouldReceive('get')->once()->with("notifier::key.enabled")->andReturn(true);
        Config::shouldReceive('get')->once()->with("notifier::views_folder")->andReturn('foobar');

        $notifier->notify('fooUser', 'fooView', array());
    }

}