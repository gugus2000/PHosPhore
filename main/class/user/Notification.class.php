<?php

namespace user;

/**
 * Notification for a visitor
 *
 * @author gugus2000
 **/
class Notification extends \core\Managed
{
	/* Constant */

		/**
		* Notification type: success
		*
		* @var string
		*/
		const TYPE_SUCCESS='success';
		/**
		* Notification type: warning
		*
		* @var string
		*/
		const TYPE_WARNING='warning';
		/**
		* Notification type: error
		*
		* @var string
		*/
		const TYPE_ERROR='error';

	/* Attribute */

		/**
		* Notification id
		* 
		* @var int
		*/
		protected $id;
		/**
		* Notification type
		* 
		* @var string
		*/
		protected $type;
		/**
		* Date of notification release
		* 
		* @var string
		*/
		protected $date_release;
		/**
		* Notification Content Id
		* 
		* @var int
		*/
		protected $id_content;

	/* Method */

		/* Getter */

			/**
			* id accessor
			* 
			* @return int
			*/
			public function getId()
			{
				return $this->id;
			}
			/**
			* type accessor
			* 
			* @return string
			*/
			public function getType()
			{
				return $this->type;
			}
			/**
			* date_release accessor
			* 
			* @return string
			*/
			public function getDate_release()
			{
				return $this->date_release;
			}
			/**
			* id_content accessor
			* 
			* @return int
			*/
			public function getId_content()
			{
				return $this->id_content;
			}

		/* Setter */

			/**
			* id setter
			*
			* @param int $id Notification id
			* 
			* @return void
			*/
			protected function setId($id)
			{
				$this->id=(int)$id;
			}
			/**
			* type setter
			*
			* @param string $type Notification type
			* 
			* @return void
			*/
			protected function setType($type)
			{
				$this->type=(string)$type;
			}
			/**
			* date_release setter
			*
			* @param string $date_release Date of notification release
			* 
			* @return void
			*/
			protected function setDate_release($date_release)
			{
				$this->date_release=(string)$date_release;
			}
			/**
			* id_content setter
			*
			* @param string $id_content Notification Content Id
			* 
			* @return void
			*/
			protected function setId_content($id_content)
			{
				$this->id_content=(int)$id_content;
			}

		/* Display */

			/**
			* id display
			* 
			* @return string
			*/
			public function displayId()
			{
				return htmlspecialchars((string)$this->id);
			}
			/**
			* type display
			* 
			* @return string
			*/
			public function displayType()
			{
				return htmlspecialchars((string)$this->type);
			}
			/**
			* date_release display
			* 
			* @return string
			*/
			public function displayDate_release()
			{
				return htmlspecialchars((string)$this->date_release);
			}
			/**
			* id_content display
			* 
			* @return string
			*/
			public function displayId_content()
			{
				return htmlspecialchars((string)$this->id_content);
			}

