@php
$items = [['route' => 'admin-eschool-fees', 'icon' => 'account_balance_wallet', 'text' => 'Fees'], ['route' => 'admin-eschool-fees-transactions', 'icon' => 'payment', 'text' => 'Fee Transactions'], ['route' => 'admin-eschool-fees-vote_heads', 'icon' => 'account_balance', 'text' => 'Vote Heads']];
@endphp
@foreach ($items as $item)
    @include('admin::layout.components.sidebar-item',$item)
@endforeach
