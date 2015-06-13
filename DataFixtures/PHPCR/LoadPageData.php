<?php

namespace BrauneDigital\CmsBundle\DataFixtures\PHPCR;

use BrauneDigital\CmsBundle\PHPCR\Layout;
use Application\BrauneDigital\CmsBundle\PHPCR\Page;
use BrauneDigital\CmsBundle\PHPCR\TextBlock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Knp\Menu\MenuItem;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu;

class LoadPageData implements FixtureInterface
{
	public function load(ObjectManager $dm)
	{
		if (!$dm instanceof DocumentManager) {

			$class = get_class($dm);
			throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");

		}


		$parent = $dm->find(null, '/cms/layouts');
		$layout = new Layout();
		$layout->setName('default');
		$layout->setTitle('Default Layout');
		$layout->setParent($parent);
		$layout->setTemplate('BrauneDigitalCmsBundle:Content:default.html.twig');
		$layout->setConfiguration(json_encode(array('grid' => array(
			array(
				array(
					'title' => 'Header',
					'col' => 'header',
					'colspan' => 2
				)
			),
			array(
				array(
					'title' => 'Body',
					'col' => 'body',
					'colspan' => 1
				),
				array(
					'title' => 'Sidebar',
					'col' => 'sidebar',
					'colspan' => 1
				)
			)
		))));
		$dm->persist($layout);
		$dm->flush();

		$parent = $dm->find(null, '/cms/pages');
		$page = new Page();
		$page->setName('legals');
		$page->setTitle('Impressum');
		$page->setParentDocument($parent);
		$page->setBody('Impressum');
		$page->setLayout($layout);

		$dm->persist($page);
		$dm->bindTranslation($page, 'de');

		$page->setTitle('Legals');
		$page->setBody('Legal Notice');

		$dm->bindTranslation($page, 'en');

		$block = new TextBlock();
		$block->setName(time());
		$block->setTitle('Headline');
		$block->setBody('Body');
		$block->setCol('body');
		$block->setBlockType('text');
		$block->setPosition(1);
		$block->setParentDocument($page);
		$dm->persist($block);


		$parent = $dm->find(null, '/cms/menu');
		$menu = new Menu();
		$menu->setParentDocument($parent);
		$menu->setName('footer');
		$menu->setLabel('Footer Menü');
		$menu->setLinkType('uri');
		$dm->persist($menu);
		$dm->bindTranslation($menu, 'en');
		$menu->setLabel('Footer Menü');
		$dm->bindTranslation($menu, 'de');
		$dm->persist($menu);

		$menuItem = new MenuNode();
		$menuItem->setParentDocument($menu);
		$menuItem->setName('legals');
		$menuItem->setLabel('Legals');
		$menuItem->setLinkType('uri');
		$menuItem->setUri('/en/page/legals');
		$dm->persist($menuItem);
		$dm->bindTranslation($menuItem, 'en');
		$menuItem->setLabel('Impressum');
		$menuItem->setLinkType('uri');
		$menuItem->setUri('/de/page/impressum');
		$dm->bindTranslation($menuItem, 'de');
		$dm->persist($menuItem);


		$dm->flush();
	}
}