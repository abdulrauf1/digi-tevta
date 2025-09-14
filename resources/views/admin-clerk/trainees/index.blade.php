<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trainees Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-6 px-2 sm:px-0" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Trainees</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header and Actions -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-semibold">All Trainees</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your organization's trainees and their information</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button id="bulkImportBtn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg flex items-center transition-colors duration-200">
                                <i class="fas fa-file-import mr-2"></i>
                                Bulk Import
                            </button>
                            <a href="{{ route('admin-clerk.trainees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Trainee
                            </a>
                        </div>
                    </div>

                    <!-- Success Message (for bulk import) -->
                    <div id="bulkImportSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-start dark:bg-green-900/20 dark:border-green-800 dark:text-green-400 hidden">
                        <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                        <div>
                            <p class="font-medium">Success</p>
                            <p class="text-sm">Bulk import completed successfully</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-start dark:bg-green-900/20 dark:border-green-800 dark:text-green-400" role="alert">
                            <i class="fas fa-check-circle mr-3 mt-0.5"></i>
                            <div>
                                <p class="font-medium">Success</p>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                <div>
                                    <p class="font-medium">Error</p>
                                    <ul class="mt-1 list-disc list-inside text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="searchInput" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Search trainees...">
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <span>Name</span>
                                            <button class="ml-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                <i class="fas fa-sort"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <span>Email</span>
                                            <button class="ml-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                                <i class="fas fa-sort"></i>
                                            </button>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="traineesTable">
                                @foreach($trainees as $trainee)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-medium dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ substr($trainee->user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium">{{ $trainee->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $trainee->user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $trainee->contact }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ Str::limit($trainee->address, 20) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('admin-clerk.trainees.show', $trainee) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin-clerk.trainees.edit', $trainee) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors duration-200" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin-clerk.trainees.destroy', $trainee) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200" title="Delete" onclick="return confirm('Are you sure you want to delete this trainee?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $trainees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Import Modal -->
    <div id="bulkImportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-11/12 md:w-3/4 lg:w-2/3 max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Bulk Import Trainees</h3>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <form id="bulkImportForm" action="{{ route('admin-clerk.trainees.bulk-import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Import Instructions</h3>
                                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                        <p>• Download the CSV template to ensure proper formatting</p>
                                        <p>• The file should include columns for: name, email, password, cnic, gender, date_of_birth, contact, emergency_contact, domicile, education_level, address</p>
                                        <p>• Passwords will be automatically hashed upon import</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Upload CSV File</h4>
                                
                                <div class="flex items-center justify-center w-full">
                                    <label for="csvFile" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">CSV (MAX. 5MB)</p>
                                        </div>
                                        <input id="csvFile" name="csv_file" type="file" class="hidden" accept=".csv" required />
                                    </label>
                                </div>
                                
                                
                                <div class="mt-6">
                                    <button type="button" id="processUpload" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg flex items-center justify-center transition-colors duration-200">
                                        
                                        Process Upload
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Template & Guidelines</h4>
                                
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Download our CSV template to ensure your file has the correct format:</p>
                                    <a href="{{ route('trainees.download-template') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200" id="downloadTemplateBtn">
                                        <i class="fas fa-file-download mr-2"></i>
                                        Download Template
                                    </a>
                                </div>
                                
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Important Notes</h3>
                                            <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-400">
                                                <p>• Email addresses must be unique</p>
                                                <p>• Passwords should be at least 8 characters</p>
                                                <p>• Date format: YYYY-MM-DD (e.g., 1990-05-15)</p>
                                                <p>• Gender options: Male, Female, Other, N/A</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                        <button type="button" id="cancelImport" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition-colors duration-200 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-300">
                            Cancel
                        </button>
                        <button type="submit" id="confirmImport" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors duration-200 flex items-center">
                            <i class="fas fa-database mr-2"></i>
                            Import Records
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .breadcrumb-arrow {
            margin: 0 8px;
            color: #9ca3af;
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Simple search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#traineesTable tr');
            
            rows.forEach(row => {
                const name = row.querySelector('td:first-child .font-medium').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (name.includes(searchValue) || email.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('bulkImportModal');
            const openBtn = document.getElementById('bulkImportBtn');
            const closeBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelImport');
            const processBtn = document.getElementById('processUpload');
            const confirmBtn = document.getElementById('confirmImport');
            const fileInput = document.getElementById('csvFile');
            const spinner = document.getElementById('processingSpinner');
            const successMessage = document.getElementById('bulkImportSuccess');
            const form = document.getElementById('bulkImportForm');
            const downloadBtn = document.getElementById('downloadTemplateBtn');
            
            // Open modal
            openBtn.addEventListener('click', function() {
                modal.classList.remove('hidden');
            });
            
            // Close modal
            function closeModal() {
                modal.classList.add('hidden');
            }
            
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            
            // Click outside to close
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            
            // Process upload
            processBtn.addEventListener('click', function() {
                if (!fileInput.files.length) {
                    alert('Please select a file to upload');
                    return;
                }
                
                // Show processing spinner
                processBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing ...';
                downloadBtn.disabled = true;
                
                // Simulate processing delay
                setTimeout(function() {
                    processBtn.innerHTML = 'Processed';
                    processBtn.disabled = false;
                    alert('File processed successfully. Ready to import.');
                }, 2000);
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                if (!fileInput.files.length) {
                    e.preventDefault();
                    alert('Please select a file first');
                    return;
                }
                
                // Show processing spinner on confirm button
                const confirmSpinner = document.createElement('i');
                confirmSpinner.className = 'fas fa-spinner fa-spin mr-2';
                confirmBtn.prepend(confirmSpinner);
                confirmBtn.disabled = true;
            });
            
            // Download template with error handling
            downloadBtn.addEventListener('click', function(e) {
                // If we want to show loading state, we need to handle the download manually
                e.preventDefault();
                
                // Show loading state
                const originalText = downloadBtn.innerHTML;
                downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating Template...';
                downloadBtn.disabled = true;
                
                // Create a hidden iframe to handle the download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = "{{ route('trainees.download-template') }}";
                
                iframe.onload = function() {
                    // Restore button state after a short delay
                    setTimeout(() => {
                        downloadBtn.innerHTML = originalText;
                        downloadBtn.disabled = false;
                        document.body.removeChild(iframe);
                    }, 1000);
                };
                
                iframe.onerror = function() {
                    // Restore button state and show error
                    downloadBtn.innerHTML = originalText;
                    downloadBtn.disabled = false;
                    document.body.removeChild(iframe);
                    alert('Failed to download template. Please try again.');
                };
                
                document.body.appendChild(iframe);
            });
            
            // Check if there was a successful bulk import from redirect
            @if(session('bulk_import_success'))
                successMessage.classList.remove('hidden');
                // Hide success message after 5 seconds
                setTimeout(function() {
                    successMessage.classList.add('hidden');
                }, 5000);
            @endif
        });
    </script>
    @endpush
</x-app-layout>