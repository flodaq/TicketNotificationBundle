<?php

namespace Flodaq\TicketNotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('flodaq_ticket_notification');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC for "symfony/config:<4.2"
            $rootNode = $treeBuilder->root('flodaq_ticket_notification');
        }

        $rootNode
            ->children()
                ->arrayNode('emails')
                    ->children()
                        ->scalarNode('sender_email')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('sender_name')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('new_html')->defaultValue('FlodaqTicketNotificationBundle:Emails:ticket.new.html.twig')->end()
                        ->scalarNode('new_txt')->defaultValue('FlodaqTicketNotificationBundle:Emails:ticket.new.txt.twig')->end()
                        ->scalarNode('update_html')->defaultValue('FlodaqTicketNotificationBundle:Emails:ticket.update.html.twig')->end()
                        ->scalarNode('update_txt')->defaultValue('FlodaqTicketNotificationBundle:Emails:ticket.update.txt.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
