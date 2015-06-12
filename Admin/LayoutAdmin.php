<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrauneDigital\CmsBundle\Admin;

use BrauneDigital\CmsBundle\Form\LayoutBuilderType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;
use PHPCR\Util\PathHelper;

class LayoutAdmin extends Admin
{
    protected $translationDomain = 'BrauneDigitalCmsBundle';

    private $sortOrder = false;

	/**
	 * Root path for the route parent selection
	 * @var string
	 */
	protected $routeRoot;

    public function setSortOrder($sortOrder)
    {
        if (! in_array($sortOrder, array(false, 'asc', 'desc'))) {
            throw new \InvalidArgumentException($sortOrder);
        }
        $this->sortOrder = $sortOrder;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', 'text')
            ->addIdentifier('title', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
		$formMapper
			->add(
				'parent',
				'doctrine_phpcr_odm_tree',
				array('choice_list' => array(), 'select_root_node' => true, 'root_node' => $this->routeRoot)
			)
			->add('name')
			->add('title')
			->add('configuration', 'text')
			->add('template', 'text')
		;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name',  'doctrine_phpcr_nodename')
        ;
    }



	public function setRouteRoot($routeRoot)
	{
		// make limitation on base path work
		parent::setRootPath($routeRoot);
		// https://github.com/sonata-project/SonataDoctrinePhpcrAdminBundle/issues/148
		$this->routeRoot = PathHelper::getParentPath($routeRoot);
	}

}
