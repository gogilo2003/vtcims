@include('admin::layout.components.sidebar-item',['route'=>'admin-eschool','icon'=>'dashboard','text'=>'E-School'])
@if (is_current_path('admin-eschool'))
    @include('admin::layout.components.sidebar-item',['route'=>'admin-eschool-students','icon'=>'people_outline','text'=>'Students'])
    @include('admin::layout.components.sidebar-item',['route'=>'admin-eschool-staff','icon'=>'people_outline','text'=>'Staff'])
    @include('admin::layout.components.sidebar-item',['route'=>'admin-eschool-examinations','icon'=>'assignment','text'=>'Examinations'])
    @include('admin::layout.components.sidebar-item',['route'=>'admin-eschool-fees-transactions','icon'=>'payment','text'=>'Fee
    Transactions'])
@endif
