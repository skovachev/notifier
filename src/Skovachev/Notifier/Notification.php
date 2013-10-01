<?php namespace Skovachev\Notifier;

use View;

class Notification
{
    protected $view;
    protected $user;
    protected $subject;
    protected $view_data;

    public function __construct($user, $view)
    {
        $this->user = $user;
        $this->view = $view;

        $this->view_data = array();
        $this->subject = null;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function setViewData($view_data)
    {
        $this->view_data = $view_data;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getViewData()
    {
        return $this->view_data;
    }

    public function getSubject()
    {
        return $this->subject;
    }
}