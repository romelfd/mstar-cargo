<?php 
date_default_timezone_set('Asia/Manila');

require_once('model.revo-ticketing.php');
class revoTicketing {

    public function __construct(){
        $this->plugin_path = ABSPATH . 'wp-content/plugins/revo-custom-ticketing/';
        /* TICKET FIELD OPTIONS */
        $this->ticket_status_options = array('for_verification' => 'For Verification', 'for_checking' => 'For Checking', 'for_web_cro' => 'For Web CRO', 'approved' => 'Approved', 'completed' => 'Completed');
        $this->ticket_type_options = array('import' => 'Import', 'export' => 'Export', 'refund' => 'Refund');
        $this->ticket_free_time_options = array('detention_demurrage' => 'Detention/Demurrage', 'combined' => 'Combined');
        $this->ticket_import_processor_options = array('ultimate_direct_consignee' => 'Ultimate Consignee / Direct Consignee', 'forwarder_broker' => 'Forwarder / Broker');
        $this->ticket_department_options = array('accounting' => 'Accounting', 'documentation' => 'Documentation', 'documentation_supervisor' => 'Documentation Supervisor', 'operations' => 'Operations');
        
        /* AGENT DEPARTMENT*/
        
        $this->agent_department_options = array('accounting' => 'Accounting', 'documentation' => 'Documentation', 'documentation_supervisor' => 'Documentation Supervisor', 'operations' => 'Operations', 'admin' => 'Admin');
        /* MESSAGES */
        $this->revo_messages = array('ticket' => 
            array ('action' => 'move_to_dept', 'message' => 'Ticket has been move to Department:')
        );

        $this->current_user = '';
        $this->current_user_meta = '';

      
       
        $this->revoticketing_model = new revoTicketingModel();

    }

