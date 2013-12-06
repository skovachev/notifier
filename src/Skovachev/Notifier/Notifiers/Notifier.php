<?php namespace Skovachev\Notifier\Notifiers;

use Illuminate\Support\Facades\Config;

abstract class Notifier
{
    protected $user;

    abstract public function getNotifierKey();
    abstract public function sendNotification($destination, $view, $data);
    abstract public function prepareDestination($destination);

    public function notify($user, $view, $data, $subject = null)
    {
        if ($this->notificationsEnabled($user))
        {
            $this->user = $user;

            $data['user'] = $user;
            $view = Config::get('notifier::views_folder') . '.' . $this->getNotifierKey() . '.' . $view;

            $destination = array('subject' => $subject);
            $destination = $this->prepareDestination($destination);
            $this->sendNotification($destination, $view, $data);
        }
    }

    public function notificationsEnabled($user)
    {
        return $this->getOption('enabled');
    }

    protected function getOption($key)
    {
        $key = $this->getKeyPrefix() . $key;
        $value = Config::get($key);
        return $value;
    }

    protected function obtainUserInfo($info)
    {
        $callback = $this->getOption('getter_' . $info);
        return $callback->__invoke($this->user);
    }

    private function getKeyPrefix()
    {
        return 'notifier::' . $this->getNotifierKey() . '.';
    }
}