<?php

namespace Flodaq\TicketNotificationBundle\EventSubscriber;

use Flodaq\TicketNotificationBundle\Mailer\Mailer;
use Hackzilla\Bundle\TicketBundle\Event\TicketEvent;
use Hackzilla\Bundle\TicketBundle\TicketEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class HackzillaTicketSubscriber
 * @package Flodaq\TicketNotificationBundle\EventSubscriber
 */
class HackzillaTicketSubscriber implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Mailer constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        /** @var Mailer $mailer */
        $mailer = $this->container->get('flodaq_ticket_notification.mailer');
        $mailer->sendTicketNotificationEmailMessage($event->getTicket(), $eventName);
    }
}