    public function run(){
        /* LOAD STYLE */
        if (!is_admin()) {
            wp_enqueue_style( 'custom-fa', 'https://use.fontawesome.com/releases/v5.0.7/css/all.css' );
            wp_enqueue_style( 'bootstrap',  plugin_dir_url( __FILE__ ) . 'scripts/bootstrap-4.5.2-dist/css/bootstrap.min.css', array(), 20141119 );
            wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'scripts/bootstrap-4.5.2-dist/js/bootstrap.bundle.min.js', array('jquery'), '20120206', true );
       
        }

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'ajax_script' );
		wp_enqueue_style( 'custom-style' );

        add_action('init', array(&$this, 'ticket_register'));
        add_action('init', array(&$this, 'bol_register'));
        
        add_action( 'init', array(&$this,'add_roles'));
        add_action("admin_init", array(&$this, 'add_ticket_meta_box'));
      //  add_action("admin_init", array(&$this, 'add_shipping_company_meta_box'));
        add_action('save_post', array(&$this, 'save_meta_data'));
        
        add_action( 'init', array(&$this,'init_plugin'));
       
        /* SHORTCODES */

        /* CLIENT */
        add_shortcode('revo_ticket_form', array(&$this,'revo_ticket_form_shortcode'));
        add_shortcode('revo_bl_number', array(&$this,'revo_bl_number_shortcode'));
        add_shortcode('revo_processing_tickets', array(&$this,'revo_processing_tickets_shortcode'));
        add_shortcode( 'revo-password-lost-form', array( $this, 'render_password_lost_form' ) );
        add_shortcode( 'revo-password-reset-form', array( $this, 'render_password_reset_form' ) );

        add_action('show_user_profile', array(&$this,'revo_client_profile_edit_action'));
        add_action('edit_user_profile', array(&$this,'revo_client_profile_edit_action'));

        add_action('personal_options_update', array(&$this,'revo_client_profile_update_action'));
        add_action('edit_user_profile_update', array(&$this,'revo_client_profile_update_action'));

        /* AGENT */
        add_shortcode('revo_admin_dashboard', array(&$this,'revo_admin_dashboard_shortcode'));

        add_action( 'wp_ajax_create_ticket', array(&$this, 'create_ticket' ));
        add_action( "wp_ajax_nopriv_create_ticket",array(&$this,"create_ticket"));

        add_action( 'wp_ajax_agent_save_ticket', array(&$this, 'agent_save_ticket' ));
        add_action( "wp_ajax_nopriv_agent_save_ticket",array(&$this,"agent_save_ticket"));

        add_action( 'wp_ajax_client_save_ticket', array(&$this, 'client_save_ticket' ));
        add_action( "wp_ajax_nopriv_client_save_ticket",array(&$this,"client_save_ticket"));

        add_action( 'wp_ajax_register_user', array(&$this, 'register_user' ));
        add_action( "wp_ajax_nopriv_register_user", array(&$this,"register_user"));

        add_action( 'wp_ajax_login_user', array(&$this, 'login_user' ));
        add_action( "wp_ajax_nopriv_login_user", array(&$this,"login_user"));
   
        add_action( 'wp_ajax_get_client_ticket', array(&$this, 'get_client_ticket' ));
        add_action( "wp_ajax_nopriv_get_client_ticket", array(&$this,"get_client_ticket"));

        add_action( 'wp_ajax_upload_xml_file', array(&$this, 'upload_xml_file' ));
        add_action( "wp_ajax_nopriv_upload_xml_file", array(&$this,"upload_xml_file"));

        add_action( 'wp_ajax_load_user_page', array(&$this, 'load_user_page' ));
        add_action( "wp_ajax_nopriv_load_user_page", array(&$this,"load_user_page"));

        add_action( 'wp_ajax_agent_client_action', array(&$this, 'agent_client_action' ));
        add_action( "wp_ajax_nopriv_agent_client_action", array(&$this,"agent_client_action"));

        add_action( 'wp_ajax_get_comments', array(&$this, 'get_comments' ));
        add_action( "wp_ajax_nopriv_get_comments", array(&$this,"get_comments"));

        add_action( 'wp_ajax_add_comment', array(&$this, 'add_comment' ));
        add_action( "wp_ajax_nopriv_add_comment", array(&$this,"add_comment"));

        add_action( 'wp_ajax_load_agent_tickets', array(&$this, 'load_agent_tickets' ));
        add_action( "wp_ajax_nopriv_load_agent_tickets", array(&$this,"load_agent_tickets"));

        /* LOST PASSWORD */
        add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
        add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );

        add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );
        
        add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );

        add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );

    }

    function init_plugin()
    {
        if (!is_admin()) {
            wp_register_script( 
                'ajax_script', 
                plugins_url( '/scripts/script.js',__FILE__ ), 
                array('jquery')
            );

            wp_register_script( 
                'dropzone', 
                plugins_url( '/scripts/dropzone/dropzone.js',__FILE__ ), 
                array('jquery')
            );
            
            wp_register_style( 'custom-style',  plugin_dir_url( __FILE__ ) . 'styles/custom-style.css' );
        
            wp_localize_script( 
                'ajax_script', 
                'myAjax', 
                array(
                    'url'   => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'revoph' )
                )
            );
            
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'ajax_script' );
            wp_enqueue_script( 'dropzone' );
            wp_enqueue_style( 'custom-style' );
        }
        /* HIDE ADMIN TOOLBAR */
        if ( is_user_logged_in()) {
            if ( !current_user_can( 'manage_options' ) ) {
                add_filter('show_admin_bar', '__return_false');
            }
        }

       

        if ( is_user_logged_in() ) {
             $this->current_user = wp_get_current_user();
             $this->current_user_meta = get_user_meta( $this->current_user->data->ID );         
        }
	}

    /* REGISTER CUSTOM POST TYPE TICKET */
    public function ticket_register() {
        $labels = array(
            'name'  => _x('Tickets', 'post type general name'),
            'singular_name'  => _x('Ticket', 'post type singular name'),
            'add_new'  => _x('Add New', 'ticket'),
            'add_new_item'  => __('Add New ticket'),
            'edit_item'  => __('Edit ticket'),
            'new_item'  => __('New ticket'),
            'view_item'  => __('View ticket'),
            'search_items'  => __('Search'),
            'not_found'  =>  __('Nothing found'),
            'not_found_in_trash'  => __('Nothing found in Trash'),
            'parent_item_colon'  => ''
        );
    
        $args = array(
            'labels'  => $labels,
            'public'  => true,
            'publicly_queryable'  => true,
            'show_ui'  => true,
            'query_var'  => true,
            'menu_icon'  => 'dashicons-tickets-alt',
            'rewrite'  => true,
            'capability_type'  => 'post',
            'hierarchical'  => false,
            'menu_position'  => null,
            'supports'  => array('title','editor','author','excerpt','comments','revisions'),
            'exclude_from_search' => true

        ); 
    
        register_post_type( 'ticket' , $args );
    }

    public function bol_register() {
        $labels = array(
            'name'  => _x('Bill of Lading', 'post type general name'),
            'singular_name'  => _x('Bill of Lading', 'post type singular name'),
            'add_new'  => _x('Add New', 'shipping_bol'),
            'add_new_item'  => __('Add New'),
            'edit_item'  => __('Edit'),
            'new_item'  => __('New'),
            'view_item'  => __('View'),
            'search_items'  => __('Search'),
            'not_found'  =>  __('Nothing found'),
            'not_found_in_trash'  => __('Nothing found in Trash'),
            'parent_item_colon'  => ''
        );
    
        $args = array(
            'labels'  => $labels,
            'public'  => true,
            'publicly_queryable'  => true,
            'show_ui'  => true,
            'query_var'  => true,
            'menu_icon'  => 'dashicons-building',
            'rewrite'  => true,
            'capability_type'  => 'post',
            'hierarchical'  => false,
            'menu_position'  => null,
            'supports'  => array('title','editor','thumbnail'),
            'exclude_from_search' => true

        ); 
    
        register_post_type( 'shipping_bol' , $args );
    } 

    /* ADD META BOX (CUSTOM FIELDS) */

    function add_ticket_meta_box(){
        add_meta_box("ticket_details", "Ticket Details", array(&$this, "ticket_details"), "ticket", "normal", "default");
    }
       
    function ticket_details(){
        global $post;
        $ticket_status_options = $this->ticket_status_options;
        $ticket_type_options = $this->ticket_type_options;
        $ticket_department_options = $this->ticket_department_options;
        $ticket_number = '0';
        $ticket_status = 'new';
        $ticket_type = 'import';
        $ticket_department = '';
        $ticket_folder = '';
        $bol_number = '';
        $client_name  = '';
        
        $custom_fields = get_post_custom($post->ID);

        if (!empty($custom_fields)) { 
           
            $client_name = $custom_fields["client_name"][0];
            $ticket_number = $custom_fields["ticket_number"][0];
            $ticket_status = $custom_fields["ticket_status"][0];
            $ticket_type =  $custom_fields["ticket_type"][0];
            $bol_number =  $custom_fields["bol_number"][0];
            $ticket_department = $custom_fields["ticket_department"][0];
            $ticket_folder = $ticket_number;
        }

        //LIST DOCUMENTS
        
        $documents_f =  $this->revoticketing_model->get_documents($this->plugin_path, $ticket_folder);
        
        include_once($this->plugin_path . 'views/revo_ticket_meta_box.php');
    }

    public function save_meta_data(){
        $this->revoticketing_model->save_meta_data();
    }

    /* ADD CUSTOM USER ROLE */
    public function add_roles() {
        add_role( 'custom_client', 'Client', array( 'read' => true, 'level_0' => true ) );
        add_role( 'custom_agent', 'Agent', array( 'read' => true, 'level_0' => true ) );
    }

    /* ADD CUSTOM FORM SHORTCODE */

    /* DISPLAY FORM CREATING TICKET */
    public function revo_ticket_form_shortcode() {
        if (!is_admin()) {
            if ( is_user_logged_in() ) {
                $current_client = $this->current_user->data;
                if ($this->current_user->roles[0] == 'custom_client') {
                //GET USER META DATA
                $all_client_meta = get_user_meta($current_client->ID);
                $client_username = $all_client_meta['first_name'][0] . ' ' . $all_client_meta['last_name'][0];
                include_once($this->plugin_path . 'views/revo_ticket_form_view.php');
                } else {
                    $all_client_meta = get_user_meta($current_client->ID);
                    $client_username = $all_client_meta['first_name'][0] . ' ' . $all_client_meta['last_name'][0];
                    include_once($this->plugin_path . 'views/revo_not_allowed_view.php');  
                }
            } else {
                $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';
                $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';
                include_once($this->plugin_path . 'views/revo_ticket_login_form_view.php');
            }
           return $output;
        }
    }
     
    /* DISPLAY BL NUMBER */
    public function revo_bl_number_shortcode() {
        if (!is_admin()) {
            include_once($this->plugin_path . 'views/revo_bl_number_view.php');
            return $output;
        }
    }

    /* DISPLAY PROCESSING TICKETS */
    public function revo_processing_tickets_shortcode() {
        $processing_tickets = array();
        if ( is_user_logged_in() ) {
            $processing_tickets = $this->revoticketing_model->get_processing_tickets($this->current_user->data->ID);
        }
        if (!is_admin()) {
            include_once($this->plugin_path . 'views/revo_processing_tickets_view.php');
            return $output;
        }
    }

    /* AJAX CREATE TICKET */
    public function create_ticket() {
        $created_ticket_info = $this->revoticketing_model->create_ticket($_REQUEST);
        $ticket_type = $created_ticket_info['ticket_type'];
        $bol_number = $created_ticket_info['bol_number'];
        $ticket_number = $created_ticket_info['ticket_number'];

        $email_subject_add = "Ticket#".$ticket_number;
        
        if ($ticket_type == 'import') {
            $email_subject_add .=  ' BL#'.$bol_number;
        }

        if ($created_ticket_info['post_id'] != 0) {
                $ticket_status_args = array(
                    'update_name' => 'new ticket created',
                    'ticket_info' => array(
                        'ticket_number' => $ticket_number,
                        'ticket_department' => 'accounting',
                        'ticket_type' =>  $ticket_type,
                        'bol_number' =>   $bol_number
                    ),
                     'send_to' => array(
                        'client' => array(
                            'id' => $this->current_user->data->ID,
                            'email_template' => 'client_submitted_new_ticket',
                            'email_subject' => 'You have created a new ticket'
                        ),
                        'agent' => array(
                            'email_template' => 'agent_new_ticket',
                            'email_subject' => 'New ticket' . " (". $email_subject_add .")"
                        )
                    ),
                    'add_to_comment' => array('comment' => 'Submitted a new ticket.', 'comment_post_ID' => $created_ticket_info['post_id'])

                );
                
                $this->send_ticket_updates($ticket_status_args);
                
        }
        echo json_encode($created_ticket_info);
        die();
    }   

    /* AJAX REGISTER USER */
    public function register_user() {
        $registered_user  = $this->revoticketing_model->register_user($_REQUEST);
        if ($registered_user['success']) {
            $notification_args = array(
                'update_name' => 'new user registration',
                'send_to' => array(
                    'client' => array(
                        'id' => $registered_user['data']['client_id'],
                        'email_template' => 'client_new_registration',
                        'email_subject' => 'Registration successful'
                    ),
                    'agent' => array(
                        'email_template' => 'agent_new_user_registration',
                        'email_subject' => 'New User Membership Request'
                    )
                )
               
            );
            
            $this->send_notification($notification_args);
            
    }

        echo json_encode( $registered_user );
        die();
    }   

    /* AJAX LOGIN USER */
    public function login_user() {
        $client_login  = $this->revoticketing_model->login_user($_REQUEST);
        echo json_encode( $client_login );
        die();
    }   

    /* ADD CLIENT FIELDS */
    
    function revo_client_profile_edit_action($user) {
       
        $checked = (isset($user->client_approved) && $user->client_approved ? ' checked="checked"' : '');

        //AGENT DEPARTMENT
        if ( $role = $user->roles[0] == 'custom_agent') {
            $agent_departments_options = $this->agent_department_options;
            $user_meta_departments = get_user_meta( $user->ID, 'agent_departments', true );
           
        ?>
        <h3>Department</h3>
        <?php foreach ($agent_departments_options as $agent_department_key => $agent_department_option ) { ?>
            <label>
                <input name="agent_departments[]" type="checkbox"  value="<?php echo $agent_department_key; ?>" <?php echo ( in_array($agent_department_key, $user_meta_departments)) ? 'checked' : '' ?>>
                <?php echo $agent_department_option; ?>
            </label><br/>
        <?php } //CLIENT ?>
        <?php } else { ?>    
            <h3>Other</h3>
            <label for="client_approved">
                <input name="client_approved" type="checkbox" id="client_approved" value="1"<?php echo $checked; ?>>
                Approved Client
            </label>
        <?php } ?>
    <?php 
    }

    function revo_client_profile_update_action($user_id) {
        
        update_user_meta($user_id, 'client_approved', isset($_POST['client_approved']));
        
        if (isset($_POST['agent_departments'])) {
            update_user_meta($user_id, 'agent_departments', $_POST['agent_departments']);
        }
    }


    /* AGENT */
    public function revo_admin_dashboard_shortcode() {
        if (!is_admin()) {
            if ( is_user_logged_in() ) {
                $role = $this->current_user->roles[0];
                /* GET DEPARTMENT */
                if ( $role == 'custom_agent' ) {
                    $agent_department_options = $this->agent_department_options;
                    $agent_id =  $this->current_user->data->ID;
                    $agent_departments = unserialize($this->current_user_meta['agent_departments'][0]);

                    $agent_username = $this->current_user_meta['first_name'][0] . ' ' . $this->current_user_meta['last_name'][0];
                   // $client_tickets = $this->revoticketing_model->get_all_client_tickets($agent_department);
                    include_once($this->plugin_path . 'views/agent/revo_admin_dashboard_view.php');
                } else {
                    include_once($this->plugin_path . 'views/agent/revo_admin_dashboard_not_allowed_view.php');
                }
            } else {
               include_once($this->plugin_path . 'views/agent/revo_admin_dashboard_login_view.php');
            }
           return $output;
        }
    }

    public function load_agent_tickets() {
        $agent_department = $_REQUEST['agent_department'];
        $paged = $_REQUEST['paged'];
        $current_user_departments = $this->current_user_meta['agent_departments'][0];
        $client_tickets = $this->revoticketing_model->get_all_client_tickets($agent_department,$paged);
        include_once($this->plugin_path . 'views/agent/revo_admin_tickets_view.php');
        echo $output;
        die();
    }

    public function get_client_ticket() {
        $ticket = $this->revoticketing_model->get_client_ticket($_REQUEST);
        $ticket_status_options = $this->ticket_status_options;
        $ticket_free_time_options = $this->ticket_free_time_options;
        $agent_department_options = $this->agent_department_options;
        
        if ($ticket) {
            /* GET TICKET META */
            $cfields = get_post_meta($ticket[0]->ID);
            $ticket_freetime = get_post_meta($ticket[0]->ID,'ticket_freetime', true);
            $ticket_folder = get_post_meta($ticket[0]->ID,'ticket_number', true);
            $documents_f =  $this->revoticketing_model->get_documents($this->plugin_path, $ticket_folder);
            $ticket_department = $cfields['ticket_department'][0];
            $ticket_agent_departments_r = $this->current_user_meta['agent_departments'][0]; 
            $ticket_agent_departments = unserialize( $ticket_agent_departments_r );
            /* SET NEW STATUS IF APPROVED */
            if ($this->current_user->roles[0] == 'custom_agent' ) {
                if ($cfields['ticket_status'][0] == 'approved') { 
                   
                    if (in_array($ticket_department, $ticket_agent_departments)) {
                        $new_ticket_status = 'for_verification';
                        if ($ticket_department == 'documentation') {
                            $new_ticket_status = 'for_checking';
                        }
                        if ($ticket_department == 'operations') {
                            $new_ticket_status = 'for_web_cro';
                        }

                        update_post_meta($ticket[0]->ID, "agent_id",  $this->current_user->data->ID);
                        update_post_meta($ticket[0]->ID, "ticket_status", $new_ticket_status );

                        $update = array( 'ID' => $ticket[0]->ID );
                        wp_update_post( $update );
                        $cfields = get_post_meta($ticket[0]->ID);
                    }
                }
            }
            
        }
        
        if ($_REQUEST['client_ticket'] == 1) {
            include_once($this->plugin_path . 'views/revo_client_ticket_details_view.php');
        } else {
            include_once($this->plugin_path . 'views/agent/revo_admin_ticket_details_view.php');
        }
       
        echo $output;
        die();
    }

    public function agent_save_ticket() {
        /* CHECK TICKET UPDATES */
       
        $ticket_changes = $this->get_ticket_updates($_REQUEST);
        $response = $this->revoticketing_model->agent_save_ticket($_REQUEST);
        echo json_encode($response); 
        die();
    }

    public function client_save_ticket() {
        /* CHECK TICKET UPDATES */
        
        $ticket_changes = $this->get_ticket_updates($_REQUEST);
        $response = $this->revoticketing_model->client_save_ticket($_REQUEST);
        echo json_encode($response); 
        die();
    }

    public function load_user_page() {
        if ($_REQUEST['agent_admin_page'] == 'all_clients' || $_REQUEST['agent_admin_page'] == 'for_approval_clients') {
            $clients = $this->revoticketing_model->get_all_clients($_REQUEST);
            include_once($this->plugin_path . 'views/agent/revo_admin_clients_view.php');
            echo $output;
        }
    
        if ($_REQUEST['agent_admin_page'] == 'account_profile' || $_REQUEST['client_page'] == 'account_profile') {
            $user_avatar_url = get_avatar_url($this->current_user->data->ID, ['size' => '300']);
            if ($_REQUEST['agent_admin_page']) {
                if ($this->current_user_meta['agent_departments'][0]) {
                    $agent_department_options = $this->agent_department_options;
                    $user_department_r = unserialize($this->current_user_meta['agent_departments'][0]);
                    foreach($user_department_r as $user_department_t) {
                        $user_department_text_r[] =  $agent_department_options[$user_department_t];
                    }
                    
                    $agent_department_text = implode(" | ", $user_department_text_r);
                    
                    $user_department =  $agent_department_text;
                }
            }
            $user_email = $this->current_user->data->user_email;
            $user_username = $this->current_user_meta['first_name'][0] . ' ' . $this->current_user_meta['last_name'][0];
          
            include_once($this->plugin_path . 'views/revo_account_page_view.php');
            echo $output;
        }

        if ($_REQUEST['agent_admin_page'] == 'bill_of_lading') {
            $add_bol_success = false;
            $bol_number = '';
            $paged = $_REQUEST['paged'];
            $bols = $this->revoticketing_model->get_all_bols($paged);
           
            include_once($this->plugin_path . 'views/agent/revo_admin_bols_view.php');
            echo $output;
        }

        if ($_REQUEST['agent_admin_page'] == 'add_new_bol') {
            include_once($this->plugin_path . 'views/agent/revo_admin_add_new_bol_view.php');
            echo $output;
        }

        if ($_REQUEST['agent_admin_page'] == 'edit_bol') {
            if ($_REQUEST['bol_id'] != 0) {
                $bol = $this->revoticketing_model->get_all_bol_by($_REQUEST['bol_id']);
            }
            include_once($this->plugin_path . 'views/agent/revo_admin_add_new_bol_view.php');
            echo $output;
        }

        if ($_REQUEST['agent_admin_page'] == 'view_bol') {
            if ($_REQUEST['bol_id'] != 0) {
                $bol = $this->revoticketing_model->get_all_bol_by($_REQUEST['bol_id']);
            }
            include_once($this->plugin_path . 'views/agent/revo_admin_view_bol_view.php');
            echo $output;
        }

        if ($_REQUEST['agent_admin_page'] == 'delete_bol') {
            if ($_REQUEST['bol_id'] != 0) {
                $bol_info = $this->revoticketing_model->delete_bol($_REQUEST['bol_id']);
            }
            if ( $bol_info['success'] ) {
                $add_bol_success = true;

                if ($bol_info['action'] == 'delete_bol') {
                    $bol_message = "has been deleted successfully.";
                }

                $bol_number = $bol_info['bol_number'];
                $bols = $this->revoticketing_model->get_all_bols(1);
                include_once($this->plugin_path . 'views/agent/revo_admin_bols_view.php');
                echo $output;
            }

        }

        if ($_REQUEST['agent_admin_page'] == 'download_documents') {
            $folder = $_REQUEST['ticket_folder'];
            $documents =  $this->revoticketing_model->get_documents($this->plugin_path,  $folder);
           
            //Archive name
           
            //Download Files path
            
            $file_path = dirname(__FILE__) .  '/uploads/' .  $folder .'/';
            
            $archive_file_name=$file_path.$folder.'.zip';

            $archive_file_name_url = plugins_url( '/uploads/'.$folder.'/'. $folder . '.zip',__FILE__ );

            $this->zipFilesAndDownload( $documents ,$archive_file_name,$file_path);

            $response = array( 'success' => true, 'errors' => array(), 'zip_url' =>  $archive_file_name_url );  
            echo json_encode($response);
            die();
            
        }

        

        if ($_REQUEST['agent_admin_page'] == 'bol_add_submit') {
            $bol_info = $this->revoticketing_model->bol_action($_REQUEST);
            if ( $bol_info['success'] ) {
                $add_bol_success = true;

                if ($bol_info['action'] == 'edit_bol') {
                   $bol_message = "has been updated successfully.";
                } else {
                   $bol_message = "has been added successfully.";
                }

                $bol_number = $bol_info['bol_number'];
                $bols = $this->revoticketing_model->get_all_bols(1);
                include_once($this->plugin_path . 'views/agent/revo_admin_bols_view.php');
                echo $output;

            } else {
                echo json_encode( $bol_info );
                die();
            }
            
        }

        if ($_REQUEST['agent_admin_page'] == 'view_client') {
            if ($_REQUEST['client_id']) {
               $client = get_user_by( 'id',  $_REQUEST['client_id'] );
             
            }
            include_once($this->plugin_path . 'views/agent/revo_admin_view_client_view.php');
            echo $output;
        }



        /* CLIENT */

        if ( $_REQUEST['client_page'] == 'pills-home-tab') {
            $paged = $_REQUEST['paged'];
            $client_tickets = $this->revoticketing_model->get_client_tickets($this->current_user->data->ID, $paged);
            include_once($this->plugin_path . 'views/revo_client_tickets_view.php');
            echo $output;
        }

        if ( $_REQUEST['client_page'] == 'search_bol') {
            $bol_number = $_REQUEST['bol_number'];
            $bol = $this->revoticketing_model->get_all_bol_by(0, $bol_number);
            include_once($this->plugin_path . 'views/agent/revo_admin_view_bol_view.php');
            echo $output;
        }

        die();
    }

    public function zipFilesAndDownload($file_names,$archive_file_name,$file_path)
    {
           
        $zip = new ZipArchive();
        //create the file and throw the error if unsuccessful
        if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
           echo ("cannot open <$archive_file_name>\n");
        }
        //add each files of $file_name array to archive
       
        foreach($file_names as $files)
        {
           $zip->addFile($file_path.$files['filename'], $files['filename']);
        }
        $zip->close();
        
    }


    public function agent_client_action() {
        $client_id = $_REQUEST['client_id'];
        $approved = false;
        if ($_REQUEST['action_btn'] =='approve') {
            $approved = true;
            update_user_meta(  $client_id , 'client_approved', 1 );
        } else {
            update_user_meta(  $client_id , 'client_approved', 0 );
        }

        if ( $approved ) {
            $notification_args = array(
                'update_name' => 'account approved',
                'send_to' => array(
                    'client' => array(
                        'id' =>  $client_id,
                        'email_template' => 'client_account_approved',
                        'email_subject' => "Your account has been approved."
                    )
                )
            );
            $this->send_notification($notification_args);
        }
         $response = array( 'success' => true, 'errors' => array(), 'client_id' =>  $client_id );  
         echo json_encode($response);
        
         die();
    }

    public function upload_xml_file() {
        if(!empty($_FILES['file']))
        {
             $path = $this->plugin_path . 'uploads/xml/';
             $path = $path . basename( $_FILES['file']['name']);

            if(move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                 //PARSE XML FILE */
                $xmlfile = file_get_contents($path); 
                $new = simplexml_load_string($xmlfile); 
                echo $con = json_encode($new); 
               // $newArr = json_decode($con, true); 
                //print_r($newArr); 
                
            } else{
                echo "There was an error uploading the file, please try again!";
            }

           

        }

        die();
    }

    public function get_comments() {
        $post_id = $_REQUEST['post_id'];
        $comments = get_comments('post_id='. $post_id);        
        
        include_once($this->plugin_path . 'views/revo_comments_view.php');
        echo $output;
        die();
    }

    public function add_comment() {
       $response = $this->revoticketing_model->add_comment($_REQUEST);
       echo json_encode($response); 
       die();
       
    }

    function revo_convert_to_time_ago( $orig_time ) {
      //  $orig_time = strtotime( $orig_time ); 
        return human_time_diff( $orig_time, current_time( 'timestamp', 0 ) ).' '.__( 'ago' );
    }

    public function get_ticket_updates($new_value) {
        //print_r($new_value);
        $ticket_status_args = array();
        $ticket_post_id = $new_value['ticket_post_id'];
       
        /* TICKET STATUS AND TICKET DEPARTMENT */
        $ticket_status_old = get_post_meta($ticket_post_id,'ticket_status', true);
        $ticket_department_old = get_post_meta($ticket_post_id,'ticket_department', true);
        $ticket_number = get_post_meta($ticket_post_id,'ticket_number', true);
        $client_id = get_post_meta($ticket_post_id,'client_id',true);
        $ticket_department = $new_value['ticket_department'];
       /* if (isset($new_value['ticket_status'])) {
            if ($new_value['ticket_status'] !=  $ticket_status_old ) {
                $ticket_status_args = array(
                    'update_name' => 'status_update',
                    'ticket_info' => array(
                        'ticket_number' => $ticket_post_id,
                        'ticket_status' => $new_value['ticket_status']
                    ),
                    'send_to' => array(
                        'client' => array(
                            'id' => $client_id,
                            'email_template' => 'client_ticket_status_changed',
                            'email_subject' => 'Ticket status changed'
                        )
                    ),
                    'add_to_comment' => array('comment' => 'Ticket status changed to ' . $new_value['ticket_status'] . '.', 'comment_post_ID' => $ticket_post_id)

                );
            
            $this->send_ticket_updates($ticket_status_args);
           }
        } */

        if (isset($new_value['ticket_department'])) {
            if ($new_value['ticket_department'] !=  $ticket_department_old ) {
                update_post_meta($ticket_post_id, "ticket_status", 'approved');
                update_post_meta($ticket_post_id, "ticket_department_prev", $ticket_department_old);
                $ticket_status_args = array(
                    'update_name' => 'new department',
                    'ticket_info' => array(
                        'ticket_number' => $ticket_number,
                        'ticket_department' =>  $ticket_department
                    ),
                    'send_to' => array(
                        'client' => array(
                            'id' =>  $client_id,
                            'email_template' => 'client_ticket_move_department',
                            'email_subject' => 'Ticket submitted to next department'
                        ),
                        'agent' => array(
                            'email_template' => 'agent_ticket_move_department',
                            'email_subject' => 'Ticket submitted to next department'
                        )
                    ),
                    'add_to_comment' => array('comment' => 'Ticket has been assigned to '. str_replace("_", " ", $new_value['ticket_department']) .' department.', 'comment_post_ID' => $ticket_post_id)

                );
            
            $this->send_ticket_updates($ticket_status_args);   
            }
        }

         /* DOCUMENTS STATUS */
         if (isset($new_value['ticket_documents'])) {
            $ticket_documents_old = get_post_meta($ticket_post_id,'ticket_documents', true);
            $document_updates = array();
            $n = 0;
            foreach ( $new_value['ticket_documents'] as $ticket_document_k => $ticket_document_v ) {
                /* CHECK STATUS */
               
                    if ($ticket_document_v['file_status'] != $ticket_documents_old[$ticket_document_k]['file_status']) {
                    /* AGENT */
                    
                        if ($ticket_document_v['file_status'] != 'replaced' && $ticket_document_v['file_status'] != '') {
                            $document_updates[$n]['text']= $ticket_document_v['filename'] . ' status has been changed to ' . $new_value['ticket_documents'][$ticket_document_k]['file_status']; 
                            $document_updates[$n]['remarks'] = $ticket_document_v['file_remarks'];
                        }
                        /* CLIENT - REPLACED FILE*/
                        if ($ticket_document_v ['file_status'] == 'replaced') {
                            $document_updates[$n]['text']= $ticket_document_v['filetobedeleted'] . ' has been replaced by ' . $ticket_document_v['filename']; 
                            $document_updates[$n]['remarks'] = $ticket_document_v['filetobedeleted'] . ' has been replaced with ' .$ticket_document_v['filename'];
                        } 

                    }

                     /* CLIENT NEW FILE */
                     if ($ticket_document_v['file_status'] == 'new') {
                        $document_updates[$n]['text']= $ticket_document_v['filename'] . ' new upload ' ; 
                        $document_updates[$n]['remarks'] = ' New ';
                    } 
                

                $n++;
            } 
            
            if (count($document_updates) > 0) {
                
                $ticket_status_args = array(
                    'update_name' => 'document updates',
                    'ticket_info' => array(
                        'ticket_number' => $ticket_number,
                        'ticket_department' =>  $ticket_department_old,
                        'ticket_documents' => $document_updates
                    ),
                    'send_to' => array(
                       'client' => array(
                            'id' =>  $client_id,
                            'email_template' => 'client_ticket_document_status',
                            'email_subject' => 'Ticket document status changed'
                        ),
                        'agent' => array(
                            'email_template' => 'agent_ticket_document_status',
                            'email_subject' => 'Ticket document status changed'
                        )
                    ),
                    //'add_to_comment' => array('comment' => 'Ticket has been assigned to '. $new_value['ticket_department'] .' department.', 'comment_post_ID' => $ticket_post_id)

                );
            
            $this->send_ticket_updates($ticket_status_args);   

            }
            
         }

    
    }

    public function send_notification($send_args) {
        //CLIENT
        if (isset($send_args['send_to']['client'])) {
            $client_id = $send_args['send_to']['client']['id'];
            if ( $client_id  != 0 ) {
                    $client = get_user_by( 'id', $client_id );
                    $client_name = $client->data->display_name;
                   
                    include_once($this->plugin_path . 'views/email_templates/'. $send_args['send_to']['client']['email_template'] .'.php');
                    $email_data = array('email_to' =>  $client->data->user_email, 'email_subject' =>  $send_args['send_to']['client']['email_subject'], 'email_body' => $output);
                    $this->revoticketing_model->send_email($email_data);
            }
            
        }

        //AGENT
        if (isset($send_args['send_to']['agent'])) {
            //GET AGENTS
            $department_agents = $this->revoticketing_model->get_agents('admin');
             //SEND EMAIL
             if (count( $department_agents ) > 0) {
                foreach ($department_agents as $department_agent){
                    $agent_name = $department_agent->data->display_name;
                    include($this->plugin_path . 'views/email_templates/'. $send_args['send_to']['agent']['email_template'] .'.php');

                    $email_data = array('email_to' =>  $department_agent->data->user_email, 'email_subject' => $send_args['send_to']['agent']['email_subject'], 'email_body' => $output);
                    $this->revoticketing_model->send_email($email_data);
                }
               
            }

        }

    }

    public function send_ticket_updates($ticket_status_args) {
        //AGENT
        $new_department = $ticket_status_args['ticket_info']['ticket_department'];
        $ticket_number= $ticket_status_args['ticket_info']['ticket_number'];
        $ticket_type = $ticket_status_args['ticket_info']['ticket_type'];
        $bol_number = $ticket_status_args['ticket_info']['bol_number'];
        $ticket_number = $ticket_status_args['ticket_info']['ticket_number'];
        if (isset($ticket_status_args['ticket_info']['ticket_documents'])) {
            $document_updates = $ticket_status_args['ticket_info']['ticket_documents'];
        }

        
        //CLIENT
        if (isset($ticket_status_args['send_to']['client'])) {
            $client_id = $ticket_status_args['send_to']['client']['id'];
            if ( $client_id  != 0 ) {
                    $client = get_user_by( 'id', $client_id );
                    $client_name = $client->data->display_name;
                    if (isset($ticket_status_args['ticket_info']['ticket_status'])) {
                        $ticket_status = $ticket_status_args['ticket_info']['ticket_status'];
                    }
                    include_once($this->plugin_path . 'views/email_templates/'. $ticket_status_args['send_to']['client']['email_template'] .'.php');
                    $email_data = array('email_to' =>  $client->data->user_email, 'email_subject' => $ticket_status_args['send_to']['client']['email_subject'], 'email_body' => $output);
                    $this->revoticketing_model->send_email($email_data);
            }
        }

        if (isset($ticket_status_args['send_to']['agent'])) {
            //GET AGENTS
           
             $department_agents = $this->revoticketing_model->get_agents($new_department);
          
             //SEND EMAIL
             if (count( $department_agents ) > 0) {
                foreach ($department_agents as $department_agent){
                    $agent_name = $department_agent->data->display_name;
                    include($this->plugin_path . 'views/email_templates/'. $ticket_status_args['send_to']['agent']['email_template'] .'.php');

                    $email_data = array('email_to' =>  $department_agent->data->user_email, 'email_subject' => $ticket_status_args['send_to']['agent']['email_subject'], 'email_body' => $output);
                    $this->revoticketing_model->send_email($email_data);
                }
               
            }

        }

        //ADD COMMENT
        if (isset($ticket_status_args['add_to_comment'])) {
            $message_data = $ticket_status_args['add_to_comment'];
            $response = $this->revoticketing_model->add_comment($message_data);
        }
    }

    /* LOST PASSWORD PAGE */

    public function redirect_to_custom_lostpassword() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user();
                exit;
            }
     
            wp_redirect( home_url( 'member-password-lost' ) );
            exit;
        }
    }

    public function render_password_lost_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
     
        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.' );
        } else {
            // Retrieve possible errors from request parameters
            $attributes['errors'] = array();
            if ( isset( $_REQUEST['errors'] ) ) {
                $error_codes = explode( ',', $_REQUEST['errors'] );
            
                foreach ( $error_codes as $error_code ) {
                    $attributes['errors'][]= $this->get_error_message( $error_code );
                }
            }
            include_once($this->plugin_path . 'views/revo_client_forgot_password_view.php');
            return $output;
        }
    }

    public function do_password_lost() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $errors = retrieve_password();
            if ( is_wp_error( $errors ) ) {
                // Errors found
                $redirect_url = home_url( 'member-password-lost' );
                $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
            } else {
                // Email sent
                $redirect_url = home_url( 'online-processing' );
                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
            }
     
            wp_redirect( $redirect_url );
            exit;
        }
    }

    public function get_error_message($error_code) {
        switch ($error_code) {
            case 'empty_username':
                return __( 'You need to enter your email address to continue.', 'personalize-login' );
            
            case 'invalid_email':
            case 'invalidcombo':
                return __( 'There are no users registered with this email address.', 'personalize-login' );
            case 'expiredkey':
            case 'invalidkey':
                return __( 'The password reset link you used is not valid anymore.', 'personalize-login' );
                
            case 'password_reset_mismatch':
                return __( "The two passwords you entered don't match.", 'personalize-login' );
                    
            case 'password_reset_empty':
                return __( "Sorry, we don't accept empty passwords.", 'personalize-login' );
        } 
    }

    public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        // Create new message
        $msg  = __( 'Hello!', 'personalize-login' ) . "\r\n\r\n";
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";
        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'personalize-login' ) . "\r\n\r\n";
        $msg .= __( 'To reset your password, visit the following address:', 'personalize-login' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
        $msg .= __( 'Thanks!', 'personalize-login' ) . "\r\n";
     
        return $msg;
    }

    

    public function redirect_to_custom_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            // Verify key / login combo
            $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'online-processing?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'online-processing?login=invalidkey' ) );
                }
                exit;
            }
     
            $redirect_url = home_url( 'member-password-reset' );
            $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
            $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
     
            wp_redirect( $redirect_url );
            exit;
        }
    }

    public function render_password_reset_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
     
        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'personalize-login' );
        } else {
            if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
                $attributes['login'] = $_REQUEST['login'];
                $attributes['key'] = $_REQUEST['key'];
     
                // Error messages
                $errors = array();
                if ( isset( $_REQUEST['error'] ) ) {
                    $error_codes = explode( ',', $_REQUEST['error'] );
     
                    foreach ( $error_codes as $code ) {
                        $errors []= $this->get_error_message( $code );
                    }
                }
                $attributes['errors'] = $errors;
                
                include_once($this->plugin_path . 'views/revo_client_reset_password_view.php');
                return $output;
            } else {
                return __( 'Invalid password reset link.', 'personalize-login' );
            }
        }
    }


    public function do_password_reset() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $rp_key = $_REQUEST['rp_key'];
            $rp_login = $_REQUEST['rp_login'];
     
            $user = check_password_reset_key( $rp_key, $rp_login );
     
            if ( ! $user || is_wp_error( $user ) ) {
                if ( $user && $user->get_error_code() === 'expired_key' ) {
                    wp_redirect( home_url( 'online-processing-login?login=expiredkey' ) );
                } else {
                    wp_redirect( home_url( 'online-processing?login=invalidkey' ) );
                }
                exit;
            }
     
            if ( isset( $_POST['pass1'] ) ) {
                if ( $_POST['pass1'] != $_POST['pass2'] ) {
                    // Passwords don't match
                    $redirect_url = home_url( 'member-password-reset' );
     
                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
     
                    wp_redirect( $redirect_url );
                    exit;
                }
     
                if ( empty( $_POST['pass1'] ) ) {
                    // Password is empty
                    $redirect_url = home_url( 'member-password-reset' );
     
                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
     
                    wp_redirect( $redirect_url );
                    exit;
                }
     
                // Parameter checks OK, reset password
                reset_password( $user, $_POST['pass1'] );
                wp_redirect( home_url( 'online-processing?password=changed' ) );
            } else {
                echo "Invalid request.";
            }
     
            exit;
        }
    }

}