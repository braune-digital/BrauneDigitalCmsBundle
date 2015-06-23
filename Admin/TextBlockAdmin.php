<?php


namespace BrauneDigital\CmsBundle\Admin;

use BrauneDigital\CmsBundle\Document\TextBlock;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Cmf\Bundle\BlockBundle\Admin\AbstractBlockAdmin;


class TextBlockAdmin extends HeadlineBlockAdmin
{

	protected $baseRouteName = 'braunedigital_cms_text_block';
	protected $class = 'BrauneDigital\CmsBundle\Document\TextBlock';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', 'text')
            ->add('title', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

		parent::configureFormFields($formMapper);

        $formMapper
            ->add('body', 'ckeditor', array(
				'config_name' => 'braunedigital_cms_default',
				'required' => false
			))
			->add('blockType', 'hidden', array(
				'required' => false,
				'read_only' => true,
				'data' => 'text'
			))
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', 'doctrine_phpcr_string')
            ->add('name',  'doctrine_phpcr_nodename')
        ;
    }

    public function toString($object)
    {
        return $object instanceof TextBlock && $object->getTitle()
            ? $object->getTitle()
            : parent::toString($object)
        ;
    }


}
