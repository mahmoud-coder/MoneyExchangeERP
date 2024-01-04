<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="/assets/" data-template="vertical-menu-template-starter">
@include('admin.layouts.sections.header.header')
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        @include('admin.layouts.sections.menu.menu')
        <!-- Layout container -->
        <div class="layout-page">
            @include('admin.layouts.sections.nav.nav')
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">@yield('content-title')</h4>
                        @yield('content')
                    </div>
                </div>
                <!-- / Content -->
            <!-- / Content wrapper -->
            @include('admin.layouts.sections.footer.content-bottom')
        </div>  <!-- # 2 -->
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->
@include('admin.layouts.sections.footer.footer')
</html>