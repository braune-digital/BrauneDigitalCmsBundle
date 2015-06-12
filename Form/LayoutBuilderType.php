<?php

namespace BrauneDigital\CmsBundle\Form;

use BrauneDigital\CmsBundle\Form\DataTransformer\JsonToTableTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LayoutBuilderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

		$transformer = new JsonToTableTransformer();
        $builder->add('configuration')->addModelTransformer($transformer);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
		$resolver->setDefaults(array(
			'compound' => true
		));
    }

	public function buildView(FormView $view, FormInterface $form, array $options)
	{
		$view->vars['json'] = $form->getData();
	}


	public function getParent()
	{
		return 'text';
	}

    /**
     * @return string
     */
    public function getName()
    {
        return 'braunedigital_cms_layoutbuilder';
    }
}
