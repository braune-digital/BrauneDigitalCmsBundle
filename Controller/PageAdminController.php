<?php

namespace BrauneDigital\CmsBundle\Controller;

use BrauneDigital\CmsBundle\PHPCR\TextBlock;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PageAdminController extends CRUDController
{
	/**
	 * @param null $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws AccessDeniedException
	 * @throws NotFoundHttpException
	 */
	public function editContentAction($id = null) {
		// the key used to lookup the template
		$templateKey = 'edit_content';

		$id = $this->get('request')->get($this->admin->getIdParameter());
		$object = $this->admin->getObject($id);

		if (!$object) {
			throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
		}

		if (false === $this->admin->isGranted('EDIT', $object)) {
			throw new AccessDeniedException();
		}

		$this->admin->setSubject($object);

		$documentManager = $this->get('doctrine_phpcr')->getManager();

		return $this->render($this->admin->getTemplate($templateKey), array(
			'page' => $object
		));
	}

	/**
	 * @param null $id
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws AccessDeniedException
	 * @throws NotFoundHttpException
	 */
	public function addContentAction($id = null, $col = "") {
		// the key used to lookup the template
		$templateKey = 'add_content';

		$id = $this->get('request')->get($this->admin->getIdParameter());
		$page = $this->admin->getObject($id);

		if (!$page) {
			throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
		}

		if (false === $this->admin->isGranted('EDIT', $page)) {
			throw new AccessDeniedException();
		}

		$this->admin->setSubject($page);
		return $this->render($this->admin->getTemplate($templateKey), array(
			'page' => $page,
			'configuration' => $this->container->getParameter('braune_digital_cms')
		));
	}

	/**
	 * @param null $id
	 * @param string $col
	 * @param string $type
	 * @return RedirectResponse
	 * @throws AccessDeniedException
	 * @throws NotFoundHttpException
	 */
	public function createContentAction($id = null, $col = "", $type = 'text-block') {
		// the key used to lookup the template
		$templateKey = 'edit_content';

		$id = $this->get('request')->get($this->admin->getIdParameter());
		$page = $this->admin->getObject($id);

		if (!$page) {
			throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
		}

		if (false === $this->admin->isGranted('EDIT', $page)) {
			throw new AccessDeniedException();
		}

		$this->admin->setSubject($page);


		$dm = $this->get('doctrine_phpcr')->getManager();

		switch ($type) {
			case 'text-block':
				$block = new TextBlock();
				$block->setName(time());
				$block->setTitle('Headline');
				$block->setBody('Body');
				$block->setCol($col);
				$block->setParentDocument($page);
				$dm->persist($block);
				$dm->flush();
				break;
		}

		return new RedirectResponse($this->admin->generateUrl(
			'edit-content',
			array(
				'id' => $page->getId()
			)
		));
	}

}
