<?php

namespace Flodaq\TicketNotificationBundle\Tests\Functional;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Flodaq\TicketNotificationBundle\FlodaqTicketNotificationBundle;
use Hackzilla\Bundle\TicketBundle\HackzillaTicketBundle;
use Hackzilla\Bundle\TicketBundle\Tests\Functional\Entity\User;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * @author Javier Spagnoletti <phansys@gmail.com>
 */
class TestKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new FrameworkBundle(),
            new SecurityBundle(),
            new DoctrineBundle(),
            new HackzillaTicketBundle(),
            new SwiftmailerBundle(),
            new FlodaqTicketNotificationBundle(),
            new TestBundle(),
        ];

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        // FrameworkBundle config
        $c->loadFromExtension('framework', [
            'secret' => 'MySecretKey',
            'default_locale' => 'en',
            'translator' => [
                'fallbacks' => [
                    'en',
                ],
            ],
        ]);

        // SecurityBundle config
        $c->loadFromExtension('security', [
            'providers' => [
                'in_memory' => [
                    'memory' => null,
                ],
            ],
            'firewalls' => [
                'main' => [
                    'anonymous' => null,
                ],
            ],
        ]);

        // DoctrineBundle config
        $c->loadFromExtension('doctrine', [
            'dbal' => [
                'connections' => [
                    'default' => [
                        'driver' => 'pdo_sqlite',
                    ],
                ],
            ],
            'orm' => [
                'default_entity_manager' => 'default',
            ],
        ]);

        // HackzillaBundle config
        $c->loadFromExtension('hackzilla_ticket', [
            'user_class' => User::class,
        ]);

        // FlodaqTicketNotificationBundle config
        $c->loadFromExtension('flodaq_ticket_notification', [
            'emails' => [
                'sender_email' => 'email@example.com',
                'sender_name' => 'John Doe',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return parent::getCacheDir();
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return parent::getLogDir();
    }

    public function unserialize($str)
    {
        $this->__construct(unserialize($str));
    }
}
