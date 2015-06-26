<?php

namespace BrauneDigital\CmsBundle\EventListener;

use BrauneDigital\CmsBundle\Document\BaseBlock;
use BrauneDigital\CmsBundle\Document\Page;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pagerfanta\Pagerfanta;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouterInterface;


class CacheListener extends ContainerAware {

    protected $container;

	protected $formats = array('html', 'json');

    public function __construct(ContainerInterface $container)
    {
		$this->container = $container;
    }

	public function prePersist(LifecycleEventArgs $args) {
		$this->preUpdate($args);
	}

	public function postPersist(LifecycleEventArgs $args) {

		$this->postUpdate($args);

	}

	public function preUpdate(LifecycleEventArgs $args) {

	}

	public function postUpdate(LifecycleEventArgs $args) {
		$config = $this->container->getParameter('braune_digital_cms');
		$cacheManager = $this->container->get('fos_http_cache.cache_manager');
		$entity = $args->getObject();
		$locales = $this->container->getParameter('locales');
		if ($entity instanceof Page) {
			foreach ($entity->getRoutes() as $route) {
				$cacheManager->refreshRoute($route->getId());
			}
		}
		if ($entity instanceof BaseBlock) {
			foreach ($entity->getParentDocument()->getRoutes() as $route) {
				$cacheManager->refreshRoute($route->getId());
			}
		}
	}

}