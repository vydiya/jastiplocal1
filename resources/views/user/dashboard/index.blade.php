@extends('user.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Hello, Samantha')

@section('content')
    <div class="dashboard-grid">
        <section class="main-cards">
            <div class="card promo-banner">
                <div class="promo-left">
                    <h3>Get Discount Voucher Up To 20%</h3>
                    <p>Use promo and save on your next order.</p>
                </div>
                <div class="promo-right">
                    <img src="{{ asset('user/assets/images/promo-person.png') }}" alt="promo" />
                </div>
            </div>

            <h3 class="section-title">Popular Dishes</h3>
            <div class="cards-row">
                {{-- contoh card --}}
                <div class="card item-card">
                    <img src="{{ asset('user/assets/images/burger1.jpg') }}" alt="burger">
                    <h4>Fish Burger</h4>
                    <div class="price">$5.59</div>
                    <button class="btn-primary small">+</button>
                </div>
                <div class="card item-card">
                    <img src="{{ asset('user/assets/images/burger2.jpg') }}" alt="burger">
                    <h4>Beef Burger</h4>
                    <div class="price">$5.59</div>
                    <button class="btn-primary small">+</button>
                </div>
                <div class="card item-card">
                    <img src="{{ asset('user/assets/images/burger3.jpg') }}" alt="burger">
                    <h4>Cheese Burger</h4>
                    <div class="price">$5.59</div>
                    <button class="btn-primary small">+</button>
                </div>
            </div>
        </section>

        <aside class="sidebar-right">
            <div class="card order-summary">
                <h4>Order Menu</h4>
                <ul class="order-list">
                    <li>Pepperoni Pizza <span class="price">+$5.59</span></li>
                    <li>Cheese Burger <span class="price">+$5.59</span></li>
                </ul>
                <div class="checkout">
                    <button class="btn-primary full">Checkout</button>
                </div>
            </div>
        </aside>
    </div>
@endsection
