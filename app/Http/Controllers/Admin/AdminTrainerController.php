<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Trainer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminTrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::with(['user'])->paginate(10);
        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => '',
            'email_verified_at' => now(),
            'cnic' => 'required|string|max:15|unique:trainees,cnic',
            'gender' => 'required|in:Male,Female,Other',
            'contact' => 'required|string|max:20',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'qualification' => 'required|in:Secondary,Intermediate,Bachelor,Master,Doctorate',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        $validated['password'] = bcrypt($validated['name']);
        $validated['role'] = 'trainer';
        
        $user = User::create($validated);        

        $user->assignRole('trainer');

        $trainer = Trainer::create(array_merge(
                ['user_id' => $user->id],
                $validated
            ));


        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer created successfully.');
    }

    public function show(User $trainer)
    {
        $trainer->load('trainer');
        $trainerData = $trainer->trainer->first();
        
        return view('admin.trainers.show', compact(['trainer', 'trainerData']));
    }

    public function edit(User $trainer)
    {
        $trainer->load('trainer');
        $trainerData = $trainer->trainer->first();
        // dd($trainerData);
        return view('admin.trainers.edit',compact(['trainer', 'trainerData']));
    }

    public function update(Request $request, User $trainer)
    {
        $trainer->load('trainer');
        $trainerProfile = $trainer->trainer;
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $trainer->id,
            'password' => 'nullable|min:8|confirmed',
            'specialization' => 'required|string|max:255',
            'experience' => 'required|integer',
            'qualification' => 'required|string|max:255',
            'phone' => 'required'
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Trainer User
        
        $trainer->update($validated);
        
        
        Trainer::where('user_id', $trainer->id)->update([
            'specialization' => $validated['specialization'],
            'experience_years' => $validated['experience'],
            'qualification' => $validated['qualification'],
            'contact' => $validated['phone']
        ]);
        

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer updated successfully.');
    }

    public function destroy(User $trainer)
    {
        $trainer->load('trainer');
        $trainerProfile = $trainer->trainer;
            
        $trainer->delete();
        Trainer::where('user_id', $trainer->id)->delete();
        

        return redirect()->route('admin.trainers.index')
            ->with('success', 'Trainer deleted successfully.');
    }
}