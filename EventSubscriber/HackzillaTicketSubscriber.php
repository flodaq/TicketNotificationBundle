<?php

namespace Flodaq\TicketNotificationBundle\EventSubscriber;

use Flodaq\TicketNotificationBundle\Mailer\Mailer;
use Hackzilla\Bundle\TicketBundle\Event\TicketEvent;
use Hackzilla\Bundle\TicketBundle\TicketEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class HackzillaTicketSubscriber
 * @package Flodaq\TicketNotificationBundle\EventSubscriber
 */
class HackzillaTicketSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            TicketEvents::TICKET_CREATE => 'ticketNotification',
            TicketEvents::TICKET_UPDATE => 'ticketNotification',
        );
    }

    /**
     * Send a notification e-mail message when a ticket has been created|modified|deleted.
     *
     * @param TicketEvent $event
     * @param string $eventName
     */
    public function ticketNotification(TicketEvent $event, $eventName)
    {
        $this->mailer->sendTicketNotificationEmailMessage($event->getTicket(), $eventName);
    }
}
