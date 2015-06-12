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
class BaseBlock extends AbstractBlock implements TranslatableInterface
{


	/**
	 * The language this document currently is in
	 */
	protected $locale;

	/**
	 * @var string
	 */
	protected $col;

	/**
	 * @var integer
	 */
	protected $position;

	/**
	 * @var string
	 */
	protected $blockType;

	/**
	 * @return string
	 */
	public function getCol()
	{
		return $this->col;
	}

	/**
	 * @param string $col
	 */
	public function setCol($col)
	{
		$this->col = $col;
	}

	/**
	 * @return mixed
	 */
	public function getLocale()
	{
		return $this->locale;
	}

	/**
	 * @param mixed $locale
	 */
	public function setLocale($locale)
	{
		$this->locale = $locale;
	}

	/**
	 * @return int
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param int $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}

	/**
	 * @return string
	 */
	public function getBlockType()
	{
		return $this->blockType;
	}

	/**
	 * @param string $blockType
	 */
	public function setBlockType($blockType)
	{
		$this->blockType = $blockType;
	}




	/**
	 * Returns the type
	 *
	 * @return string $type
	 */
	public function getType()
	{
		return 'braunedigital_cms.blocks.base';
	}


}
