<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2014 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrauneDigital\CmsBundle\Document;

use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\AbstractBlock;
use Symfony\Cmf\Bundle\CoreBundle\Translatable\TranslatableInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * {@inheritDoc}
 */
class HeadlineBlock extends BaseBlock
{


    /**
     * @var string
	 */
    protected $title;

	/**
	 * @var
	 */
	protected $headlineType;

    /**
     * Set title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

	/**
	 * @return mixed
	 */
	public function getHeadlineType()
	{
		return $this->headlineType;
	}

	/**
	 * @param mixed $headlineType
	 */
	public function setHeadlineType($headlineType)
	{
		$this->headlineType = $headlineType;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getType()
	{
		return 'braunedigital_cms.blocks.headline';
	}





}
