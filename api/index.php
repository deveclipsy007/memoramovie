<?php
/**
 * Router Principal - Memora Movie API
 */

require_once 'config.php';
require_once 'helpers.php';

// Carregar Controllers
require_once 'controllers/ChapterController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/SettingsController.php';
require_once 'controllers/MetricsController.php';
require_once 'controllers/LeadController.php';
require_once 'controllers/QuizController.php';
require_once 'controllers/LogController.php';
require_once 'controllers/SiteContentController.php';
require_once 'controllers/FaqController.php';
require_once 'controllers/ReviewController.php';
require_once 'controllers/PlanController.php';


// Capturar rota e método de forma robusta
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace('index.php', '', $script_name);
$route = '/' . ltrim(str_replace($base_path, '', explode('?', $request_uri)[0]), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Preflight CORS
if ($method === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0);
}

// Instanciar controllers
$chapterController = new ChapterController($pdo);
$authController = new AuthController($pdo);
$settingsController = new SettingsController($pdo);
$metricsController = new MetricsController($pdo);
$leadController = new LeadController($pdo);
$quizController = new QuizController($pdo);
$logController = new LogController($pdo);
$siteController = new SiteContentController($pdo);
$faqController = new FaqController($pdo);
$reviewController = new ReviewController($pdo);
$planController = new PlanController($pdo);


// Roteamento
switch (true) {

    // ======== STATUS ========
    case $route === '/' || $route === '':
        jsonResponse(true, ['message' => 'Memora Movie API online', 'version' => '1.0']);
        break;

    // ======== AUTH ========
    case $route === '/auth/login' && $method === 'POST':
        $authController->login();
        break;

    case $route === '/auth/logout' && $method === 'POST':
        $authController->logout();
        break;

    case $route === '/auth/check' && $method === 'GET':
        $authController->check();
        break;

    // ======== CHAPTERS ========
    case $route === '/chapters' && $method === 'GET':
        $chapterController->list();
        break;

    case $route === '/chapters' && $method === 'POST':
        $chapterController->create();
        break;

    case preg_match('/^\/chapters\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'GET':
        $chapterController->get($matches[1]);
        break;

    case preg_match('/^\/chapters\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'PUT':
        $chapterController->update($matches[1]);
        break;

    case preg_match('/^\/chapters\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'DELETE':
        $chapterController->delete($matches[1]);
        break;

    // ======== LEADS ========
    case $route === '/leads' && $method === 'GET':
        $leadController->list();
        break;

    case $route === '/leads' && $method === 'POST':
        $leadController->create();
        break;

    case $route === '/leads/stats' && $method === 'GET':
        $leadController->stats();
        break;

    case preg_match('/^\/leads\/(\d+)$/', $route, $matches) && $method === 'GET':
        $leadController->get($matches[1]);
        break;

    case preg_match('/^\/leads\/(\d+)\/status$/', $route, $matches) && $method === 'PUT':
        $leadController->updateStatus($matches[1]);
        break;

    case preg_match('/^\/leads\/(\d+)$/', $route, $matches) && $method === 'DELETE':
        $leadController->delete($matches[1]);
        break;

    // ======== SETTINGS ========
    case $route === '/settings' && $method === 'GET':
        $settingsController->getAll();
        break;

    case preg_match('/^\/settings\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'POST':
        $settingsController->update($matches[1]);
        break;

    // ======== METRICS ========
    case $route === '/metrics/track' && $method === 'POST':
        $metricsController->track();
        break;

    case $route === '/metrics/summary' && $method === 'GET':
        $metricsController->summary();
        break;

    // ======== QUIZ ========
    case $route === '/quiz' && $method === 'GET':
        $quizController->list();
        break;

    case $route === '/quiz' && $method === 'POST':
        $quizController->create();
        break;

    case preg_match('/^\/quiz\/(\d+)$/', $route, $matches) && $method === 'GET':
        $quizController->get($matches[1]);
        break;

    case preg_match('/^\/quiz\/(\d+)$/', $route, $matches) && $method === 'PUT':
        $quizController->update($matches[1]);
        break;

    case preg_match('/^\/quiz\/(\d+)$/', $route, $matches) && $method === 'DELETE':
        $quizController->delete($matches[1]);
        break;

    case preg_match('/^\/quiz\/(\d+)\/options$/', $route, $matches) && $method === 'POST':
        $quizController->addOption($matches[1]);
        break;

    case preg_match('/^\/quiz\/options\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'PUT':
        $quizController->updateOption($matches[1]);
        break;

    case preg_match('/^\/quiz\/options\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'DELETE':
        $quizController->deleteOption($matches[1]);
        break;

    // ======== LOGS ========
    case $route === '/logs' && $method === 'GET':
        $logController->list();
        break;

    case $route === '/logs' && $method === 'POST':
        $logController->create();
        break;

    case $route === '/logs' && $method === 'DELETE':
        $logController->clear();
        break;

    case $route === '/logs/stats' && $method === 'GET':
        $logController->stats();
        break;

    // ======== SITE CONTENT (CMS) ========
    case $route === '/site/content' && $method === 'GET':
        $siteController->getAll();
        break;

    case $route === '/site/content' && $method === 'POST':
        $siteController->updateBatch();
        break;

    case preg_match('/^\/site\/content\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'GET':
        $siteController->getSection($matches[1]);
        break;

    case preg_match('/^\/site\/content\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'PUT':
        $siteController->update($matches[1]);
        break;

    // ======== FAQs ========
    case $route === '/faqs' && $method === 'GET':
        $faqController->list();
        break;

    case $route === '/faqs' && $method === 'POST':
        $faqController->create();
        break;

    case $route === '/faqs/reorder' && $method === 'POST':
        $faqController->reorder();
        break;

    case preg_match('/^\/faqs\/(\d+)$/', $route, $matches) && $method === 'PUT':
        $faqController->update($matches[1]);
        break;

    case preg_match('/^\/faqs\/(\d+)$/', $route, $matches) && $method === 'DELETE':
        $faqController->delete($matches[1]);
        break;

    // ======== REVIEWS ========
    case $route === '/reviews' && $method === 'GET':
        $reviewController->list();
        break;

    case $route === '/reviews' && $method === 'POST':
        $reviewController->create();
        break;

    case preg_match('/^\/reviews\/(\d+)$/', $route, $matches) && $method === 'DELETE':
        $reviewController->delete($matches[1]);
        break;

    // ======== PLANS ========
    case $route === '/plans' && $method === 'GET':
        $planController->list();
        break;

    case $route === '/plans' && $method === 'POST':
        $planController->create();
        break;

    case preg_match('/^\/plans\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'GET':
        $planController->get($matches[1]);
        break;

    case preg_match('/^\/plans\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'PUT':
        $planController->update($matches[1]);
        break;

    case preg_match('/^\/plans\/([a-zA-Z0-9_-]+)$/', $route, $matches) && $method === 'DELETE':
        $planController->delete($matches[1]);
        break;


    // ======== 404 ========
    default:
        jsonResponse(false, null, 'Rota não encontrada: ' . $route, 404);
        break;
}
