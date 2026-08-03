<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="<?php echo base_url();?>">Home</a></li>
				  <li class="active">Payment</li>
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
									<form action="<?php echo base_url()?>update-cart-qty-payment" method="post">
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
								<a class="cart_quantity_delete" href="<?php echo base_url()?>delete-to-cart-payment/<?php echo html_escape($items['rowid'])?>"><i class="fa fa-times"></i></a>
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
			
			<div class="row">
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
							<li>Total <span>$<?php echo html_escape($g_total);?></span></li>
						</ul>
							<form action="<?php echo base_url()?>update-cart-qty-payment" method="post" >	
							<?php echo csrf_field(); ?>
							</form>	
					</div>
				</div>
				<div class="col-sm-6">
				<form action="<?php echo base_url()?>place-order" method="post" >
					<?php echo csrf_field(); ?>
					<div class="payment-options">
							<div class="order-message">
								<p class="alert alert-warning">Shipping Order</p>
								<?php echo $this->session->flashdata("flash_msg")?>
								<textarea name="payment_message"  placeholder="Notes about your order, Special Notes for Delivery" rows="10"></textarea>
							</div>	
							<span>
								<label><input type="radio"  name="payment_gateway" value="cash_on_delivery"> Cash on delivery</label>
							</span>
							<!-- <span>
								<label><input type="radio"  name="payment_gateway" value="paypal_payment"> Paypal</label>
							</span> -->
							<span>
								<input type="submit" name="btn" class="btn btn-primary" value="Place Order">
							</span>
						</div>
					</form>
				</div>

			</div>
		</div>
	</section><!--/#do_action-->
	