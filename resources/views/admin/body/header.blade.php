
  <header class="main-header">
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top pl-30">
      <!-- Sidebar toggle button-->
	  <div>
		  <ul class="nav">
			<li class="btn-group nav-item">
				<a href="#" class="waves-effect waves-light nav-link rounded svg-bt-icon" data-toggle="push-menu" role="button">
					<i data-feather="menu"></i>
			    </a>
			</li>
			<li class="btn-group nav-item">
				<a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link rounded svg-bt-icon" title="{{ __('messages.fullscreen') }}">
					<i data-feather="maximize"></i>
			    </a>
			</li>			
			<li class="btn-group nav-item d-none d-xl-inline-block">
				<a href="{{ route('assignments.index') }}" class="waves-effect waves-light nav-link rounded svg-bt-icon" title="{{ __('messages.assignments') }}">
					<i data-feather="check-square"></i>
			    </a>
			</li>
			<li class="btn-group nav-item d-none d-xl-inline-block">
				<a href="{{ route('events.index') }}" class="waves-effect waves-light nav-link rounded svg-bt-icon" title="{{ __('messages.academic_calendar') }}">
					<i data-feather="calendar"></i>
			    </a>
			</li>
		  </ul>
	  </div>
		
      <div class="navbar-custom-menu r-side">
        <ul class="nav navbar-nav">
          <li class="dropdown language-switcher mr-10">
            <a href="#" class="waves-effect waves-light rounded dropdown-toggle language-switcher-toggle" data-toggle="dropdown" title="{{ __('messages.language') }}">
              <i data-feather="globe"></i>
              <span class="language-current">{{ app()->getLocale() === 'bn' ? __('messages.bangla') : (app()->getLocale() === 'ar' ? __('messages.arabic') : __('messages.english')) }}</span>
            </a>
            <ul class="dropdown-menu animated flipInX language-menu">
              <li><a href="{{ route('language.switch', 'en') }}"><i data-feather="check" class="{{ app()->getLocale() === 'en' ? '' : 'invisible' }}"></i>{{ __('messages.english') }}</a></li>
              <li><a href="{{ route('language.switch', 'bn') }}"><i data-feather="check" class="{{ app()->getLocale() === 'bn' ? '' : 'invisible' }}"></i>{{ __('messages.bangla') }}</a></li>
              <li><a href="{{ route('language.switch', 'ar') }}"><i data-feather="check" class="{{ app()->getLocale() === 'ar' ? '' : 'invisible' }}"></i>{{ __('messages.arabic') }}</a></li>
            </ul>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('portal.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.portal') }}">
              <i data-feather="message-circle"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('assignments.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.assignments') }}">
              <i data-feather="book-open"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('notices.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.notices') }}">
              <i data-feather="volume-2"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('events.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.events') }}">
              <i data-feather="calendar"></i>
            </a>
          </li>
          <li class="btn-group nav-item">
            <a href="{{ route('library.index') }}" class="waves-effect waves-light nav-link rounded" title="{{ __('messages.library') }}">
              <i data-feather="book"></i>
            </a>
          </li>
		  <!-- full Screen -->
	      <li class="search-bar">		  
			  <div class="lookup lookup-circle lookup-right">
			     <input type="text" name="s">
			  </div>
		  </li>			
		  <!-- Notifications -->
		  <li class="dropdown notifications-menu">
			<a href="#" class="waves-effect waves-light rounded dropdown-toggle" data-toggle="dropdown" title="{{ __('messages.notifications') }}">
			  <i data-feather="bell"></i>
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
					<li><a href="{{ route('notices.index') }}"><i data-feather="volume-2" class="text-info"></i>{{ $headerNotice->title }}</a></li>
				  @endforeach
				  @foreach($headerEvents as $headerEvent)
					<li><a href="{{ route('events.index') }}"><i data-feather="calendar" class="text-warning"></i>{{ $headerEvent->title }}</a></li>
				  @endforeach
				  @if($headerUnreadMessages > 0)
					<li><a href="{{ route('portal.index') }}"><i data-feather="message-circle" class="text-danger"></i>{{ $headerUnreadMessages }} {{ __('messages.unread_messages') }}</a></li>
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
	 <a class="dropdown-item" href="{{ route('profile.view') }}"><i data-feather="user" class="text-muted mr-2"></i> {{ __('messages.profile') }}</a>
	  <a class="dropdown-item" href="{{ route('monthly.fee.view') }}"><i data-feather="credit-card" class="text-muted mr-2"></i> {{ __('messages.my_wallet') }}</a>
	  <a class="dropdown-item" href="{{ route('profile.edit') }}"><i data-feather="settings" class="text-muted mr-2"></i> {{ __('messages.settings') }}</a>
				 <div class="dropdown-divider"></div>
	  <a class="dropdown-item" href="{{ route('admin.logout') }}"><i data-feather="lock" class="text-muted mr-2"></i> {{ __('messages.logout') }}</a>
			  </li>
			</ul>
          </li>	
		  <li>
              <a href="{{ route('profile.edit') }}" title="{{ __('messages.settings') }}" class="waves-effect waves-light">
				<i data-feather="settings"></i>
			  </a>
          </li>
			
        </ul>
      </div>
    </nav>
  </header>