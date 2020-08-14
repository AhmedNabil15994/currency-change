@extends('Layouts.master')
@section('title', $data->data->id . ' - ' . $data->data->title)
@section('content')
<div class="">
    <div class="row" >
        <form method="post" action="{{ URL::to('/groups/update/' . $data->data->id) }}" class="form-horizontal form-label-left">
            <div class="col-md-12 col-sm-12 col-xs-12" >
                <div class="x_panel" >
                    <div class="x_title">
                        <strong>تعديل بيانات المجموعة</strong>
                        <ul class="nav navbar-right panel_toolbox">
                            <div align="right">
                                <a href="{{ URL::to('/groups') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                @if(\Helper::checkRules('edit-group'))
                                <button type="submit" class="btn btn-round btn-success">حفظ <i class="fa fa-check"></i></button>
                                @endif
                            </div>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row" >
                            <div class="col-xs-12 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label>الاسم</label>
                                    <input type="text" class="form-control" placeholder="الاسم" name="title" value="{{ $data->data->title }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-8 col-sm-6">
                                <div class="form-group">
                                    <label>الصلاحيات</label>
                                    <select id='custom-headers' class="searchable" name="permissions[]" multiple='multiple'>
                                        @forelse($data->permissions as $permissionKey => $permissionValue)
                                            <option value="{{$permissionValue}}"  {{in_array($permissionValue,$data->data->permissions) ? 'selected' : ''}}>{{$permissionValue}}</option>
                                        @empty
                                            <option value="0" disabled selected>-- لا توجد بيانات -- </option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop()

@section('script')
    <script src="{{asset('assets/components/users.js')}}"></script>
@stop
