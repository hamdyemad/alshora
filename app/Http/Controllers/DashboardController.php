<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lawyer;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\News;
use App\Models\Review;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:dashboard.view');
    }

    public function index()
    {
        // Statistics Cards Data
        $totalUsers = User::count();
        
        // Count admins by checking if they have any roles
        $totalAdmins = User::has('roles')->count();
        
        // Lawyers Statistics
        $totalLawyers = Lawyer::count();
        $activeLawyers = Lawyer::where('active', true)->count();
        
        // Customers Statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('active', true)->count();
        
        // Subscriptions Statistics (active column is boolean)
        $activeSubscriptions = Subscription::where('active', true)->count();
        $totalSubscriptions = Subscription::count();
        
        // Reviews Statistics (using 'approved' column instead of 'status')
        $totalReviews = Review::count();
        $pendingReviews = Review::where('approved', false)->count();
        $approvedReviews = Review::where('approved', true)->count();
        
        // Appointments Statistics
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $confirmedAppointments = Appointment::where('status', 'confirmed')->count();
        
        // News Statistics (using 'active' column instead of 'is_published')
        $totalNews = News::count();
        $activeNews = News::where('active', true)->count();

        return view('pages.dashboard.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalLawyers',
            'activeLawyers',
            'totalCustomers',
            'activeCustomers',
            'activeSubscriptions',
            'totalSubscriptions',
            'totalReviews',
            'pendingReviews',
            'approvedReviews',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'totalNews',
            'activeNews'
        ));
    }
}
