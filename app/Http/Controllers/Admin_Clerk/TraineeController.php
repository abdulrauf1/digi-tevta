<?php

namespace App\Http\Controllers\Admin_Clerk;

use App\Models\User;
use App\Models\Trainee;
use App\Http\Controllers\Controller;    
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


use Illuminate\Http\Request;

class TraineeController extends Controller
{
    public function index()
    {
        $trainees = Trainee::with(['user'])->paginate(10);        
        return view('admin-clerk.trainees.index', compact('trainees'));
    }

    public function create()
    {
        return view('admin-clerk.trainees.create');
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'phone' => 'required|string|max:20',
            'cnic' => 'required|string|max:15|unique:trainees,cnic',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'emergency_contact' => 'required|string|max:20',
            'domicile' => 'required|string|max:100',
            'education_level' => 'required|in:Primary,Secondary,Intermediate,Bachelor,Master,Doctorate'
        ], [
            'cnic.unique' => 'The CNIC has already been registered.',
            'date_of_birth.before' => 'The date of birth must be a date before today.',
            'education_level.in' => 'Please select a valid education level.',
        ]);

        
        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'trainee';
        
        $user = User::create($validated);
        
        $user->assignRole('trainee');
            
        Trainee::create([
            'user_id' => $user->id,
            'cnic' => $validated['cnic'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'contact' => $validated['phone'],
            'emergency_contact' => $validated['emergency_contact'],
            'domicile' => $validated['domicile'],
            'education_level' => $validated['education_level'],
            'address' => $validated['address'],            
        ]);

        return redirect()->route('admin-clerk.trainees.index')
            ->with('success', 'Trainee created successfully.');
    }

    public function show(User $trainee)
    {
        return view('admin-clerk.trainees.show', compact('trainee'));
    }

    public function edit(User $trainee)
    {
        return view('admin-clerk.trainees.edit', compact('trainee'));
    }

    public function update(Request $request, User $trainee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $trainee->id,
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $trainee->update($validated);
        
        return redirect()->route('admin-clerk.trainees.index')
            ->with('success', 'Trainee updated successfully.');
    }

    public function destroy(User $trainee)
    {
        $trainee->delete();
        
        return redirect()->route('admin-clerk.trainees.index')
            ->with('success', 'Trainee deleted successfully.');
    }

    /**
     * Download CSV template for bulk import
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="trainees_template.csv"',
            'Access-Control-Allow-Origin' => '*', // For CORS
        ];

        $template = "name,email,password,cnic,gender,date_of_birth,contact,emergency_contact,domicile,education_level,address\n";
        $template .= "John Doe,john@example.com,password123,42201-1234567-1,Male,1990-05-15,03001234567,03009876543,Islamabad,Bachelor,123 Main Street\n";

        return response()->make($template, 200, $headers);
    }

    /**
     * Process bulk import of trainees
     */
    public function bulkImport(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',            
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('csv_file');
        
        // Process the CSV file
        $importCount = 0;
        $errors = [];
        
        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            // Skip headers if present
            
            $rowNumber = 2;
            
            while (($data = fgetcsv($handle)) !== false) {
                // Skip empty rows
                if (empty(array_filter($data))) {
                    $rowNumber++;
                    continue;
                }
                
                // Validate row data
                if (count($data) < 10) {
                    $errors[] = "Row $rowNumber: Insufficient data columns";
                    $rowNumber++;
                    continue;
                }
                
                try {
                    // Validate user data
                    $userValidator = Validator::make([
                        'name' => $data[0],
                        'email' => $data[1],
                    ], [
                        'name' => 'required|string|max:255',
                        'email' => 'required|email|unique:users,email',
                    ]);
                    
                    if ($userValidator->fails()) {
                        $errors[] = "Row $rowNumber: User validation failed - " . implode(', ', $userValidator->errors()->all());
                        $rowNumber++;
                        continue;
                    }
                    
                    // Validate trainee data
                    $traineeValidator = Validator::make([
                        'cnic' => $data[2],
                        'gender' => $data[3],
                        'date_of_birth' => $data[4],
                        'contact' => $data[5],
                        'emergency_contact' => $data[6],
                        'domicile' => $data[7],
                        'education_level' => $data[8],
                        'address' => $data[9],
                    ], [
                        'cnic' => 'required|string|max:20|unique:trainees,cnic',
                        'gender' => 'required|in:Male,Female,Other,N/A',
                        'date_of_birth' => 'required|date',
                        'contact' => 'required|string|max:20',
                        'emergency_contact' => 'required|string|max:20',
                        'domicile' => 'required|string|max:100',
                        'education_level' => 'required|string|max:100',
                        'address' => 'required|string',
                    ]);
                    
                    if ($traineeValidator->fails()) {
                        $errors[] = "Row $rowNumber: Trainee validation failed - " . implode(', ', $traineeValidator->errors()->all());
                        $rowNumber++;
                        continue;
                    }
                    
                    // Create user
                    $user = User::create([
                        'name' => $data[0],
                        'email' => $data[1],
                        'password' => Hash::make($data[0]),
                    ]);
                    
                    $user->assignRole('trainee');
                    // Create trainee
                    Trainee::create([
                        'user_id' => $user->id,
                        'cnic' => $data[2],
                        'gender' => $data[3],
                        'date_of_birth' => $data[4],
                        'contact' => $data[5],
                        'emergency_contact' => $data[6],
                        'domicile' => $data[7],
                        'education_level' => $data[8],
                        'address' => $data[9],
                    ]);
                    
                    $importCount++;
                } catch (\Exception $e) {
                    $errors[] = "Row $rowNumber: " . $e->getMessage();
                }
                
                $rowNumber++;
            }
            
            fclose($handle);
        }
        
        if ($importCount > 0) {
            return redirect()->route('admin-clerk.trainees.index')
                ->with('bulk_import_success', true)
                ->with('success', "Successfully imported $importCount trainees" . (count($errors) > 0 ? " with " . count($errors) . " errors" : ""));
        } else {
            return redirect()->back()
                ->withErrors(['import' => 'No trainees were imported. ' . implode('; ', $errors)])
                ->withInput();
        }
    }
}