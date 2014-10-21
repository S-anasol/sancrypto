<?php
	
	Class Controller_Posts Extends Controller_Base
	{
		
		function index()
		{
			global $template, $core, $router;
			$list = Post::find('all',array('order'=>'time desc'));
			if(count($list))
			{
				$template->render("posts/index", array('list' => $list));
			}
			else
			{
				$template->render("access");
			}
		}
		
		function manage()
		{
			global $template, $core, $router;
			$errors = array();
			if(!$core->is_logged())
			{
				$template->render("auth");
			}
			else
			{
				$data = Post::find('all',array('order' => 'time desc'));
				$template->render("posts/manage", array('subtitle' => 'Ну посты же сука', 'data' => $data));
			}
		}
		
		function edit()
		{
			global $template, $core, $router, $get;
			$errors = array();
			if(!$core->is_logged())
			{
				$template->render("auth");
			}
			else
			{
				$get[0] = (int)$get[0];
				$post = Post::find($get[0]);
				if(!empty($post->id))
				{
					$data = array();
					$error = false;
					if(!empty($_POST))
					{
						$data['name'] = $_POST["name"];
						$data['content'] = $_POST["content"];
						$data['time'] = time();
						$data['author'] = $core->is_logged();
						
						if(!$error)
						{
							$post->name = $data['name'];
							$post->content = $data['content'];
							$post->time = $data['time'];
							$post->author = $data['author'];
							if($post->save())
							{
								$router->redirect('posts/manage/', true);
							}
						}
					}
					
					$template->render("posts/add", array('subtitle' => 'Редактировать блять', 'data' => $post));
				}
				else
				{
					$router->redirect('posts/manage/', true);
				}
			}
		}
		
		function show()
		{
			global $template, $get;
			try {
				$post = Post::find($get[0]);
			}
			catch(Exception $e)
			{
			}
			if(!empty($post->id))
			{
				$template->render("posts/show",array('post' => $post));
			}
			else
			{
				$template->render("access");
			}
		}
		
		function view()
		{
			global $template, $get;
			try {
				$post = Post::find($get[0]);
			}
			catch(Exception $e)
			{
			}
			if(!empty($post->id))
			{
				if($_SERVER["REMOTE_ADDR"] != "188.242.52.10")
				{
					$post->views++;
					$post->save();
				}
				echo "true";
			}
			else
			{
				$template->render("access");
			}
		}
		
		function add()
		{
			global $template, $core, $router;
			$errors = array();
			if(!$core->is_logged())
			{
				$template->render("auth");
			}
			else
			{
				$data = array();
				$error = false;
				if(!empty($_POST))
				{
					$data['name'] = $_POST["name"];
					$data['content'] = $_POST["content"];
					$data['time'] = time();
					$data['author'] = $core->is_logged();
					
					if(!$error)
					{
						$post = new Post();
						$post->name = $data['name'];
						$post->content = $data['content'];
						$post->time = $data['time'];
						$post->author = $data['author'];
						if($post->save())
						{
							$router->redirect('posts/show/'.$post->id.'/', true);
						}
					}
				}
				
				$template->render("posts/add", array('subtitle' => 'Ебашь', 'data' => $data, 'alerts' => $errors));
			}
		}
		
	}
?>