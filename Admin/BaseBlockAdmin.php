<?php


namespace BrauneDigital\CmsBundle\Admin;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Cmf\Bundle\BlockBundle\Admin\AbstractBlockAdmin;


class BaseBlockAdmin extends AbstractBlockAdmin
{

	/**
	 * @var ManagerRegistry
	 */
	protected $dm;

	/**
	 * @param DocumentManager $dm
	 */
	public function setDocumentManager(ManagerRegistry $dm) {
		$this->dm = $dm->getManager();
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{

		$parentOptions = array(
			'property' => 'id',
			'read_only' => true,
			'class'    => 'BrauneDigital\CmsBundle\Document\Page'
		);

		if ($this->getRequest()->get('parent')) {
			$parentOptions['data'] = $this->dm->getRepository('BrauneDigitalCmsBundle:Page')->find($this->getRequest()->get('parent'));
		}

		$formMapper
			->with('form.group_general')
			->add('parentDocument', 'phpcr_document', $parentOptions)
			->add('name', 'hidden', array(
				'read_only' => true,
				'data' => time(),
			))
			->add('col', null, array(
				'read_only' => true,
				'data' => $this->getRequest()->get('col')
			))
			->add('position')
			->end()
		;
	}


	public function generateUrl($name, array $parameters = array(), $absolute = false)
	{
		$parameters['returnUrl'] = $this->getRequest()->get('returnUrl');
		if ($this->getRequest()->get('col')) {
			$parameters['col'] = $this->getRequest()->get('col');
		}
		return parent::generateUrl($name, $parameters, $absolute);
	}


}
