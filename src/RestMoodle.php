<?php namespace Peterojo;

use dcai\curl as Curl;

class RestMoodle {
  # set all your constants here or move them to a separate file if you please
	protected $format;
	protected $host;
	protected $token;

	protected $curl;

	function __construct(Array $config)
	{
		$this->curl = new Curl;

		foreach ($config as $key => $value) {
			if(property_exists($this, $key))
				$this->$key = $value;
		}
	}

	/**
	 * Create new users
	 * @param $users
	 * $users must be an array of objects/assoc arrays each representing a user record
	 * i.e. $users = [$record1, $record2, ...] where $record1 and $record2 are either
	 * objects or assoc arrays that each carry all the information for one user
	 *
	 * @return mixed
	 */
	public function createUsers ($users)
	{
		$params = ['users' => $users];

		$mdlFunc = "core_user_create_users";
		$restFormat = (self::$restFormat=="json") ? '&moodlewsrestformat=' . self::$restFormat : '';
		$url = self::$mdlHost . '/webservice/rest/server.php?wstoken=' . self::$mdlToken . '&wsfunction=' . $mdlFunc . $restFormat;

		return $this->curl->post($url, $params);
	}

	public function deleteUsers (Array $userIDs)
	{
		$params['userids'] = $userIDs;

		$mdlFunc = "core_user_delete_users";
		$restFormat = (self::$restFormat=="json") ? '&moodlewsrestformat=' . self::$restFormat : '';
		$url = self::$mdlHost . '/webservice/rest/server.php?wstoken=' . self::$mdlToken . '&wsfunction=' . $mdlFunc . $restFormat;

		return $this->curl->post($url, $params);
	}

	/**
	 * This function searches for users using the OR operator on the values entered
	 * @param $field
	 * @param $value
	 *
	 * @return bool
	 */

	public function getUserByField( $field, $value )
	{
		$params['field'] = $field;
		$params['values'][] = $value;

		$mdlFunc = "core_user_get_users_by_field";
		$restFormat = (self::$restFormat=="json") ? '&moodlewsrestformat=' . self::$restFormat : '';
		$url = self::$mdlHost . '/webservice/rest/server.php?wstoken=' . self::$mdlToken . '&wsfunction=' . $mdlFunc . $restFormat;

		return $this->curl->get($url, $params);
	}

	public function enrolUsers( $userIDs, $courseID )
	{
		$params['enrolments'] = [];
		foreach ($userIDs as $id) {
			$params['enrolments'][] = [
				'roleid'    => 5, // student role is 5
				'userid'    => $id,
				'courseid'  => $courseID
			];
		}

		$mdlFunc = "enrol_manual_enrol_users";
		$restFormat = (self::$restFormat=="json") ? '&moodlewsrestformat=' . self::$restFormat : '';
		$url = self::$mdlHost . '/webservice/rest/server.php?wstoken=' . self::$mdlToken . '&wsfunction=' . $mdlFunc . $restFormat;

		return $this->curl->post($url, $params);
	}
}
