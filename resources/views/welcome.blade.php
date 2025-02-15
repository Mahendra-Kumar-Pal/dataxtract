@extends('layouts.normal')
@section('title', 'Landing Page')

@push('style')
    <style>
        body{font-family:Arial,sans-serif;text-align:center;margin:100px;}.box{max-width:800px;margin:auto;padding:20px;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,.1);}h1{color:#333;}a{text-decoration:none;background:#007bff;color:#fff;padding:10px 20px;border-radius:5px;display:inline-block;margin-top:20px;}a:hover{background:#0056b3;color: #fff;text-decoration: none;}
    </style>
@endpush

@section('contents')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <h1>Welcome to Excel Data Management</h1>
                    <p>Manage your Excel files with import/export and DataTable functionality.</p>
                    <a href="{{ route('leads.index') }}">Go to DataTable</a>
                </div>
            </div>
        </div>
    </div>
@endsection
