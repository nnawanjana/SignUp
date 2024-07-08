<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	//Router::connect('/', array('controller' => 'home', 'action' => 'index'));

	/*
	10/10/2015
	Okkey Sumiyoshi
	additions for Online signup
	*/
	
	Router::redirect('/customers/referral_program', 'https://oa.whgo.net/', array('status' => 302));

	Router::connect('/submissions/submissions/*', array('controller' => 'submissions', 'action' => 'submissions'));
	Router::connect('/finalise', array('controller' => 'submissions', 'action' => 'finalise'));
	Router::connect('/submissions/getStreetType', array('controller' => 'submissions', 'action' => 'street_type'));
	Router::connect('/signup-complete', array('controller' => 'submissions', 'action' => 'signup_complete'));


	Router::connect('/QA_Login', array('controller' => 'qualityassurance', 'action' => 'QA_Login'));
	Router::connect('/QA_Main', array('controller' => 'qualityassurance', 'action' => 'QA_Main'));
	Router::connect('/QA_TestData_Register', array('controller' => 'qualityassurance', 'action' => 'QA_Testdata_Register'));
	Router::connect('/QA_Assessment', array('controller' => 'qualityassurance', 'action' => 'QA_Assessment'));
	Router::connect('/QA_SignUp', array('controller' => 'qualityassurance', 'action' => 'QA_Signup'));
	Router::connect('/QA_Velocify_Request_String', array('controller' => 'qualityassurance', 'action' => 'QA_VelocifyRequestString'));
	Router::connect('/QA_Assess_Velocify_Request', array('controller' => 'qualityassurance', 'action' => 'QA_AssessVelocifyRequest'));
	Router::connect('/QA_View_Snapshot', array('controller' => 'qualityassurance', 'action' => 'QA_View_Snapshot'));
	Router::connect('/QA_Assess_Snapshot', array('controller' => 'qualityassurance', 'action' => 'QA_AssessSnapshot'));
	Router::connect('/QA_Snapshot', array('controller' => 'qualityassurance', 'action' => 'QA_Snapshot'));
	Router::connect('/QA_Issues', array('controller' => 'qualityassurance', 'action' => 'QA_Issues'));
	Router::connect('/QA_Issue_Response', array('controller' => 'qualityassurance', 'action' => 'QA_Issue_Response'));
	Router::connect('/QA_Assess_Velocify_Post', array('controller' => 'qualityassurance', 'action' => 'QA_AssessVelocifyPost'));
	
	

	/* additions ends */


/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
