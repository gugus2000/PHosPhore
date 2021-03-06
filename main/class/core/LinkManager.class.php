<?php

namespace core;

/**
 * Manager of a linking table bteween two tables
 *
 * @author gugus2000
 **/
class LinkManager extends \core\Manager
{
	/* Method */

		/**
		* Retrieves entries with a given index in a given column
		* 
		* @param string $attribute Attribute for the given index
		*
		* @param mixed $index Index of the element
		* 
		* @return mixed
		*/
		public function get($attribute, $index=null)
		{
			if ($index!==null)
			{
				if (in_array($attribute, $this::ATTRIBUTES))
				{
					$requete=$this->getBdd()->prepare('SELECT '.implode(',', $this::ATTRIBUTES).' FROM '.$this::TABLE.' WHERE '.$attribute.'=?');
					$requete->execute(array($index));
					return $requete->fetchAll();
				}
			}
			return False;
		}
		/**
		* Retrieves entries with several other same attributes with particular values
		*
		* @param array $attributes Array of attribute tables with their values
		*
		* @param array $operations Array of array of operations
		*
		* @param string $group Name of the attribute used for grouping
		*
		* @param bool $strict Whether the strict mode is activated or not (if the number of attributes in this group must match exactly)
		* 
		* @return mixed
		*/
		public function getByGroup($attributes, $operations, $group, $strict=False)
		{
			new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['getbygroup_start'], 'linkmanager');
			if (in_array($group, $this::ATTRIBUTES))
			{
				$attributes_operators=array();
				$true_attributes=array();
				if ($strict)
				{
					new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['getbygroup_strict'], 'linkmanager');
					$attributes_count=array();
				}
				foreach ($attributes as $index => $condition)
				{
					$conditionCreator=$this->conditionCreator($condition, $operations[$index]);
					$attributes_operators[$index]=$conditionCreator[0];
					$condition=$conditionCreator[1];
					$true_attributes=array_merge($true_attributes, array_values($condition));
				}
				if ($strict)
				{
					$keys=array_keys_multi($attributes);
					foreach ($keys as $key)
					{
						$attributes_count[]='COUNT('.$key.')='.(string)count(array_column($attributes, $key));
					}
					$condition_count=' AND '.$group.' IN (SELECT '.$group.' FROM '.$this::TABLE.' GROUP BY '.$group.' HAVING '.implode(' AND ', $attributes_count).')';
				}
				else
				{
					$condition_count='';
				}
				$selects_with_table_name=array();
				$equalitiess_join_with_table_name=array();
				$nbr_conditions=count($attributes);
				for ($index=0; $index < $nbr_conditions; $index++)
				{
					$selects_with_table_name[]='(SELECT '.$group.' FROM '.$this::TABLE.' WHERE '.implode(' AND ', $attributes_operators[$index]).$condition_count.') AS table_'.$index;
					if ($index+1<$nbr_conditions)
					{
						$equalitiess_join_with_table_name[]='table_'.$index.'.'.$group.'=table_'.(string)($index+1).'.'.$group;
					}
				}
				$group_with_table_name='table_0.'.$group;
				if (count($equalitiess_join_with_table_name)==0)
				{
					$where_equalities_join='';
				}
				else
				{
					$where_equalities_join=' WHERE '.implode(' AND ', $equalitiess_join_with_table_name);
				}
				$select='SELECT '.$group_with_table_name.' FROM '.implode(' JOIN ', $selects_with_table_name).$where_equalities_join.' GROUP BY '.$group_with_table_name;
				new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['getbygroup_request'].' '.$select, 'linkmanager');
				$requete=$this->getBdd()->prepare($select);
				$requete->execute(array_values_recursive($true_attributes));
				return $requete->fetchAll();
			}
			else
			{
				new \exception\Error($GLOBALS['lang']['class']['core']['linkmanager']['getbygroup_bad_group'], 'linkmanager');
			}
			return False;
		}
		/**
		* Adds elements in the table with varying and invariant parameters
		*
		* @param array $variants Variant values for each element
		*
		* @param array $invariants Invariant values common to all elements to be added
		* 
		* @return void
		*/
		public function addBy($variants, $invariants)
		{
			new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['addby'], 'linkmanager');
			foreach ($variants as $variant)
			{
				$variant=array_intersect_key($variant, array_flip($this::ATTRIBUTES));
			}
			$invariants=array_intersect_key($invariants, array_flip($this::ATTRIBUTES));
			$attributes=array_merge(array_keys($variants[\array_key_first($variants)]),array_keys($invariants));
			$data=array();
			for ($i=0; $i < count($variants); $i++)
			{ 
				$datum=array();
				foreach ($variants[$i] as $attribute => $value)
				{
					$datum[$attribute]=$value;
				}
				foreach ($invariants as $attribute => $value)
				{
					$datum[$attribute]=$value;
				}
				$data[]=$datum;
			}
			foreach ($data as $datum)
			{
				new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['addby_requests'].' '.'INSERT INTO '.$this::TABLE.'('.implode(',', $attributes).') VALUES ('.implode(',', array_fill(0, count($attributes), '?')).')', 'linkmanager');
				$requete=$this->getBdd()->prepare('INSERT INTO '.$this::TABLE.'('.implode(',', $attributes).') VALUES ('.implode(',', array_fill(0, count($attributes), '?')).')');
				$requete->execute(array_values($datum));
			}
		}
		/**
		* Checks for the existence of at least one element having an attribute with a particular value
		*
		* @param string $attribute Name of the given attribute
		*
		* @param mixed $index Attribut value
		* 
		* @return bool
		*/
		public function exist($attribute, $index)
		{
			return (bool)$this->get($attribute, $index);
		}
		/**
		* Checks for the existence of at least one entry with several other entries having the same attributes with particular values
		*
		* @param array $attributes Array containing the name and value of each attribute
		*
		* @param array $operations Array of array of operations
		*
		* @param string $group Name of the attribute used for grouping
		*
		* @param bool $strict Whether the strict mode is activated or not (if the number of attributes in this group must match exactly)
		* 
		* @return bool
		*/
		public function existByGroup($attributes, $operations, $group, $strict=False)
		{
			return (bool)$this->getByGroup($attributes, $operations, $group, $strict);
		}
		/**
		* Retrieves objects quickly
		*
		* @param array $attributes Array containing the name and value of each attribute
		*
		* @param array $operations Array of array of operations
		*
		* @param string $name_class Name of the class of the objects to be retrieved
		*
		* @param array $name_id Array containing 'db' the name of the index used to retrieve the object and 'obj' the name of the attribute used as index.
		*
		* @param int $recovery 0 no recovery, 1 grouped recovery, 2 one-by-one recovery
		* 
		* @return array
		*/
		public function retrieveBy($attributes=null, $operations=null, $name_class=null, $name_id=null, $recovery=1)
		{
			new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby'], 'linkmanager');
			if ($name_class===null)
			{
				new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_name_class'], 'linkmanager');
				preg_match('/[A-Z]+[a-z]+$/', get_class($this), $matches);
				$name_class=$matches[0];
				preg_match('/^((\w+\\\)+)/', get_class($this), $matches);
				$name_class='\\'.$matches[0].$name_class;
				new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_name_class_result'].' '.$name_class, 'linkmanager');
			}
			if ($name_id===null)
			{
				new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_name_id'], 'linkmanager');
				$name_id=array(
					'db' => 'id_'.strtolower(get_class_name($name_class)),
					'obj' => 'id',
				);
			}
			$results=$this->getBy($attributes, $operations);
			switch ($recovery)
			{
				case 0:
					new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_no_recovery'], 'linkmanager');
					$Objects=array();
					foreach ($results as $result)
					{
						$Objects[]=new $name_class(array($name_id['obj'] => $result[$name_id['db']]));
					}
					return $Objects;
					break;
				case 1:
					new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_grouped_recovery'], 'linkmanager');
					$name_manager=$name_class.'Manager';
					$Manager=new $name_manager(\core\DBFactory::MysqlConnection());
					return $Manager->retrieveBy(array(
						$name_id['obj'] => array_column($results, $name_id['db']),
					), array(
						$name_id['obj'] => 'IN',
					));
					break;
				case 2:
					new \exception\Notice($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_one_recovery'], 'linkmanager');
					$Objects=array();
					foreach ($results as $result)
					{
						$Object=new $name_class(array($name_id['obj'] => $name_id['db']));
						$Object->retrieve();
						$Objects[]=$Object;
					}
					return $Objects;
					break;
				default:			// Impossible (logically)
					new \exception\FatalError($GLOBALS['lang']['class']['core']['linkmanager']['retrieveby_impossible'], 'linkmanager');
					$Objects=array();
					foreach ($results as $result)
					{
						$Objects[]=new $name_class(array($name_id['obj'] => $result[$name_id['db']]));
					}
					return $Objects;
					break;
			}
		}
} // END class LinkManager extends \core\Manager

?>