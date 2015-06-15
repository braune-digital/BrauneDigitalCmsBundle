<?php

namespace BrauneDigital\CmsBundle\Controller;

use BrauneDigital\CmsBundle\Document\Page;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\ContentBundle\Controller\ContentController as BaseContentController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

use Symfony\Component\HttpFoundation\Request;

/**
 * Special routes to demo the features of the Doctrine Router in the CmfRoutingBundle
 */
class ContentController extends BaseContentController
{

	/**
	 * @var Container
	 */
	protected $container;

	/**
	 * @param Container $container
	 */
	public function setContainer(Container $container) {
		$this->container = $container;
	}

    /**
     * Render the provided content.
     *
     * When using the publish workflow, enable the publish_workflow.request_listener
     * of the core bundle to have the contentDocument as well as the route
     * checked for being published.
     * We don't need an explicit check in this method.
     *
     * @param Request $request
     * @param object  $contentDocument
     * @param string  $contentTemplate Symfony path of the template to render
     *                                 the content document. If omitted, the
     *                                 default template is used.
     *
     * @return Response
     */
    public function indexAction(Request $request, $contentDocument, $contentTemplate = null)
    {

		if ($contentDocument instanceof Page) {
			$contentTemplate = $contentDocument->getLayout()->getTemplate();
		} else {
			$contentTemplate = $contentTemplate ?: $this->defaultTemplate;
			$contentTemplate = str_replace(
				array('{_format}', '{_locale}'),
				array($request->getRequestFormat(), $request->getLocale()),
				$contentTemplate
			);
		}


		$config = $this->container->getParameter('braune_digital_cms');
		if ($config['persistence']['phpcr']['use_sonata_cache']) {
			$cache = $this->container->get($config['persistence']['phpcr']['sonata_cache']);

			$key = array(
				'type' => 'braune_digital_cms_content',
				'route' => $request->get('_route'),
				'format' => $request->getRequestFormat(),
				'locale' => $request->getLocale()
			);
			if (!$cache->has($key)) {
				$params = $this->getParams($request, $contentDocument);
				$content = $this->renderResponse($contentTemplate, $params);
				$cache->set($key, $content);
				return $content;
			}
			return $cache->get($key)->getData();
		} else {
			$params = $this->getParams($request, $contentDocument);
			return $this->renderResponse($contentTemplate, $params);
		}

    }

    /**
     * Action that is mapped in the controller_by_type map
     *
     * This can inject something else for the template for content with this type
     *
     * @param object $contentDocument
     *
     * @return Response
     */
    public function typeAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $params = array(
            'cmfMainContent' => $contentDocument,
            'example' => 'Additional value injected by the controller for this type (this could work without content if we want)',
        );

        return $this->renderResponse('ApplicationAppBundle::controller.html.twig', $params);
    }

    /**
     * Action that is mapped in the controller_by_class map
     *
     * This can inject something else for the template for this type of content.
     *
     * @param object $contentDocument
     *
     * @return Response the response
     */
    public function classAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $params = array(
            'cmfMainContent' => $contentDocument,
            'example' => 'Additional value injected by the controller for all content mapped to classAction',
        );

        return $this->renderResponse('ApplicationAppBundle:Demo:controller.html.twig', $params);
    }

    /**
     * Action that is explicitly referenced in the _controller field of a content
     *
     * This can inject something else for the template
     *
     * @param object $contentDocument
     *
     * @return Response
     */
    public function specialAction($contentDocument)
    {
        if (!$contentDocument) {
            throw new NotFoundHttpException('Content not found');
        }

        $params = array(
            'cmfMainContent' => $contentDocument,
            'example' => 'Additional value injected by the controller when explicitly referenced',
        );

        return $this->renderResponse('ApplicationAppBundle:Demo:controller.html.twig', $params);
    }
}
