<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Provider;

class AdminController extends Controller
{
   public function index()
   {
       // Get statistics for dashboard
       $totalUsers = User::count();
       $totalAppointments = Appointment::count();
       $totalServices = Service::count();
       $totalProviders = Provider::count();
       
       // Get recent appointments
       $recentAppointments = Appointment::with('client', 'provider.user', 'service')
           ->latest()
           ->take(5)
           ->get();
       
       return view('admin.dashboard', compact('totalUsers', 'totalAppointments', 'totalServices', 'totalProviders', 'recentAppointments'));
   }

   public function users()
   {
       $users = User::with('roles')->latest()->paginate(10);
       return view('admin.users', compact('users'));
   }
   
   public function settings()
   {
       return view('admin.settings');
   }
}
