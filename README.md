Role Based Access Control for Kohana3

![rbac table](http://farm2.static.flickr.com/1300/4711754990_8c1e43829a.jpg)

Usage
=======
**1) enable rbac module in bootstrap.php**

**2) create rules in action's comment using @rule tag like this**
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

**3) go to http://localhost/path/to/admin/rbac**
it will show a table if everything works well, you can check and uncheck for special roles. pretty easy and directly
