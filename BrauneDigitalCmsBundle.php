<?php

namespace BrauneDigital\CmsBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass;

class BrauneDigitalCmsBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);


		if (class_exists('Doctrine\Bundle\PHPCRBundle\DependencyInjection\Compiler\DoctrinePhpcrMappingsPass')) {
			$container->addCompilerPass(
				DoctrinePhpcrMappingsPass::createYamlMappingDriver(
					array(
						realpath(__DIR__ . '/Resources/config/doctrine-phpcr') => 'BrauneDigital\CmsBundle\Document',
					),
					array('braune_digital_cms_cms.persistence.phpcr.manager_name'),
					false,
					array('BrauneDigitalCmsBundle' => 'BrauneDigital\CmsBundle\Document')
				)
			);
		}
	}
}
