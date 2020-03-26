<?php

namespace content\pageelement\html;

/**
 * An HTML head
 *
 * @author gugus2000
 **/
class HTMLHead extends \content\PageElement
{
	/* Method */

		/**
		* Create a \content\pageelement\html\HTMLHead instance
		* 
		* @return void
		*/
		public function __construct()
		{
			$Metas=array();
			if (isset($GLOBALS['config']['default_head_metas']))
			{
				foreach ($GLOBALS['config']['default_head_metas'] as $meta)
				{
					$metas=array();
					foreach ($meta as $key => $value)
					{
						$metas[]=new \content\PageElement(array(
							'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/metas.html',
							'elements' => array(
								'key'   => $key,
								'value' => $value,
							),
						));
					}
					$Metas[]=new \content\PageElement(array(
						'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/meta.html',
						'elements' => array(
							'metas' => $metas,
						),
					));
				}
			}
			$css=array();
			if (isset($GLOBALS['config']['default_head_css']))
			{
				foreach ($GLOBALS['config']['default_head_css'] as $href)
				{
					$css[]=new \content\PageElement(array(
						'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/css.html',
						'elements' => array(
							'href' => $href,
						),
					));
				}
			}
			$javascripts=array();
			if (isset($GLOBALS['config']['default_head_javascripts']))
			{
				foreach ($GLOBALS['config']['default_head_javascripts'] as $src)
				{
					$javascripts[]=new \content\PageElement(array(
						'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/javascripts.html',
						'elements' => array(
							'src' => $src,
						),
					));
				}
			}
			parent::__construct(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/template.html',
				'elements' => array(
					'metas'       => $Metas,
					'css'         => $css,
					'javascripts' => $javascripts,
				),
			));
		}
		/**
		* Adds an element in an array element in elements
		*
		* @param mixed $index Index of the element to which to add the value
		*
		* @param mixed $value Value of the element of the element to be added
		* 
		* @return void
		*/
		public function addValueElement($index, $value)
		{
			switch ($index)
			{
				case 'metas':
					$this->addMetas($value);
					break;
				case 'css':
					$this->addCss($value);
					break;
				case 'javascripts':
					$this->addJavascripts($value);
					break;
				default:
						parent::addValueElement($index, $value);
					break;
			}
		}
		/**
		* Add a metas attribute to the page
		*
		* @param array $metas Metadata table
		* 
		* @return void
		*/
		public function addMetas($meta)
		{
			$metas=$this->getElement('metas');
			$Metas=array();
			foreach ($meta as $key => $value)
			{
				$Metas[]=new \content\PageElement(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/metas.html',
				'elements' => array(
					'key'   => $key,
					'value' => $value,
				),
			));
			}
			$Meta=new \content\PageElement(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/meta.html',
				'elements' => array(
					'metas' => $Metas,
				),
			));
			if (!array_key_exists(spl_object_hash($Meta), $metas))
			{
				$metas[]=$Meta;
				$this->setElement('metas', $metas);
			}
		}
		/**
		* Add a css file to the page
		*
		* @param string $href Link to the css file
		* 
		* @return void
		*/
		public function addCss($href)
		{
			$css=$this->getElement('css');
			$Css=new \content\PageElement(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/css.html',
				'elements' => array(
					'href' => $href,
				),
			));
			if (!array_key_exists(spl_object_hash($Css), $css))
			{
				$css[]=$Css;
				$this->setElement('css', $css);		    
			}
		}
		/**
		* Add a Javascript script to the page
		*
		* @param string $src Link to the javascript
		* 
		* @return void
		*/
		public function addJavascripts($src)
		{
			$javascripts=$this->getElement('javascripts');
			$Javascript=new \content\PageElement(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/html/htmlhead/javascripts.html',
				'elements' => array(
					'src' => $src,
				),
			));
			if (!array_key_exists(spl_object_hash($Javascript), $javascripts))
			{
				$javascripts[]=$Javascript;
				$this->setElement('javascripts', $javascripts);		    
			}
		}
} // END class HTMLHead extends \content\PageElement

?>