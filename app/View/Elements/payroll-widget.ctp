    <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border ui-sortable-handle">
              <h3 class="box-title">Payroll Dates</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Pay Period Start</a>
                      
                        <span class="product-description">
                          <?= date('D M-d-Y', strtotime($config['admin.payroll_start'])); ?>
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                
                <li class="item">
                  
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Pay Period End</a>
                      
                        <span class="product-description">
                          <?= date('D M-d-Y', strtotime($config['admin.payroll_end'])); ?>
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  
                  <div class="product-info">
                      <a href="javascript:void(0)" class="product-title">Pay Period Cutoff</a>
                      
                        <span class="product-description">
                          <?= date('D M-d-Y', strtotime($config['admin.payroll_cutoff'])); ?>
                        </span>
                  </div>
                </li>
                <!-- /.item -->
                <li class="item">
                  
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Pay Date</a>
                      
                        <span class="product-description">
                          <?= date('D M-d-Y', strtotime($config['admin.pay_date'])); ?>
                        </span>
                  </div>
                </li>
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
           
          </div>