<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Issued Certificates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 50%, #1d4ed8 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        .sidebar-collapsed {
            width: 80px !important;
        }
        .sidebar-collapsed #toggleSidebar .collapse-icon {
            display: none;
        }
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .logo-text,
        .sidebar-collapsed .user-profile .user-details {
            display: none;
        }
        .sidebar-collapsed .nav-item {
            justify-content: center;
        }

        /* Enhanced navigation hover effects */
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: scale(1.02);
        }

        .nav-icon-container {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-icon-container:hover {
            background-color: rgba(59, 130, 246, 0.8);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .nav-text {
            transition: color 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover .nav-text {
            color: white;
        }

        .nav-item:hover .nav-icon-container i {
            color: white;
        }

        .nav-item:hover .ml-auto {
            opacity: 1;
            transform: translateX(4px);
        }

        /* Enhanced logo and user profile hover effects */
        #toggleSidebar:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        #toggleSidebar:hover .bg-gradient-to-r {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        #toggleSidebar:hover .logo-text {
            color: rgb(219, 234, 254);
        }

        #toggleSidebar:hover .collapse-icon {
            transform: scale(1.1);
        }

        #toggleSidebar:hover .collapse-icon i {
            color: white;
        }

        .user-profile:hover {
            background-color: rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .user-profile:hover .bg-gradient-to-r {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(1.05);
        }

        .user-profile:hover .font-semibold {
            color: rgb(219, 234, 254);
        }

        .user-profile:hover .text-xs {
            color: rgb(147, 197, 253);
        }

        .content-area {
            transition: all 0.3s ease;
            margin-left: 0.5rem;
        }
        .active-nav {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid white;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 72px !important;
            }
            .nav-text,
            .logo-text {
                display: none;
            }
            .nav-item {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-900 text-white w-56 flex flex-col">

            @include('Admin.partials.navigation')

        </div>
        <!-- Main Content -->
        <div class="content-area flex-1 overflow-y-auto">

            <!-- Enhanced Top Bar -->
            <div class="bg-white shadow-md p-4 flex justify-between items-center border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <button id="mobileMenuButton" class="text-gray-500 hover:text-gray-700 md:hidden transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                        Issued Certificates
                    </h2>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="border-l h-8 border-gray-200"></div>

                    <div class="relative group">
                        <button id="adminDropdown" class="flex items-center focus:outline-none space-x-2">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow">
                                @if(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Profile Photo" class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-white"></i>
                                @endif
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500 hidden md:block transition-transform group-hover:rotate-180"></i>
                        </button>
                        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="#" id="logoutButton" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 border-t border-gray-100">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Certificate Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Search and Filter -->
                    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Students</label>
                            <div class="relative">
                                <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Search by name or ID">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                            <select id="programFilter" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Programs</option>
                                @foreach(\App\Models\Program::all() as $program)
                                    <option value="{{ $program->name }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-48">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date Range</label>
                            <input type="date" id="issueDate" class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Select date">
                        </div>
                    </div>
                    <!-- Issued Certificates List -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="issuedTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($issuedCertificates ?? [] as $certificate)
                                <tr data-name="{{ $certificate->user->name }}" data-id="{{ $certificate->user->student_id }}" data-program="{{ $certificate->program->name ?? 'No Program' }}" data-issued-date="{{ $certificate->issued_date ?? now()->format('Y-m-d') }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $certificate->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $certificate->program->name ?? 'No Program' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $certificate->issued_date ? \Carbon\Carbon::parse($certificate->issued_date)->format('M d, Y') : now()->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3 preview-btn"
                                            data-name="{{ $certificate->user->name }}"
                                            data-program="{{ $certificate->program->name ?? 'No Program' }}"
                                            data-date="{{ $certificate->completion_date }}">
                                            Preview
                                        </button>
                                        <button class="text-green-600 hover:text-green-900 mr-3 download-btn"
                                            data-name="{{ $certificate->user->name }}"
                                            data-program="{{ $certificate->program->name ?? 'No Program' }}"
                                            data-date="{{ $certificate->completion_date }}">
                                            Download
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ ($issuedCertificates ?? collect())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Preview Modal (hidden by default) -->
    <div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative mx-auto shadow-lg rounded-md bg-white" style="width: 12in; height: auto; max-width: 95vw; max-height: none; margin: 5vh auto;">
            <div class="flex justify-between items-center p-4 bg-gray-100 border-b">
                <h3 class="text-lg font-medium">Certificate Preview</h3>
                <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="certificate-preview-container" style="width: 100%; height: 8.5in; overflow: hidden;">
                <div class="certificate-preview-bg" id="certificateToExport" style="width: 11in; height: 8.5in; margin: 0 0.5in;">
                    <div class="certificate-preview-content">
                        <h1 class="preview-student-name" id="previewStudentName">[Student Name]</h1>
                        <p class="preview-completion-text">Has successfully completed the <span id="previewProgramName"><strong>[Program Name]</strong></span></p>
                        <p class="preview-completion-text">modular training program at Bohol Northern Star College (BNSC), given this day on </p>
                        <p class="preview-completion-text" id="previewCompletionDate">[Completion Date]</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 p-4 bg-gray-100 border-t">
                <button id="downloadPdfBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center gap-2">
                    <i class="fas fa-download"></i> Download as PDF
                </button>
                <button id="printBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center gap-2">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Helper function to capitalize first letter of each word
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt){
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        // Admin dropdown toggle
        document.getElementById('adminDropdown').addEventListener('click', function(e) {
            e.stopPropagation();
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').classList.add('hidden');
        });

        // Sidebar toggle
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            sidebar.classList.toggle('sidebar-collapsed');
            contentArea.classList.toggle('ml-1');
            if (sidebar.classList.contains('sidebar-collapsed')) {
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                localStorage.setItem('sidebar-collapsed', 'false');
            }
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-chevron-left');
            icon.classList.toggle('fa-chevron-right');
        });

        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        });

        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navItems.forEach(nav => nav.classList.remove('active-nav'));
                this.classList.add('active-nav');
                const url = this.getAttribute('data-url');
                if (url) {
                    // Show loading animation before navigation
                    // Use the standard loading system
                    if (window.loadingManager) {
                        window.loadingManager.show();
                    }
                    
                    // Navigate after a short delay
                    setTimeout(() => {
                        window.location.href = url;
                    }, 300);
                }
            });
        });

        function handleResize() {
            if (window.innerWidth < 768) {
                document.querySelector('.sidebar').classList.add('sidebar-collapsed');
                document.querySelector('.content-area').classList.remove('ml-1');
                document.querySelector('.content-area').classList.add('ml-1');
            } else {
                document.querySelector('.sidebar').classList.remove('sidebar-collapsed');
                document.querySelector('.content-area').classList.remove('ml-1');
                document.querySelector('.content-area').classList.add('ml-1');
            }
        }
        window.addEventListener('resize', handleResize);
        handleResize();

        // Certificate Preview Button
        document.querySelectorAll('.preview-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('previewStudentName').textContent = toTitleCase(this.dataset.name);
                document.getElementById('previewProgramName').textContent = this.dataset.program;

                // Check if certificate date is filled, otherwise use original completion date
                const certificateDateInput = document.getElementById('issueDate').value;
                let rawDate = certificateDateInput || this.dataset.date;
                let formattedDate = rawDate;

                if (rawDate) {
                    // Handles both 'YYYY-MM-DD' and 'YYYY-MM-DD HH:MM:SS'
                    const datePart = rawDate.split(' ')[0];
                    const parts = datePart.split('-');
                    if (parts.length === 3) {
                        // Month is 0-based in JS Date
                        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
                        if (!isNaN(dateObj)) {
                            const options = { year: 'numeric', month: 'long', day: 'numeric' };
                            formattedDate = dateObj.toLocaleDateString('en-US', options);
                        }
                    }
                }
                document.getElementById('previewCompletionDate').textContent = formattedDate;
                document.getElementById('previewModal').classList.remove('hidden');
            });
        });

        // Download as PDF
        document.getElementById('downloadPdfBtn').addEventListener('click', function() {
            const element = document.getElementById('certificateToExport');
            const opt = {
                margin:       0,
                filename:     'certificate.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(element).save();
        });

        // Print
        document.getElementById('printBtn').addEventListener('click', function() {
            const printContents = document.getElementById('certificateToExport').outerHTML;
            const printWindow = window.open('', '', 'width=1100,height=850');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Print Certificate</title>
                    <link href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@400;700&family=Satisfy&display=swap" rel="stylesheet">
                    <style>
                        body { margin:0; padding:0; }
                        .certificate-preview-bg { width:11in; height:8.5in; display:flex; justify-content:center; align-items:center; background: url('{{ asset('pictures/certificate.png') }}') center/contain no-repeat; font-family: 'Inria Serif', serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        .certificate-preview-content { text-align:center; width:100%; max-width:9in; }
                        .preview-student-name { font-family:'Satisfy',cursive; font-size:38px; margin-top:50px; margin-bottom:10px; font-weight:bold; color:#b49958; line-height:1.2; }
                        .preview-completion-text { font-size:16px; margin-top:2px; color:#7a0000; }
                    </style>
                </head>
                <body>${printContents}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        });

        // Logout functionality
        document.getElementById('logoutButton').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        });

        // Search and Filter
        document.getElementById('searchInput').addEventListener('input', function() {
            let val = this.value.toLowerCase();
            document.querySelectorAll('#issuedTable tbody tr').forEach(row => {
                let name = row.getAttribute('data-name').toLowerCase();
                let id = row.getAttribute('data-id').toLowerCase();
                row.style.display = (name.includes(val) || id.includes(val)) ? '' : 'none';
            });
        });
        document.getElementById('programFilter').addEventListener('change', function() {
            let val = this.value;
            document.querySelectorAll('#issuedTable tbody tr').forEach(row => {
                row.style.display = (!val || row.getAttribute('data-program') === val) ? '' : 'none';
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.querySelector('.sidebar');
            const contentArea = document.querySelector('.content-area');
            const toggleIcon = document.querySelector('#toggleSidebar i');
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                contentArea.classList.remove('ml-1');
                contentArea.classList.add('ml-1');
                if (toggleIcon.classList.contains('fa-chevron-left')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                }
            }
        });
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    @stack('scripts')
    
    <!-- Loading System Integration -->
    @include('partials.loading-integration')
</body>
</html>
