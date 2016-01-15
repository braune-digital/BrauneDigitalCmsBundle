<?php

namespace BrauneDigital\CmsBundle\Admin;

trait TranslationPhpcrAdminTrait  {


	/**
	 * @var array
	 */
	protected $currentLocale;

	/**
	 * @var array
	 */
	protected $locales = array();


	/**
	 *
	 */
	public function setCurrentLocale() {
		if ($this->hasRequest() && $this->request->get('object_locale')) {
			$this->currentLocale = array($this->request->get('object_locale'));
		} else {
			$this->currentLocale = array($this->getConfigurationPool()->getContainer()->getParameter('locale'));
		}
	}

	/**
	 * @param mixed $object
	 */
	public function buildTranslations($object) {

		$managerRegistry = $this->getConfigurationPool()->getContainer()->get('doctrine_phpcr');
		$dm = $managerRegistry->getManagerForClass($this->class);
		$existingLocales = $dm->getLocalesFor($this->getSubject());
		$locales = $this->getConfigurationPool()->getContainer()->getParameter('locales');
		foreach($locales as $locale) {
			$this->locales[] = array(
				'exists' => in_array($locale, $existingLocales),
				'code' => $locale
			);
		}
	}

	public function getLocales() {
		return $this->locales;
	}

	public function getDefaultLocale() {
		return $this->getConfigurationPool()->getContainer()->getParameter('locale');
	}

	/**
	 * {@inheritdoc}
	 */
	public function generateUrl($name, array $parameters = array(), $absolute = false)
	{
		if ($this->hasRequest() && $this->getRequest()->get('object_locale')) {
			$parameters['object_locale'] = $this->getRequest()->get('object_locale');
		}
		if ($this->hasRequest() && $this->getRequest()->get('returnUrl')) {
			$parameters['returnUrl'] = $this->getRequest()->get('returnUrl');
		}
		if ($this->hasRequest() && $this->getRequest()->get('col')) {
			$parameters['col'] = $this->getRequest()->get('col');
		}
		return $this->routeGenerator->generateUrl($this, $name, $parameters, $absolute);
	}

	/**
	 * @return mixed
	 */
	public function getSubject()
	{
		if ($this->request->get('object_locale')) {
			$object_locale = $this->request->get('object_locale');
		} else {
			$object_locale = $this->getConfigurationPool()->getContainer()->getParameter('locale');
		}

		$managerRegistry = $this->getConfigurationPool()->getContainer()->get('doctrine_phpcr');
		$dm = $managerRegistry->getManagerForClass($this->class);
		$id = $this->request->get($this->getIdParameter());
		$this->subject = $dm->findTranslation($this->class, $id, $object_locale, true);

		return $this->subject;
	}

	public function getActionParams($locale) {

		$params = $this->request->attributes->get('_route_params');
		$additionalParams  = array(
			'object_locale' => $locale['code']
		);
		if ($this->request->get('col')) {
			$additionalParams['col'] = $this->request->get('col');
		}
		if ($this->request->get('returnUrl')) {
			$additionalParams['returnUrl'] = $this->request->get('returnUrl');
		}
		$params = array_merge($params, $additionalParams);

		return $params;
	}

}