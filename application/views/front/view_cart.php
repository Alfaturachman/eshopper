
<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="<?php echo base_url();?>">Home</a></li>
				  <li class="active">Shopping Cart</li>
				</ol>
			</div>
			<div class="table-responsive cart_info">
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Item</td>
							<td class="description"></td>
							<td class="price">Price</td>
							<td class="quantity">Quantity</td>
							<td class="total">Total</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php $cart_content = $this->cart->contents();
						
						?>

						<?php foreach ($cart_content as $items){ ?>

						<tr>
							<td class="cart_product">
								<a href=""><img  width="100" src="<?php echo html_escape($items['options']['pro_image'])?>" alt=""></a>
							</td>
							<td class="cart_description">
								<h4><a href=""><?php echo html_escape($items['name'])?></a></h4>
							</td>
							<td class="cart_price">
								<p>$<?php echo html_escape($items['price'])?></p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<form action="<?php echo base_url()?>update-cart-qty" method="post">
									<?php echo csrf_field(); ?>
									<a class="cart_quantity_up" href=""> + </a>
										<input class="cart_quantity_input" type="text" name="qty" value="<?php echo html_escape($items['qty'])?>" autocomplete="off" size="2">
										<a class="cart_quantity_down" href=""> - </a>
										<input  type="hidden" name="rowid" value="<?php echo html_escape($items['rowid'])?>">

										<input  type="submit"  value="Update"/>

									<form>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price">$<?php echo html_escape($items['subtotal'])?></p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" href="<?php echo base_url()?>delete-to-cart/<?php echo html_escape($items['rowid'])?>"><i class="fa fa-times"></i></a>
							</td>
						</tr>
						<?php } ?>

					</tbody>
				</table>
			</div>
		</div>
	</section> <!--/#cart_items-->

	<section id="do_action">
		<div class="container">
			<div class="heading">
				<h3>What would you like to do next?</h3>
				<p>Choose if you have a discount code or reward points you want to use or would like to estimate your delivery cost.</p>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="chose_area">
						<ul class="user_option">
							<li>
								<input type="checkbox">
								<label>Use Coupon Code</label>
							</li>
							<li>
								<input type="checkbox">
								<label>Use Gift Voucher</label>
							</li>
							<li>
								<input type="checkbox">
								<label>Estimate Shipping & Taxes</label>
							</li>
						</ul>
						<ul class="user_info">
							<li class="single_field">
								<label>Country:</label>
								<select>
									<option>United States</option>
									<option>Bangladesh</option>
									<option>UK</option>
									<option>India</option>
									<option>Pakistan</option>
									<option>Ucrane</option>
									<option>Canada</option>
									<option>Dubai</option>
								</select>
								
							</li>
							<li class="single_field">
								<label>Region / State:</label>
								<select>
									<option>Select</option>
									<option>Dhaka</option>
									<option>London</option>
									<option>Dillih</option>
									<option>Lahore</option>
									<option>Alaska</option>
									<option>Canada</option>
									<option>Dubai</option>
								</select>
							
							</li>
							<li class="single_field zip-field">
								<label>Zip Code:</label>
								<input type="text">
							</li>
						</ul>
						<a class="btn btn-default update" href="">Get Quotes</a>
						<a class="btn btn-default check_out" href="">Continue</a>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="total_area">
						<ul>
							<?php 
								$cart_total = $this->cart->total();
							?>
							<li>Cart Sub Total <span>$<?php echo html_escape($cart_total);?></span></li>
							<?php
								$tax = ($cart_total*2)/100;
							?>
							<li>Eco Tax 2% <span>$<?php echo html_escape($tax)?></span></li>
							<!-- Shipping Cost Dependend Quantity, price, buyer distance etc -->
							<?php
								$shiping = 0;
								if($cart_total>0 && $cart_total<50){
									$shiping = 0;
								}elseif($cart_total>=50 && $cart_total<100){
									$shiping = 2;
								}elseif($cart_total>=100 && $cart_total<200){
									$shiping = 5;
								}elseif($cart_total>=200){
									$shiping = 10;
								}
							?>
							<li>Shipping Cost <span>$<?php echo html_escape($shiping)?></span></li>
							<?php $g_total = $cart_total+$tax+$shiping;?>
							<li>Total <span>
								<?php
									$gdata = array();
									$gdata['g_total'] = $g_total;
									$this->session->set_userdata($gdata);
							 		echo "$" . html_escape($g_total);
							 	?>
							 </span></li>
						</ul>
							<form action="<?php echo base_url()?>update-cart-qty" method="post" >	
							<?php echo csrf_field(); ?>
							<input type="submit" class="btn btn-default update"  value="Update"/>
							<?php $customer_id = $this->session->userdata('cus_id');?>
							<?php $shipping_id = $this->session->userdata('shipping_id');?>
							<?php if($this->cart->total_items()!=Null && $customer_id!=NULL && $shipping_id!=NULL){?>
							<a class="btn btn-default check_out" href="<?php echo base_url()?>payment">Check Out</a>
							<?php } elseif($customer_id!=NULL && $this->cart->total_items()!=Null){?>
							<a class="btn btn-default check_out" href="<?php echo base_url()?>billing">Check Out</a>
							<?php }elseif($this->cart->total_items()!=Null){ ?>
							<a class="btn btn-default check_out" href="<?php echo base_url()?>checkout">Check Out</a>
							<?php } ?>
							</form>	
					</div>
				</div>
			</div>
		</div>
	</section><!--/#do_action-->