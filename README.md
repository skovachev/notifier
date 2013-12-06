# Notifier ![Build status](https://api.travis-ci.org/skovachev/notifier.png)

Some projects require user notifications to be sent out. In many cases there are multiple ways to notify an user about an event. This package makes it easier to send notifications using different notification channels.

## Installation

You'll need to add the package to you `composer.json` file.

```js
"require-dev": {
    "skovachev/notifier": "dev-master"
}
```

Then you need to run `composer install` to download the package contents and update the autoloader.

Once it's installed you will need to register its service provider with your application. Open up `app/config/app.php` and add the following update your `providers` key.

```php
'providers' => array(
    'Skovachev\Notifier\ServiceProvider',
)
```

You'll also need to update your `aliases` key as well.

```php
'aliases' => array(
    'Notifier' => 'Skovachev\Notifier\Facade',
)
```

That's it.

## Usage

The package provides two ways of sending a notification out of the box: SMS and Email. SMS uses the Twilio SDK while Email uses the built in mailing functionality of Laravel.

To send a notification using both notifiers you need to do the following:
```php

$notification = Notifier::createNotification($user, $view); // create notification with 1. user to be notifier and 2. view template

// set data needed to render the view template
$notification->setViewData($data);

// set subject of notification
$notification->setSubject('Your message');

// send notification
Notifier::sendNotification($notification);
```

By default Notifier will try to load the view template using the following path: `notifications/<NOTIFIER_KEY>/<VIEW_TEMPLATE>`. For instance if we had a view named 'welcome_message' the path to the email templated would be `notifications/email/welcome_message` and for the SMS - `notifications/sms/welcome_message`.
The base folder for these templates can be changed in the package `config.php` (See [Settings](#settings) for more information).

In some cases you may want to send a notification using only a subset of all available notification channels. In that case you can simply pass a list of Notifier keys along with the Notification object:

```php
Notifier::sendNotification($notification, ['email']);
```

If you wanted to send a notification using just a single channel you can do that using the following syntax as well:

```php
Notifier::sendEmailNotification($notification);
```

## Settings<a name='settings'></a>

If you wanted to change some package settings you can do so by publishing the configuration:
```
php artisan config:publish skovachev/notifier
```
and editing the package's `config.php`.

There you'll be able to set the base folder for notification templates, enable / disable notifiers, set notifier options and custom getters.

### Getters 

Getters are closures defined in the package's `config.php`. They allow you to define how notifiers extract contact information from the passed used object. In the case of the Email Notifier you could define the values of the *email* and *name* attributes associated with the Email message.

## License

Notifier is released under the MIT Licence. See the bundled LICENSE file for details.

## Contributions

Please do not hesitate to send suggestions and feature requests. Let's make this package awesome!


