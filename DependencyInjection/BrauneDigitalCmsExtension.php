<?php

namespace BrauneDigital\CmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use PHPCR\Util\PathHelper;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BrauneDigitalCmsExtension extends Extension
{

	/**
	 * Allow an extension to prepend the extension configurations.
	 *
	 * @param ContainerBuilder $container
	 */
	public function prepend(ContainerBuilder $container)
	{
		// process the configuration of CmfCoreExtension
		$configs = $container->getExtensionConfig($this->getAlias());
		$parameterBag = $container->getParameterBag();
		$configs = $parameterBag->resolveValue($configs);
		$config = $this->processConfiguration(new Configuration(), $configs);

		if (empty($config['persistence']['phpcr']['enabled'])) {
			return;
		}

		$prependConfig = array(
			'chain' => array(
				'routers_by_id' => array(
					'router.default' => 0,
					'cmf_routing.dynamic_router' => -100,
				)
			),
			'dynamic' => array(
				'enabled' => true,
			)
		);
		if (isset($config['persistence']['phpcr']['basepath'])
			&& '/cms/pages' != $config['persistence']['phpcr']['basepath']
		) {
			$prependConfig['dynamic']['persistence']['phpcr']['route_basepaths'] = array($config['persistence']['phpcr']['basepath']);
		}
		$container->prependExtensionConfig('cmf_routing', $prependConfig);
	}

    /**
     * {@inheritdoc}
     */
	public function load(array $configs, ContainerBuilder $container)
	{
		$config = $this->processConfiguration(new Configuration(), $configs);
		$loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

		$loader->load('services.yml');

		$container->setParameter('braune_digital_cms', $config);

		if ($config['persistence']['phpcr']) {
			$this->loadPhpcr($config['persistence']['phpcr'], $loader, $container);
		}
	}

	protected function loadPhpcr($config, Loader\YamlFileLoader $loader, ContainerBuilder $container)
	{

		$loader->load('services-phpcr.yml');

		$prefix = $this->getAlias() . '.persistence.phpcr';

		$container->setParameter($prefix . '.basepath', $config['basepath']);
		$container->setParameter($prefix . '.layout_basepath', $config['layout_basepath']);
		$container->setParameter($prefix . '.menu_basepath', PathHelper::getParentPath($config['basepath']));

		if ($config['use_sonata_admin']) {
			$this->loadSonataAdmin($config, $loader, $container);
		} elseif (isset($config['sonata_admin'])) {
			throw new InvalidConfigurationException('Do not define sonata_admin options when use_sonata_admin is set to false');
		}

		$container->setParameter($prefix . '.manager_name', $config['manager_name']);
		$container->setParameter($prefix . '.document.class', $config['document_class']);
		$container->setParameter($prefix . '.layout.class', $config['layout_class']);
	}

	protected function loadSonataAdmin($config, Loader\YamlFileLoader $loader, ContainerBuilder $container)
	{
		$bundles = $container->getParameter('kernel.bundles');
		if ('auto' === $config['use_sonata_admin'] && !isset($bundles['SonataDoctrinePHPCRAdminBundle'])) {
			return;
		}

		$container->setParameter($this->getAlias() . '.persistence.phpcr.admin.sort',
			isset($config['sonata_admin'])
				? $config['sonata_admin']['sort']
				: false
		);

		$loader->load('admin-phpcr.yml');
	}
}
