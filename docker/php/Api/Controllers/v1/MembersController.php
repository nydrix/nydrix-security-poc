<?php
/**
 * @package    PHP MSAL Poc
 * @author     Nicolas Hindryckx <nicolas@nydrix.be>
 * @copyright  MIT
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

require_once API_DIR_ROOT . '/Domain/Members/Member.php'; 
require_once API_DIR_ROOT . '/Core/Utils/ArrayUtils.php'; 
require_once API_DIR_ROOT . '/Core/Tools.php'; 

use MSALPoc\Members\Member as MemberObject;
use MSALPoc\Core\Utils\ArrayUtils;
use MSALPoc\Validate;
use Psr\Container\ContainerInterface;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MembersController {

    private $testMemberList = array();
	private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $member1 = new MemberObject();
        $member1->id = 1;
        $member1->firstname = 'Nicolas';
        $member1->lastname = 'Hindryckx';
        $member1->license = 100420;
        $member1->license_extension_requested = false;

        $this->testMemberList[] = $member1;

        $member2 = new MemberObject();
        $member2->id = 2;
        $member2->firstname = 'Rita';
        $member2->lastname = 'Gessof';
        $member2->license = 100425;
        $member2->license_extension_requested = false;

        $this->testMemberList[] = $member2;

        $member3 = new MemberObject();
        $member3->id = 3;
        $member3->firstname = 'Kelly';
        $member3->lastname = 'Verbier';
        $member3->license = 100555;
        $member3->license_extension_requested = false;

        $this->testMemberList[] = $member3;
    }

	public function getAll(Request $request, Response $response, array $args): Response {
		
		$result = [];
		$result['roles'] = $request->getAttribute('token')["roles"] ?? [];
		$result['clubs'] = $request->getAttribute('clubs');
		$result['members'] = $this->testMemberList;

		if(!in_array("MemberManager", $result['roles'])) {
			return $response->withStatus(401)->withJson([
				'status' => 'Not allowed',
				'message' => 'you are not allowed.'
			]);
		}

		return  $response->withJson($result, 200, JSON_PRETTY_PRINT);
	}

	public function add(Request $request, Response $response, array $args): Response {
	
		$payload = $request->getParsedBody(); 

		$firstname = ArrayUtils::get($payload, 'firstname');
        $lastname = ArrayUtils::get($payload, 'lastname');
		

		if (!Validate::isGenericName($lastname)) {
			return $response->withStatus(400)->withJson([
				'status' => 'Validation Error',
				'message' => 'Enter a valid member lastname'
			]);
		}

        if (!Validate::isGenericName($firstname)) {
			return $response->withStatus(400)->withJson([
				'status' => 'Validation Error',
				'message' => 'Enter a valid member firstname'
			]);
		}

		$id = count($this->testMemberList) + 1;

        $highestLicense = max(array_column($this->testMemberList, 'license'));
        $license = $highestLicense + 1;

	
		$member = new MemberObject();
		$member->firstname = $firstname;
        $member->lastname = $lastname;
		$member->license = $license;
		$member->id = $id;

        $this->testMemberList[] = $member;

		$ok = true;
        //$member->save();
		// or $member->add();

		if (!$ok) {
			return $response->withStatus(500)->withJson([
				'status' => 'Application Error',
				'message' => 'Unable to create member'
			]);
		}

		return  $response->withJson($member, 200, JSON_PRETTY_PRINT);
	}

	public function get(Request $request, Response $response, array $args): Response {
		$memberId = $args['memberId'];
		
		$member = array_values(array_filter($this->testMemberList, function($value) use ($memberId) {
			echo $value->id == $memberId;
            return $value->id == $memberId;
        }))[0];

		
		

		if(!Validate::isLoadedObject($member)) {
			return $response->withStatus(404)->withJson([
				'status' => 'Not found',
				'message' => 'Member was not found'
			]);
		}

		return $response->withJson($member, 200, JSON_PRETTY_PRINT);
	}

	// public function update(Request $request, Response $response, array $args): Response {
	// 	$memberId = $args['memberId'];

	// 	$api = $this->api;
	// 	$payload = $api->request()->post(); 

		
    //     $member = array_filter($this->testMemberList, function($value) {
    //         return $value->id == $memberId;
    //     });

	// 	if(!Validate::isLoadedObject($member)) {
	// 		$api->response->setStatus(404);
	// 		return $api->response([
	// 			'success' => false,
	// 			'message' => 'Member was not found'
	// 		]);
	// 	}

    //     // no id or license update allowed, so we skip it

    //     if (ArrayUtils::has($payload, 'lastname')) {
    //         $lastname = ArrayUtils::get($payload, 'lastname');
    //         if (!Validate::isGenericName($lastname)) {
    //             return $api->response([
    //                 'success' => false,
    //                 'message' => 'Enter a valid member lastname'
    //             ]);
    //         }
    //     }

    //     if (ArrayUtils::has($payload, 'firstname')) {
    //         $firstname = ArrayUtils::get($payload, 'firstname');
    //         if (!Validate::isGenericName($lastname)) {
    //             return $api->response([
    //                 'success' => false,
    //                 'message' => 'Enter a valid member firstname'
    //             ]);
    //         }
    //     }


	// 	return $api->response([
	// 		'success' => false,
	// 		'message' => 'Unable to update member'
	// 	]);

    //     $member[0]->firstname = $firstname;
    //     $member[0]->lastname = $lastname;

	// 	$ok = true;
    //     //$member->save();
	// 	// or member->update()
		
	// 	if (!$ok) {
	// 		return $api->response([
	// 			'success' => false,
	// 			'message' => 'Unable to update member'
	// 		]);
	// 	}

	// 	return $api->response([
	// 		'success' => true,
	// 		'message' => 'Member updated successfully'
	// 	]);
	// }

    // public function extendLicense($memberId ) {
	// 	$api = $this->api;

    //     $member = array_filter($this->testMemberList, function($value) {
    //         return $value->id == $memberId;
    //     });

	// 	if(!Validate::isLoadedObject($member[0])) {
	// 		$api->response->setStatus(404);
	// 		return $api->response([
	// 			'success' => false,
	// 			'message' => 'Member was not found'
	// 		]);
	// 	}

    //     $member[0]->license_extension_requested = true;

	// 	$ok = true;
    //     //$member->save();
	// 	// or member->update()
		
	// 	if (!$ok) {
	// 		return $api->response([
	// 			'success' => false,
	// 			'message' => 'Unable to update member'
	// 		]);
	// 	}

	// 	return $api->response([
	// 		'success' => true,
	// 		'message' => 'Member updated successfully'
	// 	]);
	// }

	// public function delete( $memberId ) {
	// 	$api = $this->api;

    //     $found = false;  
    //     foreach($this->testMemberList as $key => $value) {
    //         if ($value->id == $memberId) {
    //             $found = true;
    //             break;
    //         }
    //     }

    //     if(!$found) {
	// 		$api->response->setStatus(404);
	// 		return $api->response([
	// 			'success' => false,
	// 			'message' => 'Member was not found'
	// 		]);
	// 	}

    //     if ($found) unset($this->testMemberList[$key]);

	// 	$ok = true;
    //     //$member->delete();

	// 	if (!$ok) {
	// 		return $api->response([
	// 			'success' => false,
	// 			'message' => 'Unable to delete member'
	// 		]);
	// 	}

	// 	return $api->response([
	// 		'success' => true,
	// 		'message' => 'Member deleted successfully'
	// 	]);
	// }
}