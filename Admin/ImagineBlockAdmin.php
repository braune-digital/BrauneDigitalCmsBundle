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

use BrauneDigital\CmsBundle\PHPCR\ImagineBlock;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

/**
 * @author Horner
 */
class ImagineBlockAdmin extends BaseBlockAdmin
{

	protected $baseRouteName = 'braunedigital_cms_imagine_block';

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->addIdentifier('id', 'text')
            ->add('name', 'text')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
		parent::configureFormFields($formMapper);

        // image is only required when creating a new item
        // TODO: sonata is not using one admin instance per object, so this doesn't really work - https://github.com/symfony-cmf/BlockBundle/issues/151
        $imageRequired = ($this->getSubject() && $this->getSubject()->getParentDocument()) ? false : true;


        $formMapper
            ->with('form.group_general')
                ->add('label', 'text', array('required' => false))
                ->add('linkUrl', 'text', array('required' => false))
                /**->add('filter', 'choice', array(
					'required' => false,
					'data' => 'image_upload_thumbnail',
					'choices' => array(
						'image_upload_thumbnail' => 'Default'
					)
				))*/
                ->add('image', 'cmf_media_image', array('required' => $imageRequired))
				->add('blockType', 'hidden', array(
					'required' => false,
					'read_only' => true,
					'data' => 'headline'
				))
            ->end();
    }

    public function toString($object)
    {
        return $object instanceof ImagineBlock && $object->getLabel()
            ? $object->getLabel()
            : parent::toString($object)
        ;
    }
}
