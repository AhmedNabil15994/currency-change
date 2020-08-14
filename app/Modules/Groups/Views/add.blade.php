@extends('Layouts.master')
@section('title', 'اضافة مجموعة')
@section('content')
    <div class="">
        <div class="row" >
            <form method="post" action="{{ URL::to('/groups/create/') }}" class="form-horizontal form-label-left">
                <div class="col-md-12 col-sm-12 col-xs-12" >
                    <div class="x_panel" >
                        <div class="x_title">
                            <strong>اضف بيانات المجموعة</strong>
                            <ul class="nav navbar-right panel_toolbox">
                                <div align="right">
                                    <a href="{{ URL::to('/groups') }}" class="btn btn-round btn-primary"><i class="fa fa-arrow-left"></i> الرجوع</a>
                                    @if(\Helper::checkRules('add-group'))
                                    <button type="submit" class="btn btn-round btn-success">اضافة <i class="fa fa-plus"></i></button>
                                    @endif
                                </div>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label>الاسم</label>
                                        <input type="text" class="form-control" placeholder="الاسم" name="title" value="{{ old('title') }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-8 col-xs-12">
                                    <div class="form-group">
                                        <label>الصلاحيات</label>
                                        <select id='custom-headers' class="searchable" name="permissions[]" multiple='multiple'>
                                        @forelse($data->permissions as $permissionKey => $permissionValue)
                                            <option value="{{$permissionValue}}">{{$permissionValue}}</option>
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
    <script src="{{asset('assets/components/groups.js')}}"></script>
@stop
