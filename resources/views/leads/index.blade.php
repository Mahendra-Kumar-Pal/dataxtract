@extends('layouts.app')
@section('title', 'Excel DataTable')

@section('contents')
    <div class="container pt-5 my-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="float-left">
                                <!-- Leads Import Form -->
                                <form id="importForm" action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input class="border rounded-pill p-1" type="file" name="file" required>
                                    <button class="rounded-pill p-1" type="submit">Import</button>
                                </form>
                            </div>
                            <div class="float-right">
                                <!-- Leads Export Button -->
                                {{-- <a href="{{ route('leads.export') }}">
                                    <button id="exportLeadsBtn" class="rounded-pill p-1">Export Leads</button>
                                </a> --}}
                                <button id="exportLeadsBtn" class="rounded-pill p-1">Export Leads</button>
                            </div>
                        </div>
                        <div class="text-right mt-5">
                            <select id="cityFilter">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </select>
                            <select id="sourceFilter">
                                <option value="">Select Source</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source }}">{{ $source }}</option>
                                @endforeach
                            </select>
                            <select id="leadTypeFilter">
                                <option value="">Select Lead Type</option>
                                @foreach($leadTypes as $leadType)
                                    <option value="{{ $leadType }}">{{ $leadType }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- DataTable -->
                        <table id="leadTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>City</th>
                                    <th>Source</th>
                                    <th>Disposition</th>
                                    <th>Lead Type</th>
                                    <th>Attempted</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function () {
            // ------datatable------
            let table = $('#leadTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('leads.index') }}",
                    data: function (d) {
                        d.city = $('#cityFilter').val();
                        d.source = $('#sourceFilter').val();
                        d.lead_type = $('#leadTypeFilter').val();
                    }
                },
                language: {
                    searchPlaceholder: "type name or mobile"
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                    { data: 'date', name: 'date', searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'mobile_no', name: 'mobile_no' },
                    { data: 'city', name: 'city', searchable: false },
                    { data: 'source', name: 'source', searchable: false },
                    { data: 'disposition', name: 'disposition', searchable: false },
                    { data: 'lead_type', name: 'lead_type', searchable: false },
                    { data: 'attempted', name: 'attempted', searchable: false },
                    { data: 'remark', name: 'remark', searchable: false }
                ]
            });
            $('#cityFilter, #sourceFilter, #leadTypeFilter').on('keyup change', function () {
                table.draw();
            });
            // ------import excel sheet------
            $('#importForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Uploading...',
                            text: 'Please wait while the file is being imported.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.success
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = "Something went wrong!";
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).map(msg => msg.join('\n')).join('\n');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            text: errorMessage,
                        });
                    }
                });
            });
            // -------disable button (by chance double click on button within second)-------
            $('#exportLeadsBtn').click(function(e) {
                e.preventDefault();
                
                // Get filter values
                let city = $('#cityFilter').val();
                let source = $('#sourceFilter').val();
                let leadType = $('#leadTypeFilter').val();

                let button = $(this);
                button.prop('disabled', true).css('cursor', 'no-drop');

                Swal.fire({
                    title: 'Please Wait!',
                    text: 'Exporting leads...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let exportUrl = "{{ route('leads.export') }}?city=" + city + "&source=" + source + "&lead_type=" + leadType;
                window.location.href = exportUrl;

                setTimeout(() => {
                    button.prop('disabled', false).css('cursor', 'pointer');
                    Swal.close();
                }, 5000);
            });
        });
    </script>
@endpush
