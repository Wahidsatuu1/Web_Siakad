<div class="user-info position-fixed">
  <span>Halo, **{{ Auth::user()->name }}** ({{ ucfirst(Auth::user()->role) }})</span>
  <form class="logout-form" action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="logout-button">Logout</button>
  </form>
</div>