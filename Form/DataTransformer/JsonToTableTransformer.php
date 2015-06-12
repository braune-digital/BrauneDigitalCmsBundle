<?php

namespace BrauneDigital\CmsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class JsonToTableTransformer implements DataTransformerInterface
{

	/**
	 * @return string
	 */
	public function transform($json)
	{
		return json_decode($json);
	}

	/**
	 * @param mixed $json
	 * @return null|object
	 */
	public function reverseTransform($json)
	{
		return json_encode($json);
	}
}