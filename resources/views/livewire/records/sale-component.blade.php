<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}} | {{$pageTitle}}</b>
                </h4>
            </div>
            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background: #3b3f5c">
                        <tr>
                            <th class="table-th text-white">ID</th>
                            <th class="table-th text-white">REGISTRADO POR: </th>
                            <th class="table-th text-white">DESCRIPCIÓN</th>
                            <th class="table-th text-white">CANTIDAD</th>
                            <th class="table-th text-white">FECHA</th>
                            <th class="table-th text-white">SUBTOTAL</th>
                            <th class="table-th text-white">IGV</th>
                            <th class="table-th text-white">TOTAL</th>
                            <th class="table-th text-white">ESTADO</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $sale)
                        <tr>
                            <td>
                                <h6>{{$sale->id}}</h6>
                            </td>
                            <td>
                                <h6>{{$sale->user}}</h6>
                            </td>
                            <td>
                                <h6>Por la venta de: {{$sale->product_name}} </h6>
                            </td>
                            <td>
                                <h6>{{$sale->product_quantity}}</h6>
                            </td>
                            <td>
                                <h6>{{$sale->date}}</h6>
                            </td>
                            <td>
                                <h6>{{$sale->subtotal}}</h6>
                            </td>
                            <td>
                                <h6>{{$sale->igv}}</h6>
                            </td>
                            <td>
                                <h6>{{$sale->total}}</h6>
                            </td>
                            <td>
                                <h6>{{$sale->status}}</h6>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>


