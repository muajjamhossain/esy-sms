
  <header class="main-header">
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top pl-30">
      <!-- Sidebar toggle button-->
	  <div>
		  <ul class="nav">
			<li class="btn-group nav-item">
				<a href="#" class="waves-effect waves-light nav-link rounded svg-bt-icon" data-toggle="push-menu" role="button">
					<i class="fa fa-bars header-icon"></i>
			    </a>
			</li>
			<li class="btn-group nav-item">
				<a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link rounded svg-bt-icon" title="{{ __('messages.fullscreen') }}">
					<i class="fa fa-expand header-icon"></i>
			    </a>
			</li>			
			<li class="btn-group nav-item d-none d-xl-inline-block">
				<a href="{{ route('assignments.index') }}" class="waves-effect waves-light nav-link rounded svg-bt-icon" title="{{ __('messages.assignments') }}">
					<i class="fa fa-check-square-o header-icon"></i>
			    </a>
			</li>
			<li class="btn-group nav-item d-none d-xl-inline-block">
				<a href="{{ route('events.index') }}" class="waves-effect waves-light nav-link rounded svg-bt-icon" title="{{ __('messages.academic_calendar') }}">
					<i class="fa fa-calendar header-icon"></i>
			    </a>
			</li>
		  </ul>
	  </div>
		
      <div class="navbar-custom-menu r-side">
        <ul class="nav navbar-nav">
          <li class="dropdown language-switcher mr-10">
            <a href="#" class="waves-effect waves-light rounded dropdown-toggle language-switcher-toggle" data-toggle="dropdown" title="{{ __('messages.language') }}">
              <i class="fa fa-globe header-icon"></i>
              <span class="language-current">{{ app()->getLocale() === 'bn' ? __('messages.bangla') : (app()->getLocale() === 'ar' ? __('messages.arabic') : __('messages.english')) }}</span>
            </a>
            <ul class="dropdown-menu animated flipInX language-menu">
              <li><a href="{{ route('language.switch', 'en') }}"><i class="fa fa-check {{ app()->getLocale() === 'en' ? '' : 'invisible' }}"></i>{{ __('messages.english') }}</a></li>
              <li><a href="{{ route('language.switch', 'bn') }}"><i class="fa fa-check {{ app()->getLocale() === 'bn' ? '' : 'invisible' }}"></i>{{ __('messages.bangla') }}</a></li>
              <li><a href="{{ route('language.switch', 'ar') }}"><i class="fa fa-check {{ app()->getLocale() === 'ar' ? '' : 'invisible' }}"></i>{{ __('messages.arabic') }}</a></li>
            </ul>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('portal.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.portal') }}">
              <i class="fa fa-comments header-icon"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('assignments.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.assignments') }}">
              <i class="fa fa-book header-icon"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('notices.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.notices') }}">
              <i class="fa fa-bullhorn header-icon"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('events.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.events') }}">
              <i class="fa fa-calendar header-icon"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('library.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.library') }}">
              <i class="fa fa-book header-icon"></i>
            </a>
          </li>
		  <!-- Global search -->
	      <li class="search-bar global-search">
			  <form action="{{ route('global.search') }}" method="get" autocomplete="off">
			    <div class="lookup lookup-circle lookup-right">
			      <input type="search" name="q" id="global-search-input" placeholder="{{ __('messages.search') }}" aria-label="{{ __('messages.search') }}">
			    </div>
			  </form>
			  <div id="global-search-results" class="global-search-results"></div>
		  </li>
		  <!-- Notifications -->
		  <li class="dropdown notifications-menu">
			<a href="#" class="waves-effect waves-light rounded dropdown-toggle" data-toggle="dropdown" title="{{ __('messages.notifications') }}">
			  <i class="fa fa-bell header-icon"></i>
			</a>
			<ul class="dropdown-menu animated bounceIn">

			  <li class="header">
				<div class="p-20">
					<div class="flexbox">
						<div>
							<h4 class="mb-0 mt-0">{{ __('messages.notifications') }}</h4>
						</div>
						<div>
							<a href="{{ route('notices.index') }}" class="text-danger">{{ __('messages.view_all') }}</a>
						</div>
					</div>
				</div>
			  </li>

			  @php
				$headerNotices = \App\Models\Notice::visible()->latest('published_at')->limit(2)->get(['id', 'title']);
				$headerEvents = \App\Models\InstitutionEvent::upcoming()->orderBy('starts_at')->limit(2)->get(['id', 'title']);
				$headerUnreadMessages = \App\Models\PortalMessage::whereNull('read_at')
					->where('user_id', '!=', Auth::id())
					->whereHas('conversation', function ($query) {
						$query->where('created_by', Auth::id())->orWhere('participant_id', Auth::id());
					})
					->count();
			  @endphp
			  <li>
				<ul class="menu sm-scrol">
				  @foreach($headerNotices as $headerNotice)
					<li><a href="{{ route('notices.index') }}"><i class="fa fa-bullhorn text-info"></i>{{ $headerNotice->title }}</a></li>
				  @endforeach
				  @foreach($headerEvents as $headerEvent)
					<li><a href="{{ route('events.index') }}"><i class="fa fa-calendar text-warning"></i>{{ $headerEvent->title }}</a></li>
				  @endforeach
				  @if($headerUnreadMessages > 0)
					<li><a href="{{ route('portal.index') }}"><i class="fa fa-comments text-danger"></i>{{ $headerUnreadMessages }} {{ __('messages.unread_messages') }}</a></li>
				  @endif
				  @if($headerNotices->isEmpty() && $headerEvents->isEmpty() && $headerUnreadMessages === 0)
					<li><span class="text-muted px-20">{{ __('messages.no_notifications') }}</span></li>
				  @endif
				</ul>
			  </li>
			  <li class="footer">
				  <a href="{{ route('notices.index') }}">{{ __('messages.view_all') }}</a>
			  </li>
			</ul>
		  </li>	
		  
@php
 $user = DB::table('users')->where('id',Auth::user()->id)->first();
@endphp		  
	      <!-- User Account-->
          <li class="dropdown user user-menu">	
			<a href="#" class="waves-effect waves-light rounded dropdown-toggle p-0" data-toggle="dropdown" title="{{ __('messages.profile') }}">
				<img src="{{ (!empty($user->image))? url('upload/user_images/'.$user->image):url('upload/no_image.jpg') }}" alt="">
			</a>
			<ul class="dropdown-menu animated flipInX">
			  <li class="user-body">
	 <a class="dropdown-item" href="{{ route('profile.view') }}"><i class="fa fa-user text-muted mr-2"></i> {{ __('messages.profile') }}</a>
	  <a class="dropdown-item" href="{{ route('monthly.fee.view') }}"><i class="fa fa-credit-card text-muted mr-2"></i> {{ __('messages.my_wallet') }}</a>
	  <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa fa-cog text-muted mr-2"></i> {{ __('messages.settings') }}</a>
				 <div class="dropdown-divider"></div>
	  <a class="dropdown-item" href="{{ route('admin.logout') }}"><i class="fa fa-lock text-muted mr-2"></i> {{ __('messages.logout') }}</a>
			  </li>
			</ul>
          </li>	
		  <li>
              <a href="{{ route('profile.edit') }}" title="{{ __('messages.settings') }}" class="waves-effect waves-light">
				<i class="fa fa-cog header-icon"></i>
			  </a>
          </li>
			
        </ul>
      </div>
    </nav>
  </header>