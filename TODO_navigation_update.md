# TODO: Update Instructor Navigation Profile

- [x] Replace static "Instructor" with dynamic {{ ucfirst(auth()->user()->name) }}
- [x] Replace static "Teacher" with dynamic {{ ucfirst(auth()->user()->role) }}
