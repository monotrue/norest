<?php
class ControllerExtensionModuleNorest extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/norest');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('norest', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

		//	$this->response->redirect($this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_allowed'] = $this->language->get('entry_allowed');
		

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/norest', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/norest', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['norest_status'])) {
			$data['norest_status'] = $this->request->post['norest_status'];
		} else {
			$data['norest_status'] = $this->config->get('norest_status');
		}
		
		
		
		if (isset($this->request->post['norest_key'])) {
			$data['norest_key'] = $this->request->post['norest_key'];
		} else {
			$data['norest_key'] = $this->config->get('norest_key');
		}
		
		
		if (isset($this->request->post['norest_allowed'])) {
			$data['norest_allowed'] = $this->request->post['norest_allowed'];
		} else {
			$data['norest_allowed'] = $this->config->get('norest_allowed');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/norest', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/norest')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}