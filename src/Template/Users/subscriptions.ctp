          <!-- Orders -->
          <div class="col-lg-10 g-mb-50">
            <div class="row justify-content-end g-mb-20 g-mb-0--md">
              <div class="col-md-7 g-mb-30">
                <!-- Search Form -->
                <form class="input-group g-pos-rel">
                  <span class="g-pos-abs g-top-0 g-left-0 g-z-index-3 g-px-13 g-py-10">
                    <i class="g-color-gray-dark-v4 g-font-size-12 icon-education-045 u-line-icon-pro"></i>
                  </span>
                  <input class="form-control u-form-control g-brd-around g-brd-gray-light-v3 g-brd-primary--focus g-font-size-13 g-rounded-left-5 g-pl-35 g-pa-0" type="search" placeholder="Search all subscriptions">
                  <div class="input-group-append g-brd-none g-py-0">
                    <button class="btn u-btn-black g-font-size-12 text-uppercase g-py-12 g-px-25" type="submit">Search Subscriptions</button>
                  </div>
                </form>
                <!-- End Search Form -->
              </div>
            </div>

            <!-- Links -->
            <ul class="list-inline g-brd-bottom--sm g-brd-gray-light-v3 mb-5">
              <li class="list-inline-item g-pb-10 g-pr-10 g-mb-20 g-mb-0--sm">
                <a class="g-brd-bottom g-brd-2 g-brd-primary g-color-main g-color-black g-font-weight-600 g-text-underline--none--hover g-px-10 g-pb-13" href="page-orders-1.html"><?= __('Subscriptions');?></a>
              </li>
            </ul>
            <!-- End Links -->

            <div class="mb-5" style="display:none;">
              <h3 class="h6 d-inline-block"><?php echo count($user->subscriptions);?> subscriptions <span class="g-color-gray-dark-v4 g-font-weight-400">placed in</span></h3>

              <!-- Secondary Button -->
              <div class="d-inline-block btn-group u-shadow-v19 ml-2">
                <button type="button" class="btn u-btn-black dropdown-toggle h6 align-middle g-brd-none g-color-black g-bg-gray-light-v5 g-bg-gray-light-v4--hover g-font-weight-300 g-font-size-12 g-py-10 g-ma-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  past 6 months
                </button>
                <div class="dropdown-menu rounded-0 g-font-size-12">
                  <a class="dropdown-item g-color-black g-font-weight-300" href="#!">last 30 days</a>
                  <a class="dropdown-item g-color-black g-bg-gray-light-v5 g-font-weight-300" href="#!">past 6 months</a>
                  <a class="dropdown-item g-color-black g-font-weight-300" href="#!">2017</a>
                  <a class="dropdown-item g-color-black g-font-weight-300" href="#!">2016</a>
                </div>
              </div>
              <!-- End Secondary Button -->
            </div>
			<?php if(count($user->subscriptions) > 0 ){ ?>
			<?php foreach ($user->subscriptions as $subscription) { ?>
            <!-- Order Block -->
            <div class="g-brd-around g-brd-gray-light-v4 rounded g-mb-30">
              <header class="g-bg-gray-light-v5 g-pa-20">
                <div class="row">
                  <div class="col-sm-3 col-md-3 g-mb-20 g-mb-0--sm">
                    <h4 class="g-color-gray-dark-v4 g-font-weight-400 g-font-size-12 text-uppercase g-mb-2">Subscription Date</h4>
                    <span class="g-color-black g-font-weight-300 g-font-size-13"><?php echo date("d-M-Y h:i:s",strtotime($subscription->created)); ?></span>
                  </div>

                  <div class="col-sm-3 col-md-3 g-mb-20 g-mb-0--sm">
                    <h4 class="g-color-gray-dark-v4 g-font-weight-400 g-font-size-12 text-uppercase g-mb-2">Price</h4>
                    <span class="g-color-black g-font-weight-300 g-font-size-13"><?php echo $this->Number->currency($subscription->plan->price, 'USD');?></span>
                  </div>

                  <div class="col-sm-3 col-md-3 g-mb-20 g-mb-0--sm">
                    <h4 class="g-color-gray-dark-v4 g-font-weight-400 g-font-size-12 text-uppercase g-mb-2">Status</h4>
                    <span class="g-color-black g-font-weight-300 g-font-size-13"><?php echo $subscription->status;?></span>
                  </div>

                  <div class="col-sm-3 col-md-3 ml-auto text-sm-right">
                    <h4 class="g-color-gray-dark-v4 g-font-weight-400 g-font-size-12 text-uppercase g-mb-2">Subscription Id # <?php echo $subscription->subscription_id;?></h4>
                  </div>
                </div>
              </header>

              <!-- Order Content -->
              <div class="g-pa-20">
			    <div class="row" >
				    <div class="col-md-8">
					<div class="row">
                      <div class="col-4 col-sm-5">Plan Name:</div>
                      <div class="col-8 col-sm-7">
                        <span class="d-block g-color-gray-dark-v4 g-font-size-13 mb-2"><?php echo $subscription->plan->name;?></span>
                      </div>
                    </div>
					<div class="row">
                      <div class="col-4 col-sm-5">Interval:</div>
                      <div class="col-8 col-sm-7">
                        <span class="d-block g-color-gray-dark-v4 g-font-size-13 mb-2"><?php echo $subscription->plan->payment_interval;?></span>
                      </div>
                    </div>
					</div>
				</div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-4 col-sm-5">Subscrption Start Date:</div>
                      <div class="col-8 col-sm-7">
                        <span class="d-block g-color-gray-dark-v4 g-font-size-13 mb-2"><?php echo date("d-M-Y h:i:s", $subscription->current_period_start);?></span>
                      </div>
                    </div>
					<div class="row">
                      <div class="col-4 col-sm-5">Subscrption End Date:</div>
                      <div class="col-8 col-sm-7">
                        <span class="d-block g-color-gray-dark-v4 g-font-size-13 mb-2"><?php echo date("d-M-Y h:i:s", $subscription->current_period_end);?></span>
                      </div>
                    </div>
					<?php if($subscription->canceled_at){ ?>
					<div class="row">
                      <div class="col-4 col-sm-5">Subscrption Cancelled Date:</div>
                      <div class="col-8 col-sm-7">
                        <span class="d-block g-color-gray-dark-v4 g-font-size-13 mb-2"><?php echo date("d-M-Y h:i:s", $subscription->canceled_at);?></span>
                      </div>
                    </div>
					<?php } ?>
                  </div>

                  <div class="col-md-4">
                    <?php
					if($subscription->status == 'active'){
					    echo $this->Html->link(
							'Cancel Subscription',
							['controller' => 'Subscriptions', 'action' => 'cancel_subscription', "?" => ["id" => $subscription->id,"subscription_id" => $subscription->subscription_id, "cust_id" => $subscription->customer]],
							['class' => 'btn btn-block g-brd-around g-brd-gray-light-v3 g-color-gray-dark-v3 g-bg-gray-light-v5 g-bg-gray-light-v4--hover g-font-size-12 text-uppercase g-py-12 g-px-25']
						);
					}/*else{
						echo $this->Html->link(
							'Reactivate Subscription',
							['controller' => 'Subscriptions', 'action' => 'retrieve_subscription', "?" => ["id" => $subscription->id, "subscription_id" => $subscription->subscription_id, "cust_id" => $subscription->customer]],
							['class' => 'btn btn-block u-btn-primary g-font-size-12 text-uppercase g-py-12 g-px-25 mb-4']
						);
					}*/
					?>
                  
                  </div>
                </div>
              </div>
              <!-- End Order Content -->
            </div>
            <!-- End Order Block -->
            <?php } ?>
            <?php } ?>
            
          </div>
