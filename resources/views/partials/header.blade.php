<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>

    <div class="d-flex justify-content-end align-items-center">
        <div class="dropdown">
            <a href="#" class="position-relative" id="dropdownNotification" data-bs-toggle="dropdown"
               aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                    1
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow notification-dropdown"
                aria-labelledby="dropdownNotification">
                <li class="dropdown-header bg-white py-3">
                    <span class="fw-bold text-black">Notifications</span>
                    <span class="badge bg-danger rounded-pill">1</span>
                </li>
                <li>
                    <a href="#" class="dropdown-item d-flex align-items-center py-3">
                        <div class="flex-shrink-0 me-3">
                            <i class="bi bi-bell-fill text-primary fs-5"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-bold">Welcome!</p>
                            <span class="small text-muted">Welcome to Sentosa Dashboard</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>

        <div class="dropdown ms-3">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"
               id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('dist/assets/static/images/profile.jpg') }}" alt="" width="32" height="32"
                     class="rounded-circle me-2">
                <strong>{{ Auth::user()->name ?? 'User' }}</strong>
            </a>
            <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                <li><a class="dropdown-item" href="{{ route('profile') }}">My Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
