<div class="modal fade" id="myModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-xs-10">
                    <h5 class="modal-title">ادخل العدد المطلوب طباعته: <span class="my-title"></span></h5>
                </div>
                <div class="col-xs-2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-3">
                            <label>العدد : </label>
                        </div>
                        <div class="col-xs-9">
                            <input type="number" min="1" class="form-control quantity" value="1" placeholder="العدد" />
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col">
                    <span class="text-center">{{ \Session::get('group_id') != 1 && \Session::has('shop_id') ? \App\Models\Shop::find(\Session::get('shop_id'))->title : 'Mlook' }}</span>
                    <div id="bcTarget"></div>
                    <p class="text-center"></p>
                    <span class="pull-left"></span>  
                    <span class="pull-right"></span>
                    <div class="clearfix"></div>  
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> طباعة</button>
                <button type="button" class="btn btn-danger btn-close" data-dismiss="modal"><i class="fa fa-home "></i> رجوع</button>
            </div>
        </div>
    </div>
</div> 