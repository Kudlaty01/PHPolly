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
	 * View constructor.
	 * @param array       $data associative array with all parts to be replaced
	 * @param string|null $template template to be introduced
	 * @param string|null $layout layout to wrap the template in
	 */
	public function __construct($data, $template = null, $layout = null)
	{
		$this->data = $data;
		$this->setTemplate($template);
		$this->setLayout($layout ?? 'layout');
	}

	/**
	 * @return null|string
	 */
	public function getTemplate(): string
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
			throw new \ErrorException("There is not view template $template");
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
	 * @inheritdoc
	 */
	public function render()
	{
		$templateContent   = $this->getTemplateContents($this->template);
		$layoutContent     = $this->getTemplateContents($this->layout);
		$navigationContent = $this->getNavigationContent($this->navigation);
		$replacementKeys   = array_map(function ($key) {
			return '{{ ' . $key . ' }}';
		}, array_keys($this->data));
		$templateContent   = str_replace($replacementKeys, array_values($this->data), $templateContent);
		$viewToRender      = str_replace(['{{ templateData }}', '{{ navigation }}'], [$templateContent, $navigationContent], $layoutContent);

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
	private function getNavigationContent($navigation)
	{
		$result = '';
		foreach ($navigation as $caption => $navItem) {
			$result .= sprintf('<a href="%s">%s</a>', $navItem, $caption);
		}
		return $result;
	}

}