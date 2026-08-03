<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CheckoutModel extends CI_Model {
	
	public function save_customer_info(){
		$data = array();
		$data['cus_name'] = $this->input->post('cus_name');
		$data['cus_email'] = $this->input->post('cus_email');
		$data['cus_password'] = password_hash($this->input->post('cus_password'), PASSWORD_DEFAULT);
		$this->db->insert("tbl_customer",$data);
		$customerid = $this->db->insert_id();
		return $customerid;
	}
	public function verify_customer_password($user,$password){
		$stored = $user->cus_password;
		if (strpos($stored, '$2y$') === 0) {
			return password_verify($password, $stored);
		}
		if (md5($password) === $stored) {
			$this->db->where('cus_id', $user->cus_id);
			$this->db->update('tbl_customer', array('cus_password' => password_hash($password, PASSWORD_DEFAULT)));
			return TRUE;
		}
		return FALSE;
	}
	public function select_customer_info_by_id($customer_id){
		$data = $this->db->select('*')
			->from('tbl_customer')
			->where("cus_id",$customer_id)
			->get()
			->row();
			return $data;
	}
	public function upate_billing_by_id(){
		$customer_id = $this->session->userdata('cus_id');
		if ($customer_id === NULL) {
			return FALSE;
		}
		$data = array();
		$data['cus_name'] = $this->input->post('cus_name');
		$data['cus_email'] = $this->input->post('cus_email');
		$data['cus_mobile'] = $this->input->post('cus_mobile');
		$data['cus_address'] = $this->input->post('cus_address');
		$data['cus_city'] = $this->input->post('cus_city');
		$data['cus_country'] = $this->input->post('cus_country');
		$data['cus_zip'] = $this->input->post('cus_zip');
		$shipping_status= $this->input->post('shipping_info');
		if($shipping_status=="on"){
		$this->db->where("cus_id",$customer_id);
		$this->db->update("tbl_customer",$data);
		$data['cus_id'] = $customer_id;
		$this->db->insert("tbl_shipping",$data);
		$customer_ship_id = $this->db->insert_id();
		$sdata = array();
		$sdata['shipping_id'] = $customer_ship_id;
		$this->session->set_userdata($sdata);
		}else{
		$this->db->where("cus_id",$customer_id);
		$this->db->update("tbl_customer",$data);
		}
		return TRUE;
	}
	public function insert_shipping(){
	$data = array();
		$data['cus_name'] = $this->input->post('cus_name');
		$data['cus_email'] = $this->input->post('cus_email');
		$data['cus_mobile'] = $this->input->post('cus_mobile');
		$data['cus_address'] = $this->input->post('cus_address');
		$data['cus_city'] = $this->input->post('cus_city');
		$data['cus_country'] = $this->input->post('cus_country');
		$data['cus_zip'] = $this->input->post('cus_zip');
		$data['cus_fax'] = $this->input->post('cus_fax');
	//	$shipping_id = $this->input->post('shipping_id');
		//$this->db->where("shipping_id",$shipping_id);
		$this->db->insert("tbl_shipping",$data);
		$customer_ship_id = $this->db->insert_id();
		$sdata = array();
		$sdata['shipping_id'] = $customer_ship_id;
		$this->session->set_userdata($sdata);
	
	}
	public function get_user_login_by_email($cus_email){
		$data = $this->db->select('*')
			->from('tbl_customer')
			->where("cus_email",$cus_email)
			->get()
			->row();
			return $data;
	}
	public function save_payment_info(){
		$data = array();
		$data['payment_type'] = $this->input->post('payment_gateway');
		$data['payment_message'] = (string) $this->input->post('payment_message');
		$this->db->insert("tbl_payment",$data);
		$sdata = array();
		$sdata['payment_id'] = $this->db->insert_id();
		$this->session->set_userdata($sdata);
	}
	public function calculate_order_total(){
		$subtotal = (float) $this->cart->total();
		$tax = round(($subtotal * 2) / 100, 2);
		$shipping = 0;
		if ($subtotal > 0 && $subtotal < 50) {
			$shipping = 0;
		} elseif ($subtotal >= 50 && $subtotal < 100) {
			$shipping = 2;
		} elseif ($subtotal >= 100 && $subtotal < 200) {
			$shipping = 5;
		} elseif ($subtotal >= 200) {
			$shipping = 10;
		}
		return array(
			'subtotal' => $subtotal,
			'tax' => $tax,
			'shipping' => $shipping,
			'total' => round($subtotal + $tax + $shipping, 2)
		);
	}
	public function validate_stock(){
		foreach ($this->cart->contents() as $item) {
			$product = $this->db->select('pro_quantity')
				->from('tbl_product')
				->where('pro_id', $item['id'])
				->get()
				->row();
			if ($product === NULL || (int) $product->pro_quantity < (int) $item['qty']) {
				return FALSE;
			}
		}
		return TRUE;
	}
	public function save_order_info(){
		$totals = $this->calculate_order_total();
		$orderdata = array();
		$orderdata['cus_id'] = $this->session->userdata('cus_id');
		$orderdata['shipping_id'] = $this->session->userdata('shipping_id');
		$orderdata['payment_id'] = $this->session->userdata('payment_id');
		$orderdata['order_total'] = $totals['total'];
		$this->db->insert("tbl_order",$orderdata);
		$order_id = $this->db->insert_id();
		foreach ($this->cart->contents() as $order_product){
			$o_details_data['order_id'] = $order_id;
			$o_details_data['product_id'] = $order_product['id'];
			$o_details_data['product_name'] = $order_product['name'];
			$o_details_data['product_price'] = $order_product['price'];
			$o_details_data['sales_quantity'] = $order_product['qty'];
			$this->db->insert("tbl_order_details",$o_details_data);
		}
		$sql = "UPDATE tbl_product, tbl_order_details
		SET tbl_product.pro_quantity = tbl_product.pro_quantity - tbl_order_details.sales_quantity 
		WHERE tbl_product.pro_id = tbl_order_details.product_id 
		AND tbl_order_details.order_id = " . (int) $order_id;
		$this->db->query($sql);
		return $order_id;
	}
	
}