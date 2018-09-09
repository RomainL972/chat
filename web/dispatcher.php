<?php

require_once "system/include.php";

global $controller, $action, $content_type, $data;

# Scan include and models directories to load each files from them
try {
	# Clean REQUEST_URI to get what we wanted
	$query_args 	= explode('/', substr($_SERVER['REDIRECT_URL'], 1));
	$controller 	= empty($query_args[0]) ? 'default' : urldecode($query_args[0]);
	$action 		= $_SERVER["REQUEST_METHOD"];

	switch ($action) {
		case 'GET':
			$data = $_GET;
			break;
		
		case 'POST':
			$data = $_POST;
			break;

		default:
			$data = file_get_contents("php://input");
			parse_str($data, $data);
	}

	//die(print_r(compact(explode(" ", "query_args controller action data"))));
	
	# Search in controllers directory if we have a match between a filename and 
	if (!is_readable("controllers/${controller}.php"))
		throw new Exception("controller cannot be find: ${controller}");

	# Load controller
	require_once "controllers/${controller}.php";
	
	# Execute specific action on this controller
	# Instanciate controller
	# Camel case conversion
	$controller_class_name = explode('_', $controller);
	foreach ($controller_class_name as &$item)
		$item = ucfirst($item);
	$controller_class_name = join($controller_class_name) . "Controller";
	$controller_instance = new $controller_class_name;
	
	# Cannot find action within the controller, throw an exception
	if (!method_exists($controller_instance, $action))
		throw new Exception("action cannot be found: ${action}");

	# Call controller method
	call_user_func(array($controller_instance, $action));
	
	# Set content-type and encoding
	header("Content-type: $content_type; charset=UTF-8");

} catch (Exception $e) {
	echo $e;
}

?>