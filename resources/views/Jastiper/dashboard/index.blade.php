@extends('layout.jastiper-app')

@section('title', 'Dashboard - Jastiper')

@push('styles')
    {{-- jika butuh css khusus --}}
@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/js/init/weather-init.js') }}"></script>
    <script src="{{ asset('admin/assets/js/init/fullcalendar-init.js') }}"></script>
    <script>
        jQuery(document).ready(function ($) {
            // Chart + widget init bisa disalin dari admin jika perlu.
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="small-stats-row">
                <div class="small-stat-card">
                    <div class="icon bg-accent-1">
                        <img src="{{ asset('admin/assets/images/icons/dashboard.svg') }}" alt="dashboard">
                    </div>
                    <div>
                        <div class="value">0</div>
                        <div class="label">Total Pesanan</div>
                    </div>
                </div>

                <div class="small-stat-card">
                    <div class="icon bg-accent-2">
                        <img src="{{ asset('admin/assets/images/icons/pesanan.svg') }}" alt="pesanan">
                    </div>
                    <div>
                        <div class="value">0</div>
                        <div class="label">Pesanan Selesai</div>
                    </div>
                </div>

                <div class="small-stat-card">
                    <div class="icon bg-accent-3">
                        <img src="{{ asset('admin/assets/images/icons/pembayaran.svg') }}" alt="pembayaran">
                    </div>
                    <div>
                        <div class="value">Rp 0</div>
                        <div class="label">Pendapatan</div>
                    </div>
                </div>

                <div class="small-stat-card">
                    <div class="icon bg-accent-1">
                        <img src="{{ asset('admin/assets/images/icons/ulasan.svg') }}" alt="ulasan">
                    </div>
                    <div>
                        <div class="value">0</div>
                        <div class="label">Ulasan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
