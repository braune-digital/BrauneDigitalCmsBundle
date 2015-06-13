<?php

namespace BrauneDigital\CmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('braune_digital_cms')
            ->children()
				->arrayNode('content_types')
					->useAttributeAsKey('name')
					->prototype('array')
						->children()
							->scalarNode('name')->end()
							->scalarNode('label')->end()
						->end()

					->end()
				->end()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('phpcr')
                            ->addDefaultsIfNotSet()
                            ->canBeEnabled()
                            ->children()
                                ->scalarNode('basepath')->defaultValue('/cms/pages')->end()
                                ->scalarNode('layout_basepath')->defaultValue('/cms/layouts')->end()
                                ->scalarNode('manager_registry')->defaultValue('doctrine_phpcr')->end()
                                ->scalarNode('manager_name')->defaultNull()->end()
                                ->scalarNode('document_class')->defaultValue('Application\BrauneDigital\CmsBundle\PHPCR\Page')->end()
                                ->scalarNode('layout_class')->defaultValue('BrauneDigital\CmsBundle\PHPCR\Layout')->end()
								->scalarNode('sonata_cache')->defaultValue('sonata.cache.memcached')->end()
								->scalarNode('use_sonata_cache')->defaultValue(false)->end()
                                ->enumNode('use_sonata_admin')
                                    ->values(array(true, false, 'auto'))
                                    ->defaultValue('auto')
                                ->end()
                                ->arrayNode('sonata_admin')
                                    ->children()
                                        ->enumNode('sort')
                                            ->values(array(false, 'asc', 'desc'))
                                            ->defaultValue(false)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->enumNode('use_menu')
                    ->values(array(true, false, 'auto'))
                    ->defaultValue('auto')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