		/**
		* Content display
		* 
		* @return string
		*/
		public function displayContent()
		{
			global $Visitor;
			$Content=new \content\Content(array(
				'id_content' => $this->getId_content(),
			));
			$Content->retrieveLang($Visitor->getConfiguration('lang'));
			return $Content->display();
		}
		/**
		* Notification display
		* 
		* @return string
		*/
		public function display()
		{
			return $this->displayContent();
		}
		/**
		* retrieves all contents in all languages
		* 
		* @return array
		*/
		public function retrieveContents()
		{
			$ContentManager=new \content\ContentManager(\core\DBFactory::MysqlConnection());
			return $ContentManager->retrieveBy(array(
				'id_content' => $this->getId_content(),
			), array(
				'id_content' => '=',
			));
		}
		/**
		* Send notification on the pageElement
		*
		* @param \content\PageElement $pageElement PageElement which will be changed
		*
		* @param \content\pageelement\Notification $Notification Notification which will be inserted into the pageElement
		* 
		* @return void
		*/
		public function sendNotification($PageElement, $Notification)
		{
			global $Visitor;
			$Notification->addElement('type', $this->displayType());
			$Notification->addElement('content', $this->displayContent());
			if (!$PageElement->getElement('notifications'))
			{
				$PageElement->addElement('notifications', array());
			}
			$PageElement->addValueElement('notifications', $Notification);
			$LinkNotificationUser=new \user\LinkNotificationUser(\core\DBFactory::MysqlConnection());
			$LinkNotificationUser->deleteBy(array(
				'id_notification' => $this->getId(),
				'id_user'         => $Visitor->getId(),
			), array(
				'id_notification' => '=',
				'id_user'         => '=',
			));
		}
		/**
		* Create a notification
		*
		* @param array $id_users List of user ids that will receive the notification
		*
		* @return void
		*/
		public function create($id_users=array())
		{
			$Manager=$this->Manager();
			$Manager->add(array(
				'type'         => $this->getType(),
				'date_release' => date($GLOBALS['config']['db_date_format']),
				'id_content'   => $this->getId_content(),
			));
			$this->setId($Manager->getIdBy(array(
				'type'       => $this->getType(),
				'id_content' => $this->getId_content(),
			)));
			$LinkNotificationUser=new \user\LinkNotificationUser(\core\DBFactory::MysqlConnection());
			$id_user=array();
			foreach ($id_users as $id)
			{
				$id_user[]=array('id_user' => $id);
			}
			$LinkNotificationUser->addBy($id_user, array(
				'id_notification' => $this->getId(),
			));
		}
		/**
		* Change a notification
		*
		* @return void
		*/
		public function change()
		{
			$Manager=$this->Manager();
			$Manager->update(array(
				'type'         => $this->getType(),
				'date_release' => date($GLOBALS['config']['db_date_format']),
			), $this->getId());
			$LinkNotificationUser=new \user\LinkNotificationUser(\core\DBFactory::MysqlConnection());
			$id_users=array();
			foreach ($this->retrieveUsers() as $User)
			{
				$id_users[]=$this->getId();
			}
			$donnees_already_in=$LinkNotificationUser->get('id_notification', $this->getId());
			$id_users_already_in=array();
			foreach ($donnees_already_in as $donnee)
			{
				$id_users_already_in[]=$donnee['id_user'];
			}
			$id_users_not_changed=array_intersect($id_users_already_in, $id_users);
			if(array_diff($id_users_not_changed, $id_users_already_in))
			{
				$LinkNotificationUser->deleteBy(array(
					'id_notification' => $this->getId(),
				), array(
					'id_notification' => '=',
				));
				$LinkNotificationUser->addBy(array(array(
					'id_user' => $id_users,
				)), array(
					'id_notification' => $this->getId(),
				));
			}
			else
			{
				$id_users_to_add=array_diff($id_users, $id_users_not_changed);
				$LinkNotificationUser->addBy(array(array(
					'id_user' => $id_users_to_add,
				)), array(
					'id_notification' => $this->getId(),
				));
			}
		}
		/**
		* Delete a notification
		*
		* @return void
		*/
		public function delete()
		{
			$Manager=$this->Manager();
			$this->retrieve();
			$Manager->delete($this->getId());
			$LinkNotificationUser=new \user\LinkNotificationUser(\core\DBFactory::MysqlConnection());
			$LinkNotificationUser->deleteBy(array(
				'id_notification' => $this->getId(),
			), array(
				'id_notification' => '=',
			));
			$ContentManager=new \content\ContentManager(\core\DBFactory::MysqlConnection());
			$ContentManager->deleteBy(array(
				'id_content' => $this->getId_content(),
			), array(
				'id_content' => '=',
			));
		}
		/**
		* Récupère l'id des users devant voir la notification
		*
		* @return void
		*/
		public function recupererId_users()
		{
			$Liaison=new \user\LinkNotificationUser(\core\DBFactory::MysqlConnection());
			$resultats=$Liaison->get('id_notification', $this->getId());
			$id=array();
			foreach ($resultats as $resultat)
			{
				$id[]=$resultat['id_user'];
			}
			$this->setId_users($id);
		}
		/**
		* Fetches users who need to see the notification
		* 
		* @return array
		*/
		public function retrieveUsers()
		{
			$Link=new \user\LinkNotificationUser(\core\DBFactory::MysqlConnection());
			return $Link->retrieveBy(array(
				'id_notification' => $this->getId(),
			), array(
				'id_notification' => '=',
			), '\\user\\User', array(
				'db'  => 'id_user',
				'obj' => 'id',
			), 1);
		}
} // END class Notification extends \core\Managed

?>