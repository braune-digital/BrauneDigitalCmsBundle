<?php

namespace BrauneDigital\CmsBundle\Twig;

use BrauneDigital\CmsBundle\Document\Page;
use Symfony\Cmf\Bundle\CoreBundle\Templating\Helper\CmfHelper;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\PHPCR\DocumentManager;

class CmsExtension extends \Twig_Extension
{

	/**
	 * @var CmfHelper
	 */
	protected $cmfHelper;

	/**
	 * @var DocumentManager
	 */
	protected $dm;

	public function __construct(CmfHelper $cmfHelper, $registry = null, $objectManagerName = null)
	{
		$this->cmfHelper = $cmfHelper;
		if ($registry && $registry instanceof ManagerRegistry) {
			$this->dm = $registry->getManager($objectManagerName);
		}
	}

	public function getFunctions()
	{
		$functions = array(
			new \Twig_SimpleFunction('bd_cms_locale_route_for_page', array($this, 'getLocaleRouteForPage')),
		);

		return $functions;
	}

	/**
	 * @param Page $page
	 * @param string $locale
	 * @return string
	 */
	public function getLocaleRouteForPage(Page $page = null,  $locale)
	{

        if (!$page) {
            return null;
        }

		foreach ($page->getRoutes() as $route) {
			$defaults = $route->getDefaults();
			if ($defaults['_locale'] == $locale) {
				return $route->getId();
			}
		}

		$metadata = $this->dm->getClassMetadata(get_class($page));
		$fallbackLocales = $this->dm->getLocaleChooserStrategy()->getFallbackLocales($page, $metadata, $locale);
		foreach ($fallbackLocales as $fallbackLocale) {
			foreach ($page->getRoutes() as $fallbackLocale) {
				$defaults = $route->getDefaults();
				if ($defaults['_locale'] == $locale) {
					return $route->getId();
				}
			}
		}

		return null;
	}

	public function getName()
	{
		return 'bd_cms';
	}
}

?>