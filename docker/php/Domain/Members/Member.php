<?php 
/**
 * @package    PHP MSAL Poc
 * @author     Nicolas Hindryckx <nicolas@nydrix.be>
 * @copyright  MIT
 * @version    1.0.0
 * @since      File available since Release 1.0.0
 */

namespace MSALPoc\Members;

require_once API_DIR_ROOT . '/Domain/Shared/ObjectModel.php'; 

use MSALPoc\ObjectModel;

class Member extends ObjectModel {
	/** @var $id Member ID */
	public $id;

    /** @var $license */
	public $license;
	
	/** @var string $firstname */
	public $firstname;

    /** @var string $lastname */
	public $lastname;

    /** @var bool $license_extension_requested */
    public $license_extension_requested;

     /**
     * constructor.
     *
     * @param null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
	}
}