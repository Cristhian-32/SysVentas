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
                        @foreach($data as $purchase)
                        <tr>
                            <td>
                                <h6>{{$purchase->id}}</h6>
                            </td>
                            <td>
                                <h6>{{$purchase->user}}</h6>
                            </td>
                            <td>
                                <h6>Por la Compra de: {{$purchase->product_name}} </h6>
                            </td>
                            <td>
                                <h6>{{$purchase->product_quantity}}</h6>
                            </td>
                            <td>
                                <h6>{{$purchase->date}}</h6>
                            </td>
                            <td>
                                <h6>{{$purchase->subtotal}}</h6>
                            </td>
                            <td>
                                <h6>{{$purchase->igv}}</h6>
                            </td>
                            <td>
                                <h6>{{$purchase->total}}</h6>
                            </td>
                            <td>
                                <h6>{{$purchase->status}}</h6>
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


