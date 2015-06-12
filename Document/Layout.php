<?php


namespace BrauneDigital\CmsBundle\Document;

use LogicException;
use Knp\Menu\NodeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPCR\NodeInterface as PHPCRNodeInterface;
use Symfony\Component\Validator\Constraints as Assert;


class Layout implements NodeInterface {

	/**
	 * Unique id of this route
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * @var NodeInterface
	 */
	protected $node;

	/**
	 * PHPCR node name
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $configuration;

	/**
	 * @var
	 */
	protected $children;

	/**
	 * @var
	 */
	protected $parent;

	/**
	 * @var string
	 */
	protected $template;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}



	/**
	 * Get the options for the factory to create the item for this node
	 *
	 * @return array
	 */
	function getOptions()
	{
		return array();
	}

	/**
	 * Get the child nodes implementing NodeInterface
	 *
	 * @return \Traversable
	 */
	function getChildren()
	{
		return $this->children;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param mixed $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}



	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return NodeInterface
	 */
	public function getNode()
	{
		return $this->node;
	}

	/**
	 * @param NodeInterface $node
	 */
	public function setNode($node)
	{
		$this->node = $node;
	}


	public function __toString() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getConfiguration()
	{
		return $this->configuration;
	}

	/**
	 * @param string $configuration
	 */
	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
	}

	public function getGrid()
	{
		$configuration = json_decode($this->getConfiguration());
		return $configuration->grid;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}




}
