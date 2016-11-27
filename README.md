# E-mails notification for Hackzilla Ticketing Bundle
Simple add-on bundle that is build on top of Hackzilla Ticketing bundle in order to provide automatic e-mails
notifications when a ticket is created or modified. The ticket's owner and every user with the role ROLE_TICKET_ADMIN get notified. 
  
## Requirements
* PHP >= 5.6
* Symfony ~2.8|~3.0
* Ticketing Bundle ~3.0 see: https://github.com/hackzilla/TicketBundle
* VichUploaderBundle ~1.0

## Installation
### Step 1: Make sure you already have HackzillaTicketBundle
Make sure HackzillaTicketBundle is already present in your composer.json:
```
{
    "require": {
        "hackzilla/ticket-bundle": "~2.0@dev",
        "friendsofsymfony/user-bundle": "~2.0@dev",
        "vich/uploader-bundle": "~1.0"
    }
}
```
Also make sure the Attachments additionnal feature is enabled.

### Step 2: Download the bundle using composer
Require the bundle with composer:
```
$ composer require flodaq/ticket-notification-bundle "^1.0@dev"
```
Composer will install the bundle to your project's vendor/flodaq/ticket-notification-bundle directory.

### Step 3: Enable the bundle
Enable the bundle in the kernel:
```
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        new FOS\UserBundle\FOSUserBundle(),
        new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        new Vich\UploaderBundle\VichUploaderBundle(),
        new Flodaq\TicketNotificationBundle\FlodaqTicketNotificationBundle(),
        // ...
        // Your application bundles
    );
}
```

### Step 4: Configure the bundle
Add the following configuration to your config.yml file according to your e-mails sender's information.
```
config.yml

flodaq_hackzilla_ticket_notification:
    emails:
        sender_email:   'email@example.com'
        sender_name:    'Firstname LASTNAME'
```

### Step 5: Custom templates (optional)
You can override default e-mails templates by configuring your custom ones in the config.yml file.
```
config.yml

    flodaq_hackzilla_ticket_notification:
        templates:
            new_html:       'FlodaqTicketNotificationBundle:Emails:ticket.new.html.twig'
            new_txt:        'FlodaqTicketNotificationBundle:Emails:ticket.new.txt.twig'
            update_html:    'FlodaqTicketNotificationBundle:Emails:ticket.update.html.twig'
            update_txt:     'FlodaqTicketNotificationBundle:Emails:ticket.update.txt.twig'
```

## Pull requests
I'm open to pull requests for additional features and/or improvements.