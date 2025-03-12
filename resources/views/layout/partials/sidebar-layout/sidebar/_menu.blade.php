<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
	<!--begin::Menu wrapper-->
	<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
		<!--begin::Menu-->
		<div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
			<div class="menu-item">
				<!--begin:Menu link-->
				<a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }} || {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}" @if(Auth::user()->type == 'Karyawan' ) href="{{ route('dashboard') }}" @else href="{{ route('dashboard.admin') }}" @endif>
					<span class="menu-icon"> 
						<i class="ki-duotone ki-element-11 fs-2">
						 <span class="path1"></span>
						 <span class="path2"></span>
						 <span class="path3"></span>
						 <span class="path4"></span>
						</i>
					</span>
					<span class="menu-title">Dashboard</span>
				</a>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div class="menu-item pt-5">
				<!--begin:Menu content-->
				<div class="menu-content">
					<span class="menu-heading fw-bold text-uppercase fs-7">Home</span>
				</div>
				<!--end:Menu content-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			@if( Auth::user()->type == 'Admin' )
			<div data-kt-menu-trigger="click" class="menu-item menu-accordion 
			{{ request()->routeIs('user-management.*') ? 'here show' : '' }} ||
			{{ request()->routeIs('departemen.*') ? 'here show' : '' }} ||
			{{ request()->routeIs('jabatan.*') ? 'here show' : '' }}
			">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">
						<i class="ki-duotone ki-abstract-28 fs-2">
						 <span class="path1"></span>
						 <span class="path2"></span>
						</i>
					</span>
					<span class="menu-title">Data Karyawan</span>
					<span class="menu-arrow"></span>
				</span>
				<!--end:Menu link-->
				<!--begin:Menu sub-->
				<div class="menu-sub menu-sub-accordion">
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('user-management.*') ? 'active' : '' }}" href="{{ route('user-management.index') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Karyawan</span>
						</a>
					</div>
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('departemen.*') ? 'active' : '' }}" href="{{ route('departemen.index') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Departemen</span>
						</a>
					</div>
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('jabatan.*') ? 'active' : '' }}" href="{{ route('jabatan.index') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Jabatan</span>
						</a>
					</div>
				</div>
				<!--end:Menu sub-->
			</div>
			<div class="menu-item">
				<a class="menu-link {{ request()->routeIs('pengajuan-cuti.*') ? 'active' : '' }}" href="{{ route('pengajuan-cuti.index-admin') }}">
					<span class="menu-icon"> 
						{!! getIcon('user-tick', 'fs-2') !!}
					</span>
					<span class="menu-title">Data Cuti</span>
				</a>
				<a class="menu-link {{ request()->routeIs('log-absen.*') ? 'active' : '' }}" href="{{ route('log-absen.index') }}">
					<span class="menu-icon">
						{!! getIcon('profile-user', 'fs-2') !!}
					</span>
					<span class="menu-title">Log Absen Karyawan</span>
				</a>
			</div>
			@endif
			@if( Auth::user()->type == 'Karyawan' )
			<div class="menu-item">
				<a class="menu-link {{ request()->routeIs('pengajuan-cuti.*') ? 'active' : '' }}" href="{{ route('pengajuan-cuti.index', Auth::user()->id) }}">
					<span class="menu-icon"> 
						{!! getIcon('user-tick', 'fs-2') !!}
					</span>
					<span class="menu-title">Pengajuan Cuti</span>
				</a>
			</div>
			<div class="menu-item">
				<a class="menu-link {{ request()->routeIs('log-absen.*') ? 'active' : '' }}" href="{{ route('log-absen.izinKaryawan', Auth::user()->id) }}">
					<span class="menu-icon"> 
						{!! getIcon('user-tick', 'fs-2') !!}
					</span>
					<span class="menu-title">Izin</span>
				</a>
			</div>
			@endif
			<!--end:Menu item-->
		</div>
		<!--end::Menu-->
	</div>
	<!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
