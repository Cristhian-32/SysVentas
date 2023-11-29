<div>
    <style></style>
    <div class="row layout-top-spacing">
        <div class="col-sm-12 col-md-8">
            <!--DETALLES-->
            @include('livewire.purchase.partials.detail')


        </div>

        <div class="col-sm-12 col-md-4">
            <!--TOTAL-->
        @include('livewire.purchase.partials.total')

        <!--DENOMIACIONES-->
            @include('livewire.purchase.partials.coins')
        </div>
    </div>
</div>

<script src="{{ asset('js/keypress.js')}}"></script>
<script src="{{ asset('js/onscan.js')}}"></script>

@include('livewire.purchase.scripts.shortcuts')
@include('livewire.purchase.scripts.events')
@include('livewire.purchase.scripts.general')
@include('livewire.purchase.scripts.scan')
