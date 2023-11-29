<div class="row">
    <div class="col-sm-12">
        <div>
            <div class="connect-sorting">
                <h5 class="text-center mb-3">RESUMEN DE COMPRA</h5>
                <div>
                    <div class="connect-sorting-content">
                        <div class="card simple-title-task ui-sortable-handle">
                            <div class="card-body">
                                <div class="task-header">
                                    <div>
                                        <h4 class="mt-3">Articulos: {{$itemsQuantity}}</h4>
                                    </div>
                                    <div>
                                        <h3>Sub Total: ${{number_format($subtotal,2)}}</h3>
                                        <input type="hidden" id="hiddenTotal" value="{{$total}}">
                                    </div>
                                    <div>
                                        <h3>IGV(18%): ${{number_format($igv,2)}}</h3>
                                        <input type="hidden" id="hiddenTotal" value="{{$total}}">
                                    </div>
                                    <div>
                                        <h2>TOTAL: ${{number_format($total,2)}}</h2>
                                        <input type="hidden" id="hiddenTotal" value="{{$total}}">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
