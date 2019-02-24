<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{url('/')}}">{{config("app.name")}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav mr-auto">
                <li><a class="nav-item nav-link active" href="{{ url('/feed')}}">Feed</a></li>
                @auth
                <li><a class="nav-item nav-link" href="{{ url('/dashboard')}}">Dashboard</a></li>
                @endauth
            </ul>
        
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login')}}"> Log ind </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/register')}}">Registrér</a>
                    </li>
                @else
                    <li class="nav-item">
                            <a class="nav-link" href="{{ url('/logout')}}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Log ud
                            </a>
                            <form id="logout-form" action="{{ url('/logout')}}" method="POST" style="display: none;">
                                @csrf
                            </form>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link disabled">{{ Auth::user()->name }} </a>
                    </li>
                @endguest
            </ul>
      </div>
</nav>
<br>