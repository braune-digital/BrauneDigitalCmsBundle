<?php

namespace BrauneDigital\CmsBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BlockAdminController extends CRUDController
{


	protected function redirectTo($object)
	{
		$request = $this->container->get('request_stack')->getCurrentRequest();
		if (
			$request->get('returnUrl') &&
			(
				null !== $this->get('request')->get('btn_update_and_list') ||
				null !== $this->get('request')->get('btn_create_and_list') ||
				$this->getRestMethod() == 'DELETE'
			)

		) {
			return new RedirectResponse($request->get('returnUrl'));
		}

		return parent::redirectTo($object);
	}


}
