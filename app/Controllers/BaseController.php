<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Exceptions\RedirectException;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        
        // Check if logged-in user still exists in database (not soft-deleted)
        $this->checkUserExists();
    }

    /**
     * Check if the logged-in user still exists in the database
     * If user is soft-deleted, automatically log them out
     */
    protected function checkUserExists()
    {
        $session = \Config\Services::session();
        
        // Only check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return;
        }

        $userID = $session->get('userID');
        
        // If no userID in session, something is wrong
        if (!$userID) {
            $session->destroy();
            return;
        }

        // Check if user still exists in database (not soft-deleted)
        $userModel = new \App\Models\UserModel();
        
        // find() with soft deletes enabled will return null if deleted
        $user = $userModel->find($userID);
        
        // If user doesn't exist or is soft-deleted, log them out
        if (!$user) {
            $session->setFlashdata('error', 'Your account has been removed. You have been logged out.');
            $session->destroy();
            
            // Redirect to login if not already on login page
            $currentUri = uri_string();
            if ($currentUri !== 'login' && strpos($currentUri, 'login') === false) {
                // Throw RedirectException which CodeIgniter will handle properly
                throw new RedirectException(redirect()->to(base_url('login')));
            }
        }
    }
}
