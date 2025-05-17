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
                    <i class="link-icon" data-feather="tag"></i>
                    <span class="link-title">Home</span>
                </a>
            </li>

        </ul>
    </div>
</nav>
