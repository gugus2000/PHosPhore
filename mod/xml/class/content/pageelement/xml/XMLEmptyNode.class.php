<?php

namespace content\pageelement\xml;

/**
 * An XML empty element node
 *
 * @author gugus2000
 **/
class XMLEmptyNode extends \content\PageElement
{
	/* method */

		/**
		* Construction of an instance of \content\pageelement\xml\XMLEmptyNode
		*
		* @param array elements Elements to insert in the node
		* 
		* @return void
		*/
		public function __construct($elements)
		{
			if (!isset($elements['name']))
			{
				$elements['name']='name';
			}
			if (!isset($elements['attributes']))
			{
				$elements['attributes']=array();
			}
			$attributes=array();
			if (!empty($elements['attributes']))
			{
				foreach ($elements['attributes'] as $name => $value)
				{
					$attributes[]=new \content\PageElement(array(
						'template' => $GLOBALS['config']['path_template'].'pageelement/xml/attribute.html',
						'elements' => array(
							'name'  => $name,
							'value' => $value,
						),
					));
				}
			}
			parent::__construct(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/xml/xmlemptynode/template.html',
				'elements' => array(
					'name'       => $elements['name'],
					'attributes' => $attributes,
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
				case 'attributes':
					$this->addAttributes($value);
					break;
				default:
					parent::addValueElement($index, $value);
					break;
			}
		}
		/**
		* Add an attributes to the attributes
		*
		* @param array attribute Attribute to add
		* 
		* @return void
		*/
		public function addAttributes($attribute)
		{
			$attributes=$this->getElement('attributes');
			$Attribute=new \content\PageElement(array(
				'template' => $GLOBALS['config']['path_template'].'pageelement/xml/attribute.html',
				'elements' => array(
					'name'  => $attribute['name'],
					'value' => $attribute['value'],
				),
			));
			$attributes[]=$Attribute;
			$this->setElement('attributes', $attributes);
		}
} // END class XMLEmptyNode extends \content\PageElement

?>