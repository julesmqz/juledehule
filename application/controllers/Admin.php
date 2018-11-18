<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admin extends JH_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->_verifySession();
        $this->load->library('grocery_CRUD');
    }

    public function profile()
    {
        $userId = $this->session->user;

        $crud = new grocery_CRUD();

        $crud->where('id', $userId);
        $crud->set_table('user');
        $crud->set_subject('User Profile');

        $crud->unset_list();
        $crud->unset_back_to_list();

        $crud->unset_delete();
        $crud->unset_add();
        $crud->unset_read();
        $crud->unset_export();
        $crud->unset_print();
        $crud->unset_texteditor('summary');


        try {
            $output = $crud->render();
            $this->_loadAdmin($output);
        } catch (Exception $e) {

            if ($e->getCode() == 14) { //The 14 is the code of the error on grocery CRUD (don't have permission).
                //redirect using your user id
                redirect(strtolower(__CLASS__) . '/' . strtolower(__FUNCTION__) . '/edit/' . $userId);

            } else {
                show_error($e->getMessage());
                return false;
            }
        }

    }

    public function categories()
    {
        $crud = new grocery_CRUD();

        $crud->set_table('category');
        $crud->set_subject('Categories');
        $crud->columns('name', 'friendly_url');
        $crud->fields('name', 'friendly_url');

        $output = $crud->render();

        $this->_loadAdmin($output);
    }

    public function posts()
    {
        $crud = new grocery_CRUD();

        $crud->set_table('post');
        $crud->set_subject('Posts');
        $crud->set_relation('category_id', 'category', 'name');
        $crud->set_relation('user_id', 'user', 'name');
        $crud->columns('title', 'summary', 'category_id', 'friendly_url', 'created_on', 'updated_on');

        //$crud->add_fields(array('customerName', 'contactLastName', 'city', 'creditLimit'));
        //$crud->edit_fields(array('title', 'summary', 'city'));

        //$crud->required_fields(array('customerName', 'contactLastName'));
        //$crud->unset_columns('summary');
        //$crud->callback_column('buyPrice', array($this, 'valueToEuro'));

        $output = $crud->render();

        $this->_loadAdmin($output);
    }

    public function slider()
    {
        $crud = new grocery_CRUD();

        $crud->set_table('slider');
        $crud->set_subject('Slider Posts');
        $crud->set_relation('post_id', 'post', 'title');
        //$crud->columns('title', 'summary', 'category_id', 'friendly_url', 'created_on', 'updated_on');

        //$crud->add_fields(array('customerName', 'contactLastName', 'city', 'creditLimit'));
        //$crud->edit_fields(array('title', 'summary', 'city'));

        //$crud->required_fields(array('customerName', 'contactLastName'));
        //$crud->unset_columns('summary');
        //$crud->callback_column('buyPrice', array($this, 'valueToEuro'));

        $output = $crud->render();

        $this->_loadAdmin($output);
    }

}
