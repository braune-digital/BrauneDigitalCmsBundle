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

	use TranslationPhpcrAdminTrait;

	protected $translationDomain = 'BrauneDigitalCmsBundle';

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

		$this->setCurrentLocale();
		$this->buildTranslations($this->subject);

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
			->add('position', null, $this->getRequest()->get('pos') ? array('data' => $this->getRequest()->get('pos')) : array())
			->end()
		;
	}


	public function generateUrl($name, array $parameters = array(), $absolute = false)
	{
		$parameters['returnUrl'] = $this->getRequest()->get('returnUrl');
		if ($this->getRequest()->get('col')) {
			$parameters['col'] = $this->getRequest()->get('col');
		}
		if ($this->getRequest()->get('object_locale')) {
			$parameters['object_locale'] = $this->getRequest()->get('object_locale');
		}
		return parent::generateUrl($name, $parameters, $absolute);
	}

	/**
	 * @param string $name
	 * @return null|string
	 */
	public function getTemplate($name)
	{
		switch ($name) {
			case 'edit':
				return 'BrauneDigitalCmsBundle:Admin:CRUD/base_edit.html.twig';
				break;
			default:
				return parent::getTemplate($name);
				break;
		}
	}


}
