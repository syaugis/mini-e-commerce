<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
    <div class="container-fluid navbar-inner">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <svg width="30" class="text-primary" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                    transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                    transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                    transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                    transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
            </svg>
            <h4 class="logo-title">{{ env('APP_NAME') }}</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" height="20px" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </i>
        </div>
        <div class="input-group search-input">
            <span class="input-group-text" id="search-input">
                <svg width="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></circle>
                    <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
            <input type="search" class="form-control" placeholder="Search...">
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <span class="navbar-toggler-icon">
                <span class="navbar-toggler-bar bar1 mt-2"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto navbar-list mb-2 mb-lg-0">
                {{-- Notifications --}}
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link" id="notification-drop" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <svg width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z"
                                fill="currentColor"></path>
                            <path opacity="0.4"
                                d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z"
                                fill="currentColor"></path>
                        </svg>
                        <span class="notification-count bg-danger">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    </a>

                    <div class="sub-drop dropdown-menu dropdown-menu-end p-0" aria-labelledby="notification-drop">
                        <div class="card shadow-none m-0">
                            <div class="card-header d-flex justify-content-between bg-primary py-3 align-items-center">
                                <div class="row g-0">
                                    <div class="col-md-12">
                                        <h5 class="text-white">All Notifications</h5>
                                    </div>
                                    <div class="d-flex flex-row align-items-center">
                                        <div class="col-md-5 me-2">
                                            <a href="{{ route('admin.notification.marks-all-read') }}">
                                                <h6 class="text-white">
                                                    Mark all as read
                                                </h6>
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <form action="{{ route('admin.notification.clear-all') }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to clear all notifications?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    style="background: none; border: none; padding: 0;">
                                                    <h6 class="text-white">Clear all</h6>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0" id="notifications-container">
                                @forelse (Auth::user()->notifications()->paginate(2) as $notification)
                                    <a href="{{ route('admin.notification.show', $notification->id) }}"
                                        class="iq-sub-card {{ is_null($notification->read_at) ? 'bg-notification-light' : '' }} position-relative">
                                        <div class="d-flex align-items-center">
                                            <div class="ms-3 w-100">
                                                <h6 class="mb-0 d-flex justify-content-between">
                                                    {{ $notification->data['title'] }}
                                                </h6>
                                                <p class="mb-0">{{ $notification->data['message'] }}</p>
                                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.notification.destroy', $notification->id) }}"
                                            method="POST" class="position-absolute top-0 end-0 m-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm p-0"
                                                style="background: none; border: none; padding: 0;"
                                                onclick="return confirm('Are you sure you want to delete this notification?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    viewBox="0 0 24 24" fill="currentColor" class="text-danger">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.29289 5.29289C5.68342 4.90237 6.31658 4.90237 6.70711 5.29289L12 10.5858L17.2929 5.29289C17.6834 4.90237 18.3166 4.90237 18.7071
                                                                    5.29289C19.0976 5.68342 19.0976 6.31658 18.7071 6.70711L13.4142 12L18.7071 17.2929C19.0976 17.6834 19.0976 18.3166 18.7071 18.7071C18.3166 19.0976 17.6834 19.0976 17.2929 18.7071L12 13.4142L6.70711 18.7071C6.31658 19.0976 5.68342 19.0976 5.29289 18.7071C4.90237 18.3166 4.90237 17.6834 5.29289 17.2929L10.5858 12L5.29289 6.70711C4.90237 6.31658 4.90237 5.68342 5.29289 5.29289Z" />
                                                </svg>
                                            </button>
                                        </form>
                                    </a>
                                @empty
                                    <h6 class="text-center p-3 mb-0">No notifications</h6>
                                @endforelse
                                <div id="pagination-links">
                                    {{ Auth::user()->notifications()->paginate(2)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                {{-- Profile --}}
                <li class="nav-item dropdown">
                    <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if (Auth::user()->profile_image != null)
                            <img src="{{ asset('images/avatars/avtar_1.png') }}" alt="User-Profile"
                                class="img-fluid avatar avatar-50 avatar-rounded">
                        @else
                            <img src="{{ asset('images/avatars/01.png') }}" alt="User-Profile"
                                class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                            <img src="{{ asset('images/avatars/avtar_1.png') }}" alt="User-Profile"
                                class="theme-color-purple-img img-fluid avatar avatar-50 avatar-rounded">
                            <img src="{{ asset('images/avatars/avtar_2.png') }}" alt="User-Profile"
                                class="theme-color-blue-img img-fluid avatar avatar-50 avatar-rounded">
                            <img src="{{ asset('images/avatars/avtar_4.png') }}" alt="User-Profile"
                                class="theme-color-green-img img-fluid avatar avatar-50 avatar-rounded">
                            <img src="{{ asset('images/avatars/avtar_5.png') }}" alt="User-Profile"
                                class="theme-color-yellow-img img-fluid avatar avatar-50 avatar-rounded">
                            <img src="{{ asset('images/avatars/avtar_3.png') }}" alt="User-Profile"
                                class="theme-color-pink-img img-fluid avatar avatar-50 avatar-rounded">
                        @endif
                        <div class="caption ms-3 mb-3 d-none d-md-block ">
                            <p class="mb-0 caption-title text-capitalize">{{ Auth::user()->name }}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Profile</a></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="javascript:void(0)" class="dropdown-item"
                                    onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
{{-- @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).on('click', '#pagination-links a', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');
            $.get(url, function(data) {
                $('#notifications-container').html($(data).find('#notifications-container').html());
            });
        });
    </script>
@endpush --}}
