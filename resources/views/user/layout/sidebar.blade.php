<aside class="sidebar">
    <div class="brand">
        <img src="{{ asset('user/assets/images/logo.png') }}" alt="GoMeal">
    </div>

    <nav class="nav">
        <a href="#" class="nav-item active">Dashboard</a>
        <a href="#" class="nav-item">Food Order</a>
        <a href="{{ route('user.items.index') }}" class="nav-item">Items</a>
        <a href="#" class="nav-item">Order History</a>
        <a href="#" class="nav-item">Settings</a>
    </nav>

    <div class="promo">
        <div class="promo-card">
            <p>Upgrade your Account to get Free Voucher</p>
            <button class="btn-primary">Upgrade</button>
        </div>
    </div>
</aside>
