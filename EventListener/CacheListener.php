<?php

namespace BrauneDigital\CmsBundle\EventListener;

use BrauneDigital\CmsBundle\PHPCR\BaseBlock;
use Application\BrauneDigital\CmsBundle\PHPCR\Page;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Escapegamer\AppBundle\Entity\Event;
use Escapegamer\AppBundle\Entity\EventTranslation;
use Escapegamer\AppBundle\Entity\Operator;
use Escapegamer\AppBundle\Entity\OperatorTranslation;
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
	protected $router;
	protected $kernel;
	protected $requestStack;

	protected $formats = array('html', 'json');

    public function __construct(RouterInterface $router, ContainerInterface $container, Kernel $kernel, RequestStack $requestStack)
    {
        $this->router = $router;
		$this->container = $container;
		$this->kernel = $kernel;
		$this->requestStack = $requestStack;
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
		if ($config['persistence']['phpcr']['use_sonata_cache']) {

			$cache = $this->container->get($config['persistence']['phpcr']['sonata_cache']);
			$entity = $args->getObject();
			$keys = array();
			$locales = $this->container->getParameter('locales');

			if ($entity instanceof Page) {
				// Cache for show route
				foreach ($locales as $locale) {
					foreach($this->formats as $format) {
						$keys[] = array(
							'type' => 'braune_digital_cms_content',
							'id' => $entity->getId(),
							'format' => $format,
							'locale' => $locale
						);
					}
				}
			}
			if ($entity instanceof BaseBlock) {
				// Cache for show route
				foreach ($locales as $locale) {
					foreach($this->formats as $format) {
						$keys[] = array(
							'type' => 'braune_digital_cms_content',
							'id' => $entity->getParentDocument()->getId(),
							'format' => $format,
							'locale' => $locale
						);
					}
				}
			}
			if (count($keys) > 0) {
				foreach ($keys as $key) {
					if ($cache->has($key)) {
						$cache->flush($key);
					}
				}
			}
		}
	}

}