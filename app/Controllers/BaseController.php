<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;

    // Required helpers
    protected $helpers = ['url', 'form', 'session'];

    protected $notifications = [];
    protected $unreadCount = 0;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Start session
        if (!session()->has('initialized')) {
            session()->set('initialized', true);
        }

        // ✅ Load Notifications Globally (Only if Superadmin logged in)
        if (session()->get('superadmin_id')) {

            $notificationModel = new \App\Models\NotificationModel();

            $this->notifications = $notificationModel
                ->where('status', 'unread')
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->findAll();

            $this->unreadCount = $notificationModel
                ->where('status', 'unread')
                ->countAllResults();
        }
    }

    // ✅ Function to pass data automatically to views
    protected function renderView($view, $data = [])
    {
        $data['notifications'] = $this->notifications;
        $data['unreadCount']   = $this->unreadCount;

        return view($view, $data);
    }
}
