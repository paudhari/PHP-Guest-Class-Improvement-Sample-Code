<?php

class Guest {

	/**
	 * @var DbConnection
	 */
	private $dbConnection;

	public function __construct(DbConnectionInterface $dbConnection) {
		$this->dbConnection = $dbConnection;
	}

	public function addGuest( $guest ) {
		$this->dbConnection->connect();
		$this->dbConnection->insert('guest', array($guest['name'], $guest['address'], $guest['phone'], $guest['email'], date('Y-m-d H:i:s')));
		$this->dbConnection->disconnect();

	}

	public function addGuests() {
		$this->dbConnection->connect();
		if (isset($_REQUEST['guestArray'])) {
			foreach ($_REQUEST['guestArray'] as $guest) {
				$this->dbConnection->insert('guest', array($guest['name'], $guest['address'], $guest['phone'], $guest['email'], date('Y-m-d H:i:s')));
			}
		}
		$this->dbConnection->disconnect();
	}
}

?>
