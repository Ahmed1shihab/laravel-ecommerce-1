<div class="breadcrumbs">
    <div class="breadcrumbs-container container">
        <div>
            {{ $slot }}
        </div>
        <div style="display: flex">
            @include('partials.search')
        </div>
    </div>
</div> <!-- end breadcrumbs -->