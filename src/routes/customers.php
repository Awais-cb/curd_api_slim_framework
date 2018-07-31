<?php
// Getting started

// Bringing in request and response object under this files scope
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/*
	// Initiating slim app
	// we are reintiating the app from this file so all routes defined in index.php won't work unless we comment it out
	$app = new \Slim\App;
*/

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

// Get all customers
$app->get('/api/get_customers',function(Request $req,Response $res){
	
	$sql='select * from customers';
	try {	
			// Get db object and make connection
			$db = new db();
			$conn = $db->connect();

			// fetching results
			$stmt = $conn->query($sql);
			$records = $stmt->fetchAll(PDO::FETCH_OBJ);
			$conn = null;
			$res->withStatus(200)->withHeader('Content-Type','application/json')->write(json_encode($records));


			/*
			// WITH SQLI
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
			
		        while($row = $result->fetch_assoc()) {
		         	$records[] = $row ;
			    }

			}else{

			     $records[] = 'No records found!' ;
			}
			$conn->close();	
			$res->withStatus(200)->withHeader('Content-Type', 'application/json')->write(json_encode($records));
			*/


	} catch (Exception $e) {
		
		// returning exception if there is any issue 
		echo '{"error":{"connection_failed":'.$e->getMessage().'}';

	}
});

// Get 1 customers
$app->get('/api/get_customer/{id}',function(Request $req,Response $res){
	
	$id = $req->getAttribute('id');
	$sql="select * from customers where c_id = $id";
	
	try {	
			// Get db object and make connection
			$db = new db();
			$conn = $db->connect();

			// fetching results
			$stmt = $conn->query($sql);
			$record = $stmt->fetch(PDO::FETCH_OBJ);
			$conn = null;
			$res->withStatus(200)->withHeader('Content-Type','application/json')->write(json_encode($record));


	} catch (Exception $e) {
		
		// returning exception if there is any issue 
		echo '{"error":{"connection_failed":'.$e->getMessage().'}';

	}
});

// add 1 customer
$app->post('/api/add_customer',function(Request $req,Response $res){
	
	$fname = $req->getParam('fname');
	$lname = $req->getParam('lname');
	$phone = $req->getParam('phone');
	$email = $req->getParam('email');
	$address = $req->getParam('address');
	$city = $req->getParam('city');
	$state = $req->getParam('state');
	
	$sql="INSERT into customers (c_fname,c_lname,c_phone,c_email,c_address,c_city,c_state) VALUES('$fname','$lname','$phone','$email','$address','$city','$state')";
	
	/*
		// INSERT QUERY using placeholders
		$sql="INSERT into customers (c_fname,c_lname,c_phone,c_email,c_address,c_city,c_state) VALUES(:fname,:lname,:phone,:email,:address,:city,:state)";
	*/

	try {	
			// Get db object and make connection
			$db = new db();
			$conn = $db->connect();

			$stmt = $conn->prepare($sql);
	/*
		// PLACEHOLDER BINDING
		$stmt->bindParam(':fname',$fname);
		$stmt->bindParam(':lname',$lname);
		$stmt->bindParam(':phone',$phone);
		$stmt->bindParam(':email',$email);
		$stmt->bindParam(':address',$address);
		$stmt->bindParam(':city',$city);
		$stmt->bindParam(':state',$state);
	*/
			$stmt->execute();

			$notice=array('notice'=> array('text'=>'Customer added!'));

			$res->withStatus(200)->withHeader('Content-Type','application/json')->write(json_encode($notice));


	} catch (Exception $e) {
		
		// returning exception if there is any issue 
		echo '{"error":{"connection_failed":'.$e->getMessage().'}';

	}
});

// Update customer
$app->put('/api/customer/update/{id}',function(Request $req,Response $res){
	
	$id = $req->getAttribute('id');

	$fname = $req->getParam('fname');
	$lname = $req->getParam('lname');
	$phone = $req->getParam('phone');
	$email = $req->getParam('email');
	$address = $req->getParam('address');
	$city = $req->getParam('city');
	$state = $req->getParam('state');
	
	$sql="UPDATE customers SET 
				c_fname = '$fname',
				c_lname = '$lname',
				c_phone = '$phone',
				c_email = '$email', 
				c_address = '$address',
				c_city = '$city',
				c_state= '$state' WHERE c_id = $id";
	echo $sql;			
	try {	
			// Get db object and make connection
			$db = new db();
			$conn = $db->connect();

			$stmt = $conn->prepare($sql);

			$stmt->execute();

			$notice=array('notice'=> array('text'=>'Customer updated!'));

			$res->withStatus(200)->withHeader('Content-Type','application/json')->write(json_encode($notice));


	} catch (Exception $e) {
		
		// returning exception if there is any issue 
		echo '{"error":{"connection_failed":'.$e->getMessage().'}';

	}
});

// Get 1 customers
$app->delete('/api/customer/delete/{id}',function(Request $req,Response $res){
	
	$id = $req->getAttribute('id');
	$sql="DELETE from customers where c_id = $id";
	
	try {	
			// Get db object and make connection
			$db = new db();
			$conn = $db->connect();

			// fetching results
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$conn = null;

			$notice=array('notice'=> array('text'=>'Customer deleted!'));
			$res->withStatus(200)->withHeader('Content-Type','application/json')->write(json_encode($notice));


	} catch (Exception $e) {
		
		// returning exception if there is any issue 
		echo '{"error":{"connection_failed":'.$e->getMessage().'}';

	}
});


/*
	// ways to send response
    return $response->withStatus(200)
    ->withHeader('Content-Type', 'application/json')
    ->write(json_encode($customers));

	// or just 
	echo json_encode($customers)
*/
?>