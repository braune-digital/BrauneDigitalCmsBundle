<?php


namespace BrauneDigital\CmsBundle\Admin;

use BrauneDigital\CmsBundle\Document\TextBlock;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Cmf\Bundle\BlockBundle\Admin\AbstractBlockAdmin;


class HeadlineBlockAdmin extends BaseBlockAdmin
{

	protected $baseRouteName = 'braunedigital_cms_headline_block';

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

			->add('blockType', 'hidden', array(
				'required' => false,
				'read_only' => true,
				'data' => 'headline'
			))
            ->add('title', 'text', array(
				'required' => false
			))
			->add('headlineType', 'choice', array(
				'required' => false,
				'choices' => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
				),
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
