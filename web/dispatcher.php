<?php

require_once "system/include.php";
require_once '../vendor/autoload.php';

global $controller, $action, $content_type, $data, $response_code;

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

# Search in controllers directory if we have a match between a filename and 
if (!is_readable("controllers/${controller}.php"))
	error(404, "Controller cannot be found: ${controller}");

# Load controller
require_once "controllers/${controller}.php";

# Set default content_type
$content_type = "application/json";
	
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
	error(403, "Action cannot be found: ${action}");

# Call controller method
$response = call_user_func(array($controller_instance, $action));

if(empty($response) && !$response_code)
	$response_code = 204;

answer($response, $response_code);

?>