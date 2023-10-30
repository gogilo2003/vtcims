@php
$items = [['route' => 'admin-eschool-examinations', 'icon' => 'assignment', 'text' => 'Examinations'], ['route' => 'admin-eschool-examinations-tests', 'icon' => 'assignment', 'text' => 'Tests'], ['route' => 'admin-eschool-examinations-transcripts', 'icon' => 'assessment', 'text' => 'Transcripts'], ['route' => 'admin-eschool-examinations-marklists', 'icon' => 'content_paste', 'text' => 'Marklist']];
@endphp
@foreach ($items as $item)
    @include('admin::layout.components.sidebar-item',$item)
@endforeach
