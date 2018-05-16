<?php

namespace Flodaq\TicketNotificationBundle\Tests\Functional;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class FunctionalTest extends WebTestCase
{
    public function testConfiguredMailer()
    {
        $container = static::$kernel->getContainer();

        $this->assertTrue($container->hasParameter('flodaq_ticket_notification.emails'));
        $this->assertArrayHasKey('sender_email', $container->getParameter('flodaq_ticket_notification.emails'));
        $this->assertArrayHasKey('sender_name', $container->getParameter('flodaq_ticket_notification.emails'));
    }

    public function testMailerService()
    {
        $container = static::$kernel->getContainer();

        $this->assertTrue($container->has('mailer'));
        $this->assertInstanceOf(\Swift_Mailer::class, $container->get('mailer'));
    }

    public function testConfiguredTemplates()
    {
        $container = static::$kernel->getContainer();

        $this->assertTrue($container->hasParameter('flodaq_ticket_notification.templates'));
        $this->assertArrayHasKey('new_html', $container->getParameter('flodaq_ticket_notification.templates'));
        $this->assertArrayHasKey('new_txt', $container->getParameter('flodaq_ticket_notification.templates'));
        $this->assertArrayHasKey('update_html', $container->getParameter('flodaq_ticket_notification.templates'));
        $this->assertArrayHasKey('update_txt', $container->getParameter('flodaq_ticket_notification.templates'));
    }
}
