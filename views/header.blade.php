<nav id="arch-navbar" aria-label="Seitennavigation">
    <div class="arch-navbar__inner">
        <a class="arch-navbar__brand" href="@isset($home){{ $home }}@else/@endif">
            <img alt="Arch Linux" height="40" width="190" src="/assets/extensions/archlinux-de-theme-archlinux/archlogo.svg">
        </a>
        <button id="arch-navbar-toggle" class="arch-navbar__toggle" type="button" aria-controls="arch-navbar-menu" aria-expanded="false" aria-label="Navigation umschalten">
            <span class="arch-navbar__toggle-icon" aria-hidden="true"></span>
        </button>
        <div id="arch-navbar-menu" class="arch-navbar__menu">
            <ul class="arch-navbar__list">
                @isset ($navbar)
                    @foreach ($navbar as $name => $url)
                        <li>
                            <a class="arch-navbar__link{{ isset($navbar_selected) && $navbar_selected == $name ? ' is-active' : '' }}" href="{{ $url }}"@if (isset($navbar_selected) && $navbar_selected == $name) aria-current="page"@endif>{{ $name }}</a>
                        </li>
                    @endforeach
                @endisset
            </ul>
        </div>
    </div>
</nav>
