<?php
/**
 * Belzac CRM - API Router
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../../app/models/Auth.php';
require_once __DIR__ . '/../../app/models/Contact.php';
require_once __DIR__ . '/../../app/models/Campaign.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Parse the route
$basePath = '/belzac-crm/public/api';
$route = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));
$route = trim($route, '/');
$parts = explode('/', $route);

$endpoint = $parts[0] ?? '';
$id = $parts[1] ?? null;

// Get request data
$data = json_decode(file_get_contents('php://input'), true) ?? [];

// Initialize auth
$auth = new Auth();

try {
    // Public endpoints (no auth required)
    if ($endpoint === 'auth') {
        handleAuth($auth, $parts[1] ?? '', $data);
    }
    // Protected endpoints
    else {
        $auth->requireAuth();
        $userId = $_SESSION['user_id'];

        switch ($endpoint) {
            case 'contacts':
                handleContacts($userId, $id, $requestMethod, $data);
                break;
            case 'campaigns':
                handleCampaigns($userId, $id, $requestMethod, $data);
                break;
            case 'dashboard':
                handleDashboard($userId);
                break;
            default:
                respond(['error' => 'Endpoint not found'], 404);
        }
    }
} catch (Exception $e) {
    respond(['error' => $e->getMessage()], 500);
}

// ===== AUTH HANDLERS =====
function handleAuth($auth, $action, $data) {
    switch ($action) {
        case 'login':
            $result = $auth->login($data['email'] ?? '', $data['password'] ?? '');
            respond($result, $result['success'] ? 200 : 401);
            break;
        case 'register':
            $result = $auth->register($data);
            respond($result, $result['success'] ? 201 : 400);
            break;
        case 'logout':
            $result = $auth->logout();
            respond($result);
            break;
        case 'me':
            $user = $auth->user();
            respond(['success' => true, 'user' => $user]);
            break;
        default:
            respond(['error' => 'Invalid auth action'], 400);
    }
}

// ===== CONTACT HANDLERS =====
function handleContacts($userId, $id, $method, $data) {
    $contact = new Contact();

    switch ($method) {
        case 'GET':
            if ($id) {
                $result = $contact->getById($id, $userId);
                respond(['success' => true, 'contact' => $result]);
            } else {
                $filters = [
                    'status' => $_GET['status'] ?? null,
                    'search' => $_GET['search'] ?? null,
                    'limit' => $_GET['limit'] ?? 50,
                    'offset' => $_GET['offset'] ?? 0
                ];
                $contacts = $contact->getAll($userId, $filters);
                $total = $contact->count($userId);
                respond(['success' => true, 'contacts' => $contacts, 'total' => $total]);
            }
            break;

        case 'POST':
            $contactId = $contact->create($data, $userId);
            if ($contactId) {
                respond(['success' => true, 'contact_id' => $contactId], 201);
            } else {
                respond(['success' => false, 'message' => 'Failed to create contact'], 400);
            }
            break;

        case 'PUT':
            if (!$id) {
                respond(['error' => 'Contact ID required'], 400);
            }
            $updated = $contact->update($id, $data, $userId);
            respond(['success' => $updated > 0, 'updated' => $updated]);
            break;

        case 'DELETE':
            if (!$id) {
                respond(['error' => 'Contact ID required'], 400);
            }
            $deleted = $contact->delete($id, $userId);
            respond(['success' => $deleted > 0, 'deleted' => $deleted]);
            break;

        default:
            respond(['error' => 'Method not allowed'], 405);
    }
}

// ===== CAMPAIGN HANDLERS =====
function handleCampaigns($userId, $id, $method, $data) {
    $campaign = new Campaign();

    switch ($method) {
        case 'GET':
            if ($id) {
                if (isset($_GET['stats'])) {
                    $stats = $campaign->getStats($id, $userId);
                    respond(['success' => true, 'stats' => $stats]);
                } else {
                    $result = $campaign->getById($id, $userId);
                    respond(['success' => true, 'campaign' => $result]);
                }
            } else {
                $filters = ['status' => $_GET['status'] ?? null];
                $campaigns = $campaign->getAll($userId, $filters);
                respond(['success' => true, 'campaigns' => $campaigns]);
            }
            break;

        case 'POST':
            $campaignId = $campaign->create($data, $userId);
            if ($campaignId) {
                respond(['success' => true, 'campaign_id' => $campaignId], 201);
            } else {
                respond(['success' => false, 'message' => 'Failed to create campaign'], 400);
            }
            break;

        case 'PUT':
            if (!$id) {
                respond(['error' => 'Campaign ID required'], 400);
            }
            $updated = $campaign->update($id, $data, $userId);
            respond(['success' => $updated > 0]);
            break;

        case 'DELETE':
            if (!$id) {
                respond(['error' => 'Campaign ID required'], 400);
            }
            $deleted = $campaign->delete($id, $userId);
            respond(['success' => $deleted > 0]);
            break;

        default:
            respond(['error' => 'Method not allowed'], 405);
    }
}

// ===== DASHBOARD HANDLER =====
function handleDashboard($userId) {
    $contact = new Contact();
    
    $stats = [
        'total_contacts' => $contact->count($userId),
        'subscribed' => $contact->count($userId, 'subscribed'),
        'unsubscribed' => $contact->count($userId, 'unsubscribed'),
        'bounced' => $contact->count($userId, 'bounced'),
    ];
    
    respond(['success' => true, 'stats' => $stats]);
}

// Helper function to send JSON response
function respond($data, $code = 200) {
    http_response_code($code);
    echo json_encode($data);
    exit;
}
