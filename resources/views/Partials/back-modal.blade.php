<div class="modal fade" id="back" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-xs-10">
                    <h5 class="modal-title">اختر سبب الاسترجاع: <span class="my-title"></span></h5>
                </div>
                <div class="col-xs-2">
                    <button type="button" class="close pull-left" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-3">
                            <label>السبب : </label>
                        </div>
                        <div class="col-xs-9">
                            <select class="form-control select2" name="reason">
                                <option value="1" {{ Input::get('reason') == 1 ? 'selected' : '' }}>استرجاع بدون سبب</option>
                                <option value="2" {{ Input::get('reason') == 2 ? 'selected' : '' }}>استرجاع للتلف</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-3">
                            <label>المرتجع للعميل : </label>
                        </div>
                        <div class="col-xs-9">
                            <input type="number" class="form-control" placeholder="المرتجع للعميل" name="back" min="1">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-3">
                            <label>الوصف : </label>
                        </div>
                        <div class="col-xs-9">
                            <textarea class="form-control" placeholder="الوصف" name="description"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-arrow-left"></i> استرجاع</button>
                <button type="button" class="btn btn-danger btn-close" data-dismiss="modal"><i class="fa fa-home "></i> رجوع</button>
            </div>
        </div>
    </div>
</div> 