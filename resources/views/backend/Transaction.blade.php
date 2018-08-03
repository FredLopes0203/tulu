@extends('backend.layouts.app')

@section ('title', 'Charity Transactions')

@section('after-styles')
    {{ Html::style("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css") }}
    {{ Html::style('js/select2/select2.min.css') }}
@endsection

@section('page-header')
    <h1>
        Charity Transaction
        <small>Transaction history</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Transaction List</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <div style="width: 100px; margin-left: 15px; margin-top:3px; float: left">
                <label>Charity Group:</label>
            </div>
            <div class="col-lg-2 col-lg-2 col-lg-2">

                <select id="charitygroupsel" class="form-control select2 error" style="width: 100%">
                    <option value="0" selected="selected">All</option>
                    @foreach($charitygroups as $charitygroup)
                        <option value="{{$charitygroup->id}}"
                            @if(isset($selectedCharityGroup))
                                @if($selectedCharityGroup == $charitygroup->id)
                                    selected = "selected"
                                @endif
                            @endif
                        >{{$charitygroup->name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="width: 60px; margin-left: 15px; margin-top:3px; float: left">
                <label>Charity :</label>
            </div>
            <div class="col-lg-2 col-lg-2 col-lg-2">

                <select id="charitysel" class="form-control select2 error" style="width: 100%">
                    <option value="0" selected="selected">All</option>

                </select>
            </div>
        </div><!-- /.box-body -->

        <div class="box-body">
            <label id="lblTotal" style="color: #6A1B9A; font-size: 18px; margin-bottom: 20px">Total: </label>
            <div class="table-responsive">
                <table id="transaction-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th style="width: 25%">Charity Group</th>
                            <th style="width: 25%">Charity Name</th>
                            <th style="width: 25%">Amount($)</th>
                            <th style="width: 25%">Transaction Date</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@endsection

@section('after-scripts')
    {{ Html::script("https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables-extend.js") }}
    {{ Html::script("js/select2/select2.full.min.js") }}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var totalAmount = 0;
            $('#lblTotal').text("Total Amount: " + totalAmount + " $");

            $(".select2").select2();

            var TransactionTable = $('#transaction-table').DataTable({
                dom: 'lfrtip',
                processing: false,
                autoWidth: false,
                columns: [
                    {data: 'charitygroup', name: 'charitygroup'},
                    {data: 'charityname', name: 'charityname'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at', name: 'created_at'},
                ]
            });

            $("#charitygroupsel").change(function(){
                var selectedCharityGroup = $('#charitygroupsel').val();
                var postdata = {charitygroup: selectedCharityGroup};
                totalAmount = 0;
                $('#lblTotal').text("Total Amount: " + totalAmount + " $");

                $.ajax({
                    type: 'POST',
                    url: '{{route("admin.charity.charitybygroup.get")}}',
                    dataType: 'json',
                    data:postdata,
                    error: function (xhr, err) {
                        console.log(err);
                    },
                    success: function(data)
                    {
                        $('#charitysel')
                            .find('option')
                            .remove()
                            .end()
                            .append('<option value="0" selected="selected">All</option>');

                        $.each( data['charities'], function( key, value ) {
                            console.log(value['name']);
                            $("#charitysel").append('<option value="' + value['id'] + '">' + value['name'] + '</option>');
                        });

                        getTransactions();
                    }
                });
            }).change();

            $("#charitysel").change(function(){
                totalAmount = 0;
                $('#lblTotal').text("Total Amount: " + totalAmount + " $");
                getTransactions();
            });

            function getTransactions()
            {
                var selectedCharityGroup = $('#charitygroupsel').val();
                var selectedCharity = $('#charitysel').val();

                var postdata = {charitygroup: selectedCharityGroup, charity: selectedCharity};

                $.ajax({
                    type: 'POST',
                    url: '{{route("admin.transactions.get")}}',
                    dataType: 'json',
                    data:postdata,
                    error: function (xhr, err) {
                        console.log(err);
                    },
                    success: function(data)
                    {
                        var TransactionData = data['transactions'];
                        totalAmount = data['total'];
                        if(totalAmount == undefined)
                        {
                            totalAmount = 0;
                        }
                        $('#lblTotal').text("Total Amount: " + totalAmount + " $");
                        console.log(TransactionData);
                        TransactionTable.clear();

                        TransactionTable.rows.add(TransactionData);
                        TransactionTable.draw();


                    }
                });
            }
        });
    </script>
@endsection