<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.home') }}" class="sidebar-brand" target="_blank">
            SP. <span>Creation</span>
        </a>
    </div>

    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item">
                <a href="{{ route('admin.home') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item nav-category"> SP. Creation</li>

            {{-- Home --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home.index') }}">
                    <i class="link-icon" data-feather="home"></i>
                    <span class="link-title">Home</span>
                </a>
            </li>

            {{-- About --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('about.index') }}">
                    <i class="link-icon" data-feather="loader"></i>
                    <span class="link-title">About</span>
                </a>
            </li>

            {{-- Media --}}
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#attributeset" role="button" aria-expanded="false"
                    aria-controls="attributeset">
                    <i class="link-icon" data-feather="video"></i>
                    <span class="link-title">Media</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="attributeset">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('media.index') }}" class="nav-link">All Media</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('media.create') }}" class="nav-link">Inactive Media</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Brand --}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('brand.index') }}">
                    <i class="link-icon" data-feather="shopping-bag"></i>
                    <span class="link-title">Brand</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
