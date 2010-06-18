<?php
class Rbac_Core 
{
	public static function check($item = null)
	{
		$backtrace = debug_backtrace();
		$action = $backtrace[1]['class'].'_'.$backtrace[1]['function'];
		$acls = ORM::factory('acl')->find_all();
		$role = array(0);
		if ($user = Auth::instance()->get_user())
		{
			$role = array();
			foreach($user->roles->find_all() as $user_role)
			{
				$role[] = $user_role->id;
				if ($user_role->name == 'admin')
					return true;
			}
		}
		$allowed = false;
		foreach($acls as $acl)
		{
			if (in_array($acl->role, $role) AND $action == $acl->action)
			{
				if (empty($acl->expression) OR eval('return '.$acl->expression.';'))
				{
					$allowed = true;
				}
			}
		}
		if (!$allowed)
		{
			echo View::factory('rbac_denied')->render();
			die();
		}
	}

	public static function match($role, $action, $expression = '')
	{
		$acls = ORM::factory('acl')->find_all();
		$checked = '';
		foreach($acls as $acl) {
			if ($acl->role == $role AND $acl->action == $action){
				if ($acl->expression == $expression) {
					$checked = 'checked';
					break;
				}
			}
		}
		return $checked;
	}

	public static function parse_controllers()
	{
		$rule = array();
		foreach (self::_fetch_controllers() as $controller)
		{
			$class = new ReflectionClass($controller);
			$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
			foreach($methods as $method)
			{
				if (substr($method->getName(), 0, 6) == 'action')
				{
					$rule = array_merge($rule, self::_parse_action($method, $class->getName()));
				}
			}
		}
		return $rule;
	}

	private static function _parse_action($action, $controller_name)
	{
		$comment = str_replace(array("\r\n","\r"), "\n", $action->getDocComment());
		$comment = explode("\n", $comment);
		// remove header/footer comment
		array_pop($comment);
		array_shift($comment);
		$rule = array();
		if (empty($comment))
			return $rule;
		
		foreach ($comment as $str)
		{
			$str = trim(str_replace("*",'', $str));
			if (strpos($str, '@rule') !== false) 
			{
				$rule[trim(str_replace('@rule', '', $str))] = $controller_name.'_'.$action->getName();
			}
		}
		return $rule;
	}

	private static function _fetch_controllers()
	{
		$paths = Kohana::modules() + array(APPPATH);
		$paths = array_values($paths);
		foreach($paths as $index=>$path)
		{
			$paths[$index] = rtrim($path,'/').'/';
		}
		$paths = Kohana::list_files('classes/controller',$paths);
		return self::_path_to_controller($paths);
	}

	private static function _path_to_controller($paths)
	{
		$controllers = array();
		foreach($paths as $r_path=>$a_path)
		{
			if(is_array($a_path))
			{
				foreach($a_path as $sub_path => $sub_real_path)
				{
					$controllers[] = str_replace('/','_',substr($sub_path,8,-4));
				}
			}
			else
			{
				$controllers[] = str_replace('/','_',substr($r_path,8,-4));
			}
		}
		return $controllers;
	}
}
