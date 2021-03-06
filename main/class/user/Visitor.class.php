<?php

namespace user;

/**
 * Website visitor
 *
 * @author gugus2000
 **/
class Visitor extends \user\User
{
	/* Attribute */

		/**
		* Page viewed by the user
		*
		* @var Page
		*/
		protected $page;
		/**
		* User configurations
		*
		* @var array
		*/
		protected $configurations;

	/* Method */

		/* Getter */

			/**
			* page accessor
			*
			* @return Page
			*/
			public function getPage()
			{
				return $this->page;
			}
			/**
			* configurations accessor
			*
			* @return array
			*/
			public function getConfigurations()
			{
				return $this->configurations;
			}

		/* Setter */

			/**
			* page setter
			*
			* @param Page $page Page viewed by the user
			*
			* @return void
			*/
			protected function setPage($page)
			{
				$this->page=$page;
			}
			/**
			* configurations setter
			*
			* @param array $configurations User configurations
			*
			* @return void
			*/
			protected function setConfigurations($configurations)
			{
				$this->configurations=$configurations;
			}

		/* Display */

			/**
			* page display
			*
			* @return string
			*/
			public function displayPage()
			{
				return htmlspecialchars((string)$this->page->display());
			}
			/**
			* configurations display
			*
			* @return string
			*/
			public function displayConfigurations()
			{
				$display='';
				foreach ($this->configurations as $Configuration)
				{
					$display.=$Configuration->display().'<br />';
				}
				return $display;
			}

