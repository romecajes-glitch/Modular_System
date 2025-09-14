<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Bohol Northern Star College - Modular Class Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles */
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .logo-container {
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #3b82f6;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .login-btn {
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-8px);
            }

            40% {
                transform: translateX(8px);
            }

            60% {
                transform: translateX(-8px);
            }

            80% {
                transform: translateX(8px);
            }

            100% {
                transform: translateX(0);
            }
        }

        .shake {
            animation: shake 0.5s ease;
        }

        /* Responsive improvements */
        @media (max-width: 640px) {
            .nav-link::after {
                display: none;
            }
            
            .logo-container:hover {
                transform: none;
            }
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Better touch targets for mobile */
        @media (max-width: 768px) {
            .login-btn {
                min-height: 44px;
                min-width: 44px;
            }
        }

    </style>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation Bar -->
    <nav class="bg-gradient-to-b from-blue-900 to-blue-600 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo and School Name -->
                <div class="flex-shrink-0 flex items-center logo-container">
                    <div class="flex items-center">
                        <img src="<?php echo e(asset('pictures/logo.png')); ?>" alt="BNSC Logo" class="h-10 w-10 mr-3">
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-white leading-tight">
                                Bohol Northern Star College
                            </span>
                            <span class="text-xs text-blue-200 hidden sm:block">
                                - Modular Class Portal
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Center Navigation Links -->
                <div class="hidden lg:flex lg:items-center lg:space-x-6 xl:space-x-8">
                    <a href="<?php echo e(route('welcome')); ?>" class="nav-link text-white hover:text-blue-300 px-2 xl:px-3 py-2 text-sm font-medium">Home</a>
                    <a href="<?php echo e(route('about')); ?>" class="nav-link text-white hover:text-blue-300 px-2 xl:px-3 py-2 text-sm font-medium">About</a>
                    <a href="<?php echo e(route('programs')); ?>" class="nav-link text-white hover:text-blue-300 px-2 xl:px-3 py-2 text-sm font-medium">Programs</a>
                </div>

                <!-- Login Button -->
                <div class="relative">
                    <button class="login-btn flex items-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden flex items-center ml-4">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-300 hover:bg-blue-800 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="lg:hidden hidden" id="mobile-menu">
            <div class="px-4 pt-2 pb-3 space-y-1 bg-gradient-to-b from-blue-900 to-blue-600 border-t border-blue-700">
                <a href="<?php echo e(route('welcome')); ?>" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800 transition-colors duration-200">Home</a>
                <a href="<?php echo e(route('about')); ?>" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800 transition-colors duration-200">About</a>
                <a href="<?php echo e(route('programs')); ?>" class="block px-3 py-3 rounded-md text-base font-medium text-white hover:text-blue-300 hover:bg-blue-800 transition-colors duration-200">Programs</a>
                <div class="pt-3 border-t border-blue-700">
                    <button class="w-full flex justify-center items-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-3 rounded-md text-base font-medium transition-colors duration-200" onclick="document.getElementById('login-modal').classList.remove('hidden')">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-b from-blue-900 to-blue-600 text-white py-16 sm:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-6 sm:mb-8 leading-tight">Welcome to BNSC Modular Class Portal</h1>
                <p class="text-lg sm:text-xl md:text-2xl mb-8 sm:mb-10 max-w-4xl mx-auto leading-relaxed">Empowering Skills, One Session at a Time — Your Gateway to Flexible, Hands-On Learning.</p>
                <div class="flex justify-center">
                    <a href="<?php echo e(route('enrollment')); ?>" class="bg-white text-blue-700 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold text-lg shadow-lg transition duration-300 transform hover:scale-105 flex items-center">
                        Enroll Now <i class="fas fa-user-plus ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- School Pictures Section -->
    <div class="bg-white py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-gray-800 mb-8 sm:mb-12">Our Campus</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <div class="rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <img src="<?php echo e(asset('pictures/bnc_welc.jpg')); ?>" alt="School Building" class="w-full h-48 sm:h-56 lg:h-64 object-cover">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <img src="<?php echo e(asset('pictures/comlab.jpg')); ?>" alt="Classroom" class="w-full h-48 sm:h-56 lg:h-64 object-cover">
                </div>
                <div class="rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 sm:col-span-2 lg:col-span-1">
                    <img src="<?php echo e(asset('pictures/hallway.jpg')); ?>" alt="Library" class="w-full h-48 sm:h-56 lg:h-64 object-cover">
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Our School Section -->
    <div class="bg-gray-50 py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-gray-800 mb-8 sm:mb-12">Why Choose Our School</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-blue-600 mb-3 sm:mb-4">
                        <i class="fas fa-award text-3xl sm:text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Quality Education</h3>
                    <p class="text-sm sm:text-base text-gray-600">Our institution is recognized for academic excellence and producing competent graduates.</p>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-blue-600 mb-3 sm:mb-4">
                        <i class="fas fa-chalkboard-teacher text-3xl sm:text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Expert Faculty</h3>
                    <p class="text-sm sm:text-base text-gray-600">Learn from experienced professors and industry professionals dedicated to student success.</p>
                </div>
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 sm:col-span-2 lg:col-span-1">
                    <div class="text-blue-600 mb-3 sm:mb-4">
                        <i class="fas fa-network-wired text-3xl sm:text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Industry Connections</h3>
                    <p class="text-sm sm:text-base text-gray-600">Strong partnerships with leading companies for internships and job placements.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16" id="main-content">
        <!-- Features Section -->
        <div class="mb-12 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-gray-800 mb-8 sm:mb-12">Key Features of Our Portal</h2>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <div class="text-blue-600 mb-3 sm:mb-4">
                        <i class="fas fa-user-graduate text-3xl sm:text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Modular Enrollment & Class Scheduling</h3>
                    <p class="text-sm sm:text-base text-gray-600">Easily enroll students in modular programs with automatic tracking of their class schedules, assignments, and progress. Our system supports both manual and online enrollment with admin oversight.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <div class="text-blue-600 mb-3 sm:mb-4">
                        <i class="fas fa-file-upload text-3xl sm:text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Module Upload & Assignment Submission</h3>
                    <p class="text-sm sm:text-base text-gray-600">Instructors can upload digital learning modules, and students can submit their completed modules directly through the portal. Submissions are tracked by deadlines and monitored for completion.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300">
                    <div class="text-blue-600 mb-3 sm:mb-4">
                        <i class="fas fa-chart-pie text-3xl sm:text-4xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold mb-2 text-gray-800">Real-time Progress & Payment Monitoring</h3>
                    <p class="text-sm sm:text-base text-gray-600">Admins and cashiers can monitor student payment status, while instructors and students can view academic progress in real-time. Role-based dashboards provide personalized views for effective management.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- About -->
                <div class="sm:col-span-2 lg:col-span-1">
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">About BNSC</h3>
                    <p class="text-sm sm:text-base text-gray-300 leading-relaxed">The Bohol Northern Star College, Inc., is guided by a Triad of Virtues: Educated Minds, Noble Hearts and Helpful Hands, and aims to internalize these virtues in the lives of its Faculty, Staff, and Students.</p>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Contact Us</h3>
                    <address class="text-sm sm:text-base text-gray-300 not-italic space-y-2">
                        <p class="flex items-start"><i class="fas fa-map-marker-alt mr-2 mt-1 flex-shrink-0"></i> <span>Isaac Garces St. 6315 Ubay, Bohol, Philippines</span></p>
                        <p class="flex items-center"><i class="fas fa-phone mr-2 flex-shrink-0"></i> <span>(038) - 500 - 0409</span></p>
                        <p class="flex items-center"><i class="fas fa-envelope mr-2 flex-shrink-0"></i> <span>info@bnsc.edu.ph</span></p>
                    </address>
                </div>

                <!-- Social Media -->
                <div>
                    <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/BNSCPAGE" class="text-gray-300 hover:text-white text-lg sm:text-xl transition-colors duration-300"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.youtube.com/@boholnorthernstarcollegein4253" class="text-gray-300 hover:text-white text-lg sm:text-xl transition-colors duration-300"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-gray-400">
                <p class="text-xs sm:text-sm">&copy; 2025 Bohol Northern Star College. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php echo $__env->make('partials.login_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
        // Add loading spinner to login form
        document.querySelector('form[action="<?php echo e(route('custom.login')); ?>"]').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('login-submit-btn');
            const spinner = document.getElementById('login-spinner');
            const btnText = document.getElementById('login-button-text');
            btn.disabled = true;
            btnText.textContent = 'Logging in...';
            btnText.classList.remove('opacity-0');
            btnText.classList.add('text-white');
            spinner.classList.remove('hidden');

            setTimeout(() => {
                e.target.submit();
            }, 1000);
        });
    </script>
    <script>
        // Login modal functionality
        const loginBtn = document.querySelector('.login-btn');
        const loginModal = document.getElementById('login-modal');
        const closeLoginModal = document.getElementById('close-login-modal');

        loginBtn.addEventListener('click', () => {
            loginModal.classList.remove('hidden');
        });

        closeLoginModal.addEventListener('click', () => {
            loginModal.classList.add('hidden');
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.setAttribute('aria-expanded', !isHidden);
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            });

            // Close mobile menu when window is resized to desktop size
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

    </script>

    <?php if($errors->has('username') || $errors->has('password')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('login-modal').classList.remove('hidden');
        });

    </script>
    <?php endif; ?>

    <!-- Loading System Integration -->
    <?php echo $__env->make('partials.loading-integration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\Users\gucor\OneDrive\Documents\Herd\Modular_System\resources\views/welcome.blade.php ENDPATH**/ ?>