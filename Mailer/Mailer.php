<?php

namespace Flodaq\TicketNotificationBundle\Mailer;

use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\User;
use Hackzilla\Bundle\TicketBundle\Entity\TicketMessage;
use Hackzilla\Bundle\TicketBundle\Model\TicketInterface;
use Hackzilla\Bundle\TicketBundle\TicketEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Mailer
 * @package Flodaq\TicketNotificationBundle\Mailer
 */
class Mailer
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
     * Send a notification by e-mail to the concerned users when a ticket has been created|modified|deleted.
     *
     * @param TicketInterface $ticket
     * @param string $eventName
     * @return null
     */
    public function sendTicketNotificationEmailMessage(TicketInterface $ticket, $eventName)
    {
        // Retrieve the creator
        /** @var User $creator */
        $creator = $ticket->getUserCreatedObject();

        // Prepare the email according to the message type
        switch ($eventName) {
            case TicketEvents::TICKET_CREATE:
                $subject = $this->container->get('translator')->trans('emails.ticket.new.subject', array(
                    '%number%' => $ticket->getId(),
                    '%sender%' => $creator->getUsername(),
                ), 'FlodaqTicketNotificationBundle');
                $templateHTML = $this->container->getParameter('flodaq_ticket_notification.templates')['new_html'];
                $templateTxt = $this->container->getParameter('flodaq_ticket_notification.templates')['new_txt'];
                break;
            case TicketEvents::TICKET_UPDATE:
                $subject = $this->container->get('translator')->trans('emails.ticket.update.subject', array(
                    '%number%' => $ticket->getId(),
                    '%sender%' => $creator->getUsername(),
                ), 'FlodaqTicketNotificationBundle');
                $templateHTML = $this->container->getParameter('flodaq_ticket_notification.templates')['update_html'];
                $templateTxt = $this->container->getParameter('flodaq_ticket_notification.templates')['update_txt'];
                break;
            default:
                return null;
        }

        /** @var TicketMessage $message */
        $message = $ticket->getMessages()->last();

        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        // Prepare the recipients
        // At least the ticket's owner must receive the notification
        $recipients = array();

        if ($message->getUser() !== $creator->getId()) {
            $recipients[] = $creator->getEmail();
        }

        // Add every user with the ROLE_TICKET_ADMIN role
        /** @var User $user */
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_TICKET_ADMIN')) {
                if (!in_array($user->getEmail(), $recipients) &&
                    $message->getUser() !== $user->getId()) {
                    $recipients[] = $user->getEmail();
                }
            }
        }

        // Prepare email headers
        $message = $this->prepareEmailMessage(
            $subject,
            $recipients
        );

        // Prepare template args
        $args = array(
            'ticket' => $ticket,
        );

        // Create the message body in HTML
        $format = 'text/html';
        $this->addMessagePart($message, $templateHTML, $args, $format);

        // Create the message body in plain text
        $format = 'text/plain';
        $this->addMessagePart($message, $templateTxt, $args, $format);

        // Finally send the message
        $this->sendEmailMessage($message);

        return null;
    }

    /**
     * Prepare an e-mail message.
     *
     * @param $subject
     * @param $to
     * @return \Swift_Mime_SimpleMessage
     */
    private function prepareEmailMessage($subject, $to)
    {
        // Prepare a confirmation e-mail
        return (new \Swift_Message($subject))
            ->setFrom(array(
                $this->container->getParameter('flodaq_ticket_notification.emails')['sender_email']
                    => $this->container->getParameter('flodaq_ticket_notification.emails')['sender_name']
            ))
            ->setTo($to);
    }

    /**
     * Add content to the e-mail message.
     *
     * @param \Swift_Mime_SimpleMessage $message
     * @param $template
     * @param $args
     * @param $format
     */
    private function addMessagePart(\Swift_Mime_SimpleMessage $message, $template, $args, $format)
    {
        switch ($format) {
            case 'text/plain':
                $message->addPart(
                    $this->container->get('twig')->render($template, $args),
                    $format
                );
                break;
            case 'text/html':
            default:
                $message->setBody(
                    $this->container->get('twig')->render($template, $args),
                    $format
                );
                break;
        }
    }

    /**
     * Send the e-mail message.
     *
     * @param $message
     */
    private function sendEmailMessage($message)
    {
        $this->container->get('mailer')->send($message);
    }
}