Role Based Access Control for Kohana3

![rbac table](http://farm2.static.flickr.com/1300/4711754990_8c1e43829a.jpg)

Usage
=======
1) enable rbac module in bootstrap.php (auth module is needed)

2) install install.sql under rbac directory (replace '{table_prefix}' to your own table_prefix)

3) if you haven't created user , create first , and assign a role for this user

4) create rules in action's comment using @rule tag like this
	/**
	 * @rule edit node
	 * @rule edit node (owner)|$item->user_id == $user->id
	 */
	public function action_edit()
	{
		$node = ORM::factory('node')->find($this->request->param('id'));
		Rbac::check($node);
		//...
	}
in the second rule , there is a "|", below which is an expression , $item is $node here.

5) if you don't have login form , you can use Auth::force_login like this
	public function action_login()
	{
		// suppose you just created an user whose email is foo@bar.com 
		Auth:instance()->force_login('foo@bar.com'); 
		// if you want to log out 
		// Auth::install()->logout(); 
	}
then browser this url to perform login

6) go to http://localhost/path/to/admin/rbac
it will show a table if everything works well, you can check and uncheck for special roles. pretty easy and directly

Tips
======
1) admin has all priviledges.
2) if you visit /path/to/admin/rbac , and it shows 'sorry , but you are not allowed to access this page', you can comment Rbac::check($node) in controller/admin/rbac.php temporary.
