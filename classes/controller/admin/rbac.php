<?php
class Controller_Admin_Rbac extends Controller
{
	/**
	 * @rule access control
	 * @rule access control (with condition)|$user->id == 2
	 */
	public function action_index()
	{
		Rbac::check();
		$rules = Rbac::parse_controllers();
		$roles = ORM::factory('role')
			->where('name', '!=', 'admin')
			->find_all();
		$view = View::factory('rbac_index')->set(array(
			'rules' => $rules,
			'roles' => $roles,
			)
		);
		$this->request->response = $view;
	}

	public function action_modify()
	{
		$record = ORM::factory('acl')
			->where('role', '=', $_POST['role'])
			->and_where('action', '=', $_POST['action'])
			->and_where('expression', '=', $_POST['expression'])
			->find();
		if ($record->loaded())
		{
			$record->delete();
		}
		else
		{
			$record->values($_POST)->save();
		}
		die('ok');
	}
}