		/**
		* Adding an element to the configurations array
		*
		* @param string $index Index to insert
		*
		* @param mixed $value Value to be inserted
		*
		* @return void
		*/
		public function setConfiguration($index, $value)
		{
			$this->configurations[$index]=$value;
		}
		/**
		* Accessor of a configuration value associated with an index
		*
		* @param string $index Value Index
		*
		* @return mixed
		*/
		public function getConfiguration($index)
		{
			if (isset($this->configurations))
			{
				if (isset($this->configurations[$index]))
				{
					return $this->configurations[$index];
				}
				else
				{
					new \exception\Warning($GLOBALS['lang']['class']['user']['visitor']['error_configuration_undefined']);
				}
			}
			else
			{
				new \exception\Warning($GLOBALS['lang']['class']['user']['visitor']['error_configurations_undefined']);
			}
		}
		/**
		* Verifies that the user has permission to view the page
		*
		* @return bool
		*/
		public function verifPermission()
		{
			return $this->getRole()->existPermission(array('application' => $this->getPage()->getApplication(), 'action' => $this->getPage()->getAction()));
		}
		/**
		* Connects the visitor
		*
		* @param string $password Visitor's password
		*
		* @return bool
		*/
		public function connection($password)
		{
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['connection'], 'visitor');
			if ($this->getPassword()!==null)
			{
				if ($this->getPassword()->verif($password))
				{
					$utilisateurManager=$this->Manager();
					$date=date($GLOBALS['config']['db_date_format']);
					$utilisateurManager->update(array(
						'date_login' => $date,
					), $this->getId());
					$this->setDate_login($date);
					$_SESSION['password']=$this->getPassword()->getPassword_clear();
					$_SESSION['nickname']=$this->getNickname();
					$_SESSION['id']=$this->getId();
					new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['connection_end'], 'visitor');
					return True;
				}
				else
				{
					new \exception\Warning($GLOBALS['lang']['class']['user']['visitor']['connection_error'], 'visitor');
					return False;
				}
			}
			else
			{
				new \exception\Warning($GLOBALS['lang']['class']['user']['visitor']['connection_error'], 'visitor');
			}
		}
		/**
		* Disconnects the visitor
		*
		* @return void
		*/
		public function disconnection()
		{
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['disconnection'], 'visitor');
			if ($this->getPassword()->verif($this->getPassword()->getPassword_clear()))
			{
				$utilisateurManager=$this->Manager();
				$date=date($GLOBALS['config']['db_date_format']);
				$utilisateurManager->update(array(
					'date_login' => $date,
				), $this->getId());
				unset($_SESSION['password']);
				unset($_SESSION['nickname']);
				unset($_SESSION['id']);
				new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['disconnection_end'], 'visitor');
			}
			else
			{
				new \exception\Error($GLOBALS['lang']['class']['user']['visitor']['disconnection_error'], 'visitor');
			}
		}
		/**
		* Visitor Registration
		*
		* @param string $password Visitor's password
		*
		* @param string $role_name Name of the visitor's role
		*
		* @return void
		*/
		public function registration($password, $role_name)
		{
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['registration'], 'visitor');
			$VisitorManager=$this->Manager();
			$this->setId($VisitorManager->add(array(
				'nickname'          => $this->getNickname(),
				'avatar'            => $this->getAvatar(),
				'date_registration' => date($GLOBALS['config']['db_date_format']),
				'date_login'        => date($GLOBALS['config']['db_date_format']),
				'banned'            => (int)$this->getBanned(),
				'email'             => $this->getEmail(),
			)));
			$this->setPassword(new \user\Password(array(
				'id'             => $this->getId(),
				'password_clear' => $password,
			)));
			$this->setRole(new \user\Role(array(
				'id'        => $this->getId(),
				'role_name' => $role_name,
			)));
			$this->getRole()->retrievePermissions();
			$RoleManager=$this->getRole()->Manager();
			$RoleManager->update(array(
				'role_name' => $this->getRole()->getRole_name(),
			), $this->getId());
			$PasswordManager=$this->getPassword()->Manager();
			$this->getPassword()->hash();
			$PasswordManager->update(array(
				'password_hashed' => $this->getPassword()->getPassword_hashed(),
			), $this->getId());
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['registration_end'], 'visitor');
		}
		/**
		* Updates the user
		*
		* @return void
		*/
		public function update()
		{
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['update'], 'visitor');
			$Manager=$this->Manager();
			$Manager->update(array(
				'avatar' => $this->getAvatar(),
				'banned' => (int)$this->getBanned(),
				'email'  => $this->getEmail(),
			), $this->getId());
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['updated'], 'visitor');
		}
		/**
		* Deletes the user /!\ USE WITH CAUTION
		*
		* @return void
		*/
		public function delete()
		{
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['delete'], 'visitor');
			$Manager=$this->Manager();
			$Manager->delete($this->getId());
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['deleted'], 'visitor');
		}
		/**
		* Load page
		*
		* @param array $parameters Array page requested by the visitor
		*
		* @return string
		*/
		public function loadPage($parameters)
		{
			new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['loadpage'], 'visitor');
			global $Visitor, $Router;
			$this->setConfigurations($GLOBALS['config']['user_config']);
			$ConfigurationManager=new \user\ConfigurationManager(\core\DBFactory::MysqlConnection());
			if (isset($parameters[$GLOBALS['config']['route_parameter']]['lang']))
			{
				if (in_array($parameters[$GLOBALS['config']['route_parameter']]['lang'],array_keys($GLOBALS['config']['lang_available'])))
				{
					new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['recognized_lang'].' '.$parameters[$GLOBALS['config']['route_parameter']]['lang'], 'visitor');
					if ($this->getId()==$GLOBALS['config']['guest_id'])
					{
						new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['lang_guest'], 'visitor');
						$_SESSION['lang']=$parameters[$GLOBALS['config']['route_parameter']]['lang'];
					}
					else
					{
						new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['lang_register'], 'visitor');
						if ($ConfigurationManager->existBy(array(
							'id_user' => $this->getId(),
							'name'    => 'lang',
						)))
						{
							new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['already_exist'], 'visitor');
							$id=$ConfigurationManager->getIdBy(array(
								'id_user' => $this->getId(),
								'name'    => 'lang',
							));
							$ConfigurationManager->update(array(
								'value' => $parameters[$GLOBALS['config']['route_parameter']]['lang'],
							), $id);
						}
						else
						{
							new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['add'], 'visitor');
							$ConfigurationManager->add(array(
								'id_user' => $this->getId(),
								'name'    => 'lang',
								'value'   => $parameters[$GLOBALS['config']['route_parameter']]['lang'],
							));
						}
					}
				}
			}
			if ($this->getId()==$GLOBALS['config']['guest_id'])
			{
				foreach (array_keys($this->getConfigurations()) as $configuration_name)
				{
					if (isset($_SESSION[$configuration_name]))
					{
						$this->setConfiguration($configuration_name, $_SESSION[$configuration_name]);
					}
				}
			}
			else
			{
				$configurations=$ConfigurationManager->getBy(array(
					'id_user' => $this->getId(),
				), array(
					'id_user' => '=',
				));
				foreach ($configurations as $configuration)
				{
					$Configuration=new \user\Configuration($configuration);
					$this->setConfiguration($Configuration->getName(), $Configuration->getValue());
				}
			}
			$GLOBALS['config']['user_config']['lang']=$this->getConfiguration('lang');
			$GLOBALS['lang']['self']=$GLOBALS['config']['user_config']['lang'];
			if($this->getRole()->existPermission($parameters))	// Permission accordée
			{
				$this->setPage(new \user\Page($parameters));
				if (stream_resolve_include_path($this->getPage()->getPath()))
				{
					include($this->getPage()->getPath());
					new \exception\Notice($GLOBALS['lang']['class']['user']['visitor']['loadpage_end'], 'visitor');
					return $this->getPage()->display();
				}
				else
				{
					new \exception\FatalError($GLOBALS['lang']['class']['user']['visitor']['no_file']);
				}
			}
			else
			{
				new \exception\FatalError($GLOBALS['lang']['class']['user']['visitor']['no_perm']);
			}
		}
		/**
		 * Checks if at least one link in the list is open for access by the visitor
		 *
		 * @param array $links Links to check
		 *
		 * @return bool
		 *
		 **/
		function verifLinks($links)
		{
			foreach ($links as $index => $link)
			{
				if ($this->getRole()->existPermission($link))
				{
					return True;
				}
			}
			return False;
		}
		/**
		* Create a \user\Visitor instance
		*
		* @param array $attributes Object attributes
		*
		* @param bool $retrieve If the visitor must be retrieved
		*
		* @return mixed
		*/
		public function __construct($attributes, $retrieve=true)
		{
			parent::__construct($attributes);
			if ($retrieve)
			{
				$this->retrieve();

			}
		}
} // END class Visitor extends \user\User

?>
