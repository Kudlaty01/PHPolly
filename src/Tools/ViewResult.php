<?php

namespace Tools;

/**
 * Class View
 *
 * @package \Tools
 */
class ViewResult extends AbstractActionResult implements IActionResult
{
	/**
	 * @var string
	 */
	private $title;
	/**
	 * @var array
	 */
	protected $data;
	/**
	 * @var string|null
	 */
	private $template;
	/**
	 * @var string|null
	 */
	private $layout;
	/**
	 * @var string|null
	 */
	private $navigation;
	/**
	 * @var string|null
	 */
	private $navigationItemTemplate;
	/**
	 * @var string[]
	 */
	private $additionalScripts;

	/**
	 * View constructor.
	 * @param array       $data associative array with all parts to be replaced
	 * @param string|null $title layout to wrap the template in
	 * @param string|null $template template to be introduced
	 */
	public function __construct($data, $title = null, $template = null)
	{
		$this->data  = $data;
		$this->title = $title;
		$this->setTemplate($template);
		$this->setLayout('layout'); //set default layout
		$this->setNavigationItemTemplate('menu-item'); //set default layout
	}

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 * @return ViewResult
	 */
	public function setTitle($title): ViewResult
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getTemplate()//: ?string - would be in PHP 7.1
	{
		return $this->template;
	}

	/**
	 * @param null|string $template
	 * @return ViewResult
	 * @throws \ErrorException
	 */
	public function setTemplate($template): ViewResult
	{
		if ($template && !file_exists($this->getPathRelativeToProject($template))) {
			throw new \ErrorException("There is no view template $template");
		}
		$this->template = $template;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getLayout(): string
	{
		return $this->layout;
	}

	/**
	 * @param null|string $layout
	 * @return ViewResult
	 * @throws \ErrorException
	 */
	public function setLayout($layout)
	{
		if ($layout && !file_exists($this->getPathRelativeToProject($layout))) {
			throw new \ErrorException("There is not view layout template $layout");
		}
		$this->layout = $layout;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getNavigation()
	{
		return $this->navigation;
	}

	/**
	 * @param null|string $navigation
	 * @return ViewResult
	 */
	public function setNavigation($navigation)
	{
		$this->navigation = $navigation;
		return $this;
	}

	/**
	 * @param null|string $navigation
	 * @return ViewResult
	 */
	public function addNavigation($navigation)
	{
		$this->navigation = array_merge($this->navigation, $navigation);
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getNavigationItemTemplate()
	{
		return $this->navigationItemTemplate;
	}

	/**
	 * @param null|string $navigationItemTemplate
	 * @return ViewResult
	 * @throws \ErrorException
	 */
	public function setNavigationItemTemplate($navigationItemTemplate)
	{
		if ($navigationItemTemplate && !file_exists($this->getPathRelativeToProject($navigationItemTemplate))) {
			throw new \ErrorException("There is not navigation item layout template $navigationItemTemplate");
		}
		$this->navigationItemTemplate = $navigationItemTemplate;
		return $this;
	}


	/**
	 * @inheritdoc
	 */
	public function render(): string
	{
		$templateContent   = $this->getTemplateContents($this->template);
		$layoutContent     = $this->getTemplateContents($this->layout);
		$navigationContent = $this->getNavigationContent($this->navigation);
		$scriptsContent    = $this->getScriptsContent($this->additionalScripts);
		$replacementKeys   = array_map(function ($key) {
			return '{{ ' . $key . ' }}';
		}, array_keys($this->data));
		$templateContent   = str_replace($replacementKeys, array_values($this->data), $templateContent);
		$tagsToReplace     = $this->getTagsToReplace(['title', 'templateData', 'navigation', 'controllerScripts']);
		$viewToRender      = str_replace($tagsToReplace, [($this->title ?? ''), $templateContent, $navigationContent, $scriptsContent], $layoutContent);

		return $viewToRender;
	}

	/**
	 * @param string $path the path to template
	 * @return string
	 */
	private function getTemplateContents($path)
	{
		return file_get_contents($this->getPathRelativeToProject($path));
	}

	/**
	 * @param $template
	 * @return string
	 */
	private function getPathRelativeToProject($template)
	{
		$root = $_SERVER['DOCUMENT_ROOT'];
		return join(DIRECTORY_SEPARATOR, [$root, 'views', $template]) . '.tpl';
	}

	/**
	 * @param array $navigation
	 * @return string
	 */
	private function getNavigationContent($navigation): string
	{
		$result            = '';
		$navigatonTemplate = $this->getTemplateContents($this->navigationItemTemplate);
		$tagsToReplace     = $this->getTagsToReplace(['link', 'itemCaption']);

		foreach ($navigation as $caption => $navItem) {
			$result .= str_replace($tagsToReplace, [$navItem, $caption], $navigatonTemplate);
		}
		return $result;
	}

	public function setControllerScripts($additionalScripts)
	{
		$this->additionalScripts = $additionalScripts;
	}

	public function getScriptsContent($additionalScripts)
	{
		$scriptTags = array_map(function ($script) {
			return sprintf('<script type="text/javascript" src="%s"></script>', $script);
		}, $additionalScripts);
		return join(PHP_EOL, $scriptTags);
	}

	/**
	 * @param array $tagNames
	 * @return array
	 */
	protected function getTagsToReplace(array $tagNames):array
	{
		$tagsToReplace = array_map(function ($tag) {
			return sprintf('{{ %s }}', $tag);
		}, $tagNames);
		return $tagsToReplace;
	}

}