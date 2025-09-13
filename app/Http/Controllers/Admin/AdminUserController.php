<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Trainer;
use App\Models\Trainee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');
        
        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // Sorting
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $users = $query->paginate(10)->appends($request->except('page'));
        // dd($users);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['admin', 'admission-clerk', 'trainer', 'trainee'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,admission-clerk,trainer,trainee'
        ]);

        $validated['password'] = bcrypt($validated['password']);
        
        // Create the user
        $user = User::create(array_merge(                
                ['email_verified_at' => now()],
                $validated
            ));
        
        if($validated['role'] == 'trainer')
        {
            Trainer::create([
                'user_id' => $user->id,
                'cnic' => '',
                'gender' => 'N/A',
                'contact' => '',
                'specialization' => '',
                'experience_years' => 0,
                'qualification' => '',
                'phone' => '',
                'address' => ''
                
            ]);
        } elseif ($validated['role'] == 'trainee')
        {
            Trainee::create([
                'user_id' => $user->id,
                'cnic' => '',
                'gender' => 'N/A',
                'date_of_birth' => '2000-01-01',
                'contact' => '',
                'emergency_contact' => '',
                'domicile' => '',
                'education_level' => '',
                'address' => '',            
            ]);
        }


        // Assign the role using Spatie Permission
        $user->assignRole($validated['role']);

        
        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        // Get the user's sessions directly from the database
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                return [
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity),
                    'device' => $this->getDeviceInfo($session->user_agent),
                    'browser' => $this->getBrowserInfo($session->user_agent),
                ];
            });
        
        // Get the last login info
        $lastLogin = $sessions->isNotEmpty() ? $sessions->first() : null;
        
        return view('admin.users.show', compact('user', 'sessions', 'lastLogin'));
    }

    /**
     * Extract device information from user agent string
     */
    private function getDeviceInfo($userAgent)
    {
        if (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            return 'iOS Device';
        } elseif (preg_match('/Android/', $userAgent)) {
            return 'Android Device';
        } elseif (preg_match('/Windows/', $userAgent)) {
            return 'Windows PC';
        } elseif (preg_match('/Macintosh|Mac OS/', $userAgent)) {
            return 'Mac';
        } elseif (preg_match('/Linux/', $userAgent)) {
            return 'Linux PC';
        } else {
            return 'Unknown Device';
        }
    }

    /**
     * Extract browser information from user agent string
     */
    private function getBrowserInfo($userAgent)
    {
        if (preg_match('/Chrome/', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Firefox/', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Safari/', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/Opera/', $userAgent)) {
            return 'Opera';
        } else {
            return 'Unknown Browser';
        }
    }
    
    public function edit(User $user)
    {
        $roles = Role::whereIn('name', ['admin', 'admission-clerk', 'trainer', 'trainee'])->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:admin,admission-clerk,trainer,trainee'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Update user details
        $user->update($validated);
        
        // Sync roles (remove all existing roles and assign the new one)
        $user->syncRoles([$validated['role']]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

}