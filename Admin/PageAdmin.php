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

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Symfony\Cmf\Bundle\RoutingBundle\Admin\RouteAdmin;
use BrauneDigital\CmsBundle\Document\Page;
use Sonata\AdminBundle\Route\RouteCollection;

class PageAdmin extends RouteAdmin
{
    protected $translationDomain = 'BrauneDigitalCmsBundle';

    private $sortOrder = false;

	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->add('edit-content', $this->getRouterIdParameter().'/edit-content', array(), array('id' => '.+'));
		$collection->add('add-content', $this->getRouterIdParameter().'/add-content/{col}', array(), array('id' => '.+'));
		$collection->add('create-content', $this->getRouterIdParameter().'/create-content/{col}', array(), array('id' => '.+'));
	}

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
            ->addIdentifier('path', 'text')
            ->add('title', 'text')
			->add('_action', 'actions', array(
				'actions' => array(
					'edit-content' => array(
						'template' => 'BrauneDigitalCmsBundle:Admin/Page/Partials:list_edit_content.html.twig'
					),
					'edit' => array('template' => 'BrauneDigitalCmsBundle:Admin/Page/Partials:list_edit.html.twig'),
				)
			))

        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
		$formMapper
			->with('form.group_general', array(
				'translation_domain' => 'CmfRoutingBundle',
			))
			->add(
				'parent',
				'doctrine_phpcr_odm_tree',
				array('choice_list' => array(), 'select_root_node' => true, 'root_node' => $this->routeRoot)
			)
			->add('name', 'text')
			->end();

        $formMapper
            ->with('form.group_general', array(
                'translation_domain' => 'BrauneDigitalCmsBundle',
            ))
                ->add('label', null, array('required' => false))
                ->add('title')
                ->add('layout', 'phpcr_document', array(
					'class' => 'BrauneDigital\CmsBundle\Document\Layout'
				))
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name',  'doctrine_phpcr_nodename')
        ;
    }

    public function getExportFormats()
    {
        return array();
    }

    public function prePersist($object)
    {
        if ($this->sortOrder) {
            $this->ensureOrderByDate($object);
        }
    }

    public function preUpdate($object)
    {
        if ($this->sortOrder) {
            $this->ensureOrderByDate($object);
        }
    }

    /**
     * @param Page $page
     */
    protected function ensureOrderByDate($page)
    {
        $items = $page->getParentDocument()->getChildren();

        $itemsByDate = array();
        /** @var $item Page */
        foreach ($items as $item) {
            $itemsByDate[$item->getDate()->format('U')][$item->getPublishStartDate()->format('U')][] = $item;
        }

        if ('asc' == $this->sortOrder) {
            ksort($itemsByDate);
        } else {
            krsort($itemsByDate);
        }
        $sortedItems = array();
        foreach ($itemsByDate as $itemsForDate) {
            if ('asc' == $this->sortOrder) {
                ksort($itemsForDate);
            } else {
                krsort($itemsForDate);
            }
            foreach ($itemsForDate as $itemsForPublishDate) {
                foreach ($itemsForPublishDate as $item) {
                    $sortedItems[$item->getName()] = $item;
                }
            }
        }

        if ($sortedItems !== $items->getKeys()) {
            $items->clear();
            foreach ($sortedItems as $key => $item) {
                $items[$key] = $item;
            }
        }
    }

    public function toString($object)
    {
        return $object instanceof Page && $object->getTitle()
            ? $object->getTitle()
            : $this->trans('link_add', array(), 'SonataAdminBundle')
        ;
    }

	/**
	 * @param string $name
	 * @return null|string
	 */
	public function getTemplate($name)
	{
		switch ($name) {
			case 'edit_content':
				return 'BrauneDigitalCmsBundle:Admin:Page/edit_content.html.twig';
				break;
			case 'add_content':
				return 'BrauneDigitalCmsBundle:Admin:Page/add_content.html.twig';
				break;
			default:
				return parent::getTemplate($name);
				break;
		}
	}
}
