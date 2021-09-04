<header>
  <div class="top-nav container">
    <div class="logo"><a href="/">Ecommerce</a></div>
    @if (! (request()->is("checkout") || request()->is('guestCheckout')))
        <ul>
          <li><a href="{{ route("shop.index") }}">Shop</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Blog</a></li>
          
        </ul>
    @endif

    <div class="top-nav-right">
      @if (! (request()->is('checkout') || request()->is('guestCheckout')))
        @include('partials.menus.main-right')
      @endif
  </div>

  </div>
</header>