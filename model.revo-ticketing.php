<?php 
class revoTicketingModel {
    public function __construct(){
          /* post per page */
          $this->post_per_page = 3;
    }


    public function create_ticket($form_data){
        $ticket_info_r = array();
        $title = 'Ticket';
        $count_tickets = wp_count_posts('ticket')->publish;
        $ticket_number_r = sprintf("%03d",$count_tickets+1);
       

        // Gather post data
        $ticket_post = array(
            'post_title' => $title,
            'post_content' => '/*AUTO GENERATED TICKET */ ',
            'post_status' => 'publish', 
            'post_type' => 'ticket',
            'post_author' => $current_user->ID
        );
    
        // Attempt to add post
        if($id = wp_insert_post($ticket_post)) {
            $current_user = wp_get_current_user();
            $all_client_meta = get_user_meta($current_user->ID);
            $client_name = $all_client_meta['first_name'][0] . ' ' . $all_client_meta['last_name'][0];
            $client_id = $current_user->ID;
            $ticket_type = $form_data["ticket_type"];
            $bol_number =  $form_data["bol_number"];
            //ADD TRANSACTION PREFIX TO TICKET NUMBER
            $ticket_prefix = '';
            if ($ticket_type == 'import') {
                $ticket_prefix = 'IMP';
            } else if ($ticket_type  == 'export') {
                $ticket_prefix = 'EXP';
            } else {
                $ticket_prefix = 'REF';
            }
            
            $ticket_number = $ticket_prefix.$ticket_number_r;

             // UPDATE META DATA
        
            update_post_meta($id, "client_id", $client_id);
            update_post_meta($id, "client_name", $client_name);
            update_post_meta($id, "ticket_number",  $ticket_number);
            update_post_meta($id, "ticket_status", 'for_verification');  
            update_post_meta($id, "ticket_type",  $ticket_type);
            update_post_meta($id, "ticket_department", 'accounting');   

            if (isset($form_data['ticket_documents'])) {
                update_post_meta($id, "ticket_documents",  $form_data['ticket_documents']);
            }
            
            if ($ticket_type == 'import') {
                update_post_meta($id, "bol_number", $bol_number);   
            } else {
                update_post_meta($id, "bol_number", 0);   
            }
            
             /* UPDATE TICKET NAME */
           // $ticket = array(
           //     'ID' => $id,
           //     'post_title' => $ticket_number
         //   );
            
          //  wp_update_post(  $ticket );
            
            $ticket_info_r = array(
                'post_id' => $id , 
                'ticket_number' =>  $ticket_number,
                'ticket_type' => $ticket_type,
                'bol_number' => $bol_number 
            );
        }

        return $ticket_info_r;
    }


    public function save_meta_data(){
        global $post;
        if ($post) {
            if ($post->post_type == 'ticket') {
                update_post_meta($post->ID, "ticket_number",$_POST["ticket_number"]);
                update_post_meta($post->ID, "ticket_status", $_POST["ticket_status"]);
                update_post_meta($post->ID, "ticket_type", $_POST["ticket_type"]);
                update_post_meta($post->ID, "ticket_department", $_POST["ticket_department"]);
                update_post_meta($post->ID, "bol_number", $_POST["bol_number"]);
            }
        }
    }

    public function agent_save_ticket($form_data) {
      
        $post_id = $form_data['ticket_post_id'];
        $ticket_number = get_post_meta($post_id, 'ticket_number', true);
        $ticket_documents = get_post_meta($post_id, 'ticket_documents', true);
        //AGENT DEPT AND TICKET STATUS
        $current_agent = wp_get_current_user();
       // update_post_meta($post_id, "ticket_status", $form_data['ticket_status']);
        update_post_meta($post_id, "ticket_department", $form_data['ticket_department']);
        update_post_meta($post_id, "agent_id",  $current_agent->ID);
        
        if (isset($form_data['ticket_documents'])) {
            foreach($form_data['ticket_documents'] as $ticket_document_k => $ticket_document){
                $ticket_documents[$ticket_document_k]['filename'] =  $ticket_document['filename'];
                if (isset($ticket_document['filetobedeleted'])) {
                    $remarks = $ticket_document['filetobedeleted'] . ' has been replaced with ' .$ticket_document['filename'];
                    $ticket_documents[$ticket_document_k]['file_status'] =  $ticket_document['file_status'];
                    $ticket_documents[$ticket_document_k]['file_remarks'] =  $ticket_document['file_remarks'];
                } else {
                    if ($ticket_documents[$ticket_document_k]['file_status'] == 'new') {
                        $ticket_documents[$ticket_document_k]['file_status'] =  'for_approval';
                    } else {
                        $ticket_documents[$ticket_document_k]['file_status'] =  $ticket_document['file_status'];
                    }
                    $ticket_documents[$ticket_document_k]['file_remarks'] =  $ticket_document['file_remarks'];
                }
                
                $this->delete_document( $ticket_number, $ticket_document['filetobedeleted'] );
            }

            if (count($ticket_documents) > 0) {
                update_post_meta($post_id, "ticket_documents",  $ticket_documents);
            }
        }
 
        //FREETIME 
        if (isset($form_data['ticket_freetime']))  {
            update_post_meta($post_id, 'ticket_freetime', $form_data['ticket_freetime']);
        }
        

       //UPDATE FILES STATUS
       if (count($form_data['file_status']) > 0 ) {
            $file_status = serialize($form_data['file_status']);
            update_post_meta($post_id, "file_status", $file_status);
       }

       //CARGO INFO 
       if (count($form_data['cargo_info']) > 0 ) {
           foreach ($form_data['cargo_info'] as $cargo_k => $cargo_v) {
                update_post_meta($post_id, $cargo_k, $cargo_v);
           } 
       }

       //WEBCRO
       $webcro = false;
       $webcrofile = false;
       if (isset($form_data['ticket_web_cro'])) {
            update_post_meta($post_id, "ticket_web_cro", $form_data['ticket_web_cro']);
            $webcro = true;
       }

       if (isset($form_data['ticket_filename_cro'])) {
            update_post_meta($post_id, "ticket_filename_cro", $form_data['ticket_filename_cro']);
            $webcrofile = true;
       }

       if ($webcro  && $webcrofile) {
            update_post_meta($post_id, "ticket_status", 'completed');
       }
       
    
       $update = array( 'ID' => $post_id );
       wp_update_post( $update );

       return array( 'success' => true, 'errors' => array(), 'post_id' =>  $post_id, 'ticket_number' => $ticket_number );
    }


    public function client_save_ticket($form_data) {
       
        $post_id = $form_data['ticket_post_id'];
        $ticket_number = get_post_meta($post_id, 'ticket_number', true);
        $ticket_documents = get_post_meta($post_id, 'ticket_documents', true);
       
        if (isset($form_data['ticket_documents'])) {
            foreach($form_data['ticket_documents'] as $ticket_document_k => $ticket_document){
                $ticket_documents[$ticket_document_k]['filename'] =  $ticket_document['filename'];
                if (isset($ticket_document['filetobedeleted'])) {
                    $remarks = $ticket_document['filetobedeleted'] . ' has been replaced with ' .$ticket_document['filename'];
                    $ticket_documents[$ticket_document_k]['file_status'] =  'replaced';
                    $ticket_documents[$ticket_document_k]['file_remarks'] =  $remarks;
                } else {
                    if ($ticket_documents[$ticket_document_k]['file_status'] == 'new') {
                        $ticket_documents[$ticket_document_k]['file_status'] =  'for_approval';
                    } else {
                        $ticket_documents[$ticket_document_k]['file_status'] =  $ticket_document['file_status'];
                    }
                    $ticket_documents[$ticket_document_k]['file_remarks'] =  $ticket_document['file_remarks'];
                }
                
                $this->delete_document( $ticket_number, $ticket_document['filetobedeleted'] );
            }

            if (count($ticket_documents) > 0) {
                update_post_meta($post_id, "ticket_documents",  $ticket_documents);
            }
        }

        //AGENT DEPT AND TICKET STATUS
        $current_agent = wp_get_current_user();
        $update = array( 'ID' => $post_id );
        wp_update_post( $update );

        return array( 'success' => true, 'errors' => array(), 'post_id' =>  $post_id, 'ticket_number' => $ticket_number );
    }

    public function delete_document($folder, $filename) {
        $dir = plugin_dir_path( __FILE__ ) . '/uploads/' . $folder .'/';
        if (file_exists($dir.$filename)) {
           unlink($dir.$filename);
        }
    }

    public function get_documents($plugin_path, $ticket_folder) {
        $dir =  $plugin_path . '/uploads/' . $ticket_folder .'/';
        if (file_exists($dir)) {
            $documents_r = array();
            $documents_f = array();
            $documents_r = scandir($dir);
            if (count($documents_r) > 0) {
                $n= 0;
                clearstatcache();
                foreach ($documents_r as $document) {
                    if ( $document != "." &&  $document != "..") {
                        $ext = pathinfo(plugins_url( '/uploads/'.$ticket_folder .'/'. $document,__FILE__ ), PATHINFO_EXTENSION);
                        if ($ext !== 'zip') {
                            $documents_f[$n] = array( 
                                'filename' => $document,
                                'file_url' => plugins_url( '/uploads/'.$ticket_folder .'/'. $document,__FILE__ ),
                                'date_uploaded' => date("M d Y H:i:s", filemtime( $dir.$document))
                            );
                        }
                    }
                    $n++;
                }
            }
           
            return $documents_f;
        } 
    }

    public function get_processing_tickets($client_id){
        $tickets = array();
        $args2 = array(
            'post_type'	=> 'ticket',
            'post_author' => $client_id,
            'post_status'	=> 'publish',
        	
        );
        
        // Query the posts
        $tickets  = query_posts( $args2 );
        wp_reset_query();

        if (count($tickets) > 0) {
            return $tickets;
        }

        return  $tickets;    
    }

    public function get_client_tickets($client_id=0, $paged=1){
        $tickets = array();
        
        $args = array(
            'post_type'	=> 'ticket',
        	'meta_query' => array(
                 array(
                    'key'     => 'client_id',
        	        'value'   =>  $client_id,
                )
            )
        );

        $tickets_r  = query_posts( $args );
        $total_posts = count( $tickets_r );

        $pagination_args = array(
            'total_posts_count' => $total_posts , 
            'posts_per_page' => $this->post_per_page,
            'paged' => $paged
        );
       
        $paginate = $this->generate_pagination($pagination_args);


        $args2 = array(
            'posts_per_page' => $this->post_per_page,
            'paged' => $paged,
        	'post_type'	=> 'ticket',
        	'meta_query' => array(
                 array(
                    'key'     => 'client_id',
        	        'value'   =>  $client_id,
                )
            )
        );

       
        // Query the posts
        $tickets['data']  = query_posts( $args2 );
        $tickets['pagination'] =  $paginate;
       
        wp_reset_query();

        if (count($tickets) > 0) {
            return $tickets;
        }

        return  $tickets;    
    }

    public function login_user($form_data){
        global $wpdb;
        $user_data = array( 'success' => false, 'errors' => array());
        $errors = array();
        
        /* CHECK IF CLIENT HAS BEEN APPROVED */
        $username = $wpdb->escape($form_data['log']);
        $password = $wpdb->escape($form_data['pwd']);
        $remember = $wpdb->escape($form_data['rememberme']);
        $client  = wp_authenticate($username, $password);
        if ( is_wp_error($client) ) {
            $error = $client->get_error_message();
            $errors['message'] = $error;
            $user_data = array( 'success' => false, 'errors' => $errors );
            return ($user_data);   
        }

        $clientStatus = get_user_meta($client->ID, 'client_approved', true);
        if ($clientStatus == 0) {
            $errors['message'] = 'Your account has not been activated yet.';
            $user_data = array( 'success' => false, 'errors' => $errors );
        } else {
            $creds = array();
            $creds['user_login'] = $username;
            $creds['user_password'] = $password;
            $creds['remember'] = $remember;
            $user_signon = wp_signon( $creds, false );

            $user_data = array( 'success' => true, 'errors' => $errors );   
        }    

        return ($user_data);   
    }

    public function register_user($form_data){
        global $wpdb;

        $user_data = array( 'success' => true, 'errors' => array());
        $errors = array();
    
        if ( empty( $form_data['first_name'] ) || ! empty( $form_data['first_name'] ) && trim( $form_data['first_name'] ) == '' ) {
            $errors['first_name'] = 'You must include a first name';
        }

        if ( empty( $form_data['last_name'] ) || ! empty( $form_data['last_name'] ) && trim( $form_data['last_name'] ) == '' ) {
            $errors['last_name'] = 'You must include a last name';
        }

        $email = $wpdb->escape($form_data['email']);  
        
        if (!is_email($email)) {  
            $errors['email'] = "Please enter a valid email";  
        } elseif (email_exists($email)) {  
            $errors['email'] = "This email address is already in use";  
        }  
        
        if (0 === preg_match("/.{6,}/", $form_data['password'])) {   
            $errors['password'] = "Password must be at least six characters";  
        }  
       
        if (0 !== strcmp($form_data['password'], $form_data['password_confirmation'])) {
            $errors['password_confirmation'] = "Passwords do not match";  
        }   

        if ( empty( $form_data['company_name'] ) || ! empty( $form_data['company_name'] ) && trim( $form_data['company_name'] ) == '' ) {
            $errors['company_name'] = 'You must include a company name';
        }
      
        if ( empty( $form_data['address'] ) || ! empty( $form_data['address'] ) && trim( $form_data['address'] ) == '' ) {
            $errors['address'] = 'You must include an address';
        }

        if ( empty( $form_data['contact_number'] ) || ! empty( $form_data['contact_number'] ) && trim( $form_data['contact_number'] ) == '' ) {
            $errors['contact_number'] = 'You must include a contact number';
        }
  
        if (0 === count($errors)) {  
            
            $password = $form_data['password'];  
            $username = $email;
            $new_user_id = wp_create_user($username, $password, $email);  
            $display_name = trim( $form_data['first_name']) . ' ' .  trim( $form_data['last_name'] );
            wp_update_user( array( 'ID' => $new_user_id, 'display_name' => $display_name ) );
            $user_data = array( 'success' => true, 'errors' => $errors);
            
            /* UPDATE USER CUSTOM FIELDS */
            update_user_meta(  $new_user_id, 'first_name', trim( $form_data['first_name'] ) );
            update_user_meta(  $new_user_id, 'last_name', trim( $form_data['last_name'] ) );
            update_user_meta(  $new_user_id, 'company_name', trim( $form_data['company_name'] ) );
            update_user_meta(  $new_user_id, 'address', trim( $form_data['address'] ) );
            update_user_meta(  $new_user_id, 'contact_number', trim( $form_data['contact_number'] ) ); 

            /* SET USER ROLE */
            $user_info = get_userdata($new_user_id);
            $user_info ->set_role( 'custom_client' );

            $user_data = array( 'success' => true, 'errors' => $errors, 'data' => array('client_id' => $new_user_id));
            

        }  else {
            $user_data = array( 'success' => false, 'errors' => $errors);
        }  

        return $user_data; 

    }

    


    /* AGENT */

    public function get_all_client_tickets($agent_dept=admin, $paged=1){
       
        $tickets = array();
       
        $args = array(
            'post_type'	=> 'ticket'
        );

        if ($agent_dept != 'admin') {
            $args['meta_query'] = array(
                array(
                   'key'     => 'ticket_department',
                   'value'   =>  $agent_dept,
               )
            );
        }

        $tickets_r  = query_posts( $args );


        $total_posts = count( $tickets_r );

        $pagination_args = array(
            'total_posts_count' => $total_posts , 
            'posts_per_page' => $this->post_per_page,
            'paged' => $paged
        );
       
        $paginate = $this->generate_pagination($pagination_args);
        
        $args2 = array(
            'post_type'	=> 'ticket',
            'posts_per_page' => $this->post_per_page,
            'paged' => $paged,
        );

        if ($agent_dept != 'admin') {
            $args2['meta_query'] = array(
                array(
                   'key'     => 'ticket_department',
                   'value'   =>  $agent_dept,
               )
            );
        }
        // Query the posts
        // Query the posts
        $tickets['data']  = query_posts( $args2 );
        $tickets['pagination'] =  $paginate;
        

       
        wp_reset_query();

        if (count($tickets) > 0) {
            return $tickets;
        }

        return  $tickets;    
    }

    /* GET SINGLE TICKET */

    public function get_client_ticket($form_data){
        $tickets = array();
        $args2 = array(
            'post_type'	=> 'ticket',
            'p' => $form_data["ticket_id"]
        );

        // Query the posts
        $ticket  = query_posts( $args2 );
       
        wp_reset_query();

        if (count($ticket) > 0) {
            return $ticket;
        }

        return  $ticket;    
    }


    public function get_all_clients($form_data){
        $admin_page = $form_data['agent_admin_page'];
        $clients = array();

        $args = array(
            'role'    => 'custom_client',
            'orderby' => 'user_nicename',
            'order'   => 'ASC'
        );

        if ($form_data['agent_admin_page'] == 'for_approval_clients') {
            $args['meta_query'] = array(
             
                array(
                    'key'     => 'client_approved',
                    'value'   =>  null,
                )
            );

        }

        $clients = get_users( $args );

        wp_reset_query();

        return $clients;    
    }

    public function get_agents($department = ''){

        $agents = array();
        $args = array(
            'role'    => 'custom_agent',
            'orderby' => 'user_nicename',
            'order'   => 'ASC'
        );

        

       /* if ($department != '') {
            $args['meta_query'] = array(
                array(
                    'key' => 'agent_departments',
                    'value' =>  $department,
                    'compare' => '=='
                )
            );
       } */

     
        $agents = get_users( $args );

        wp_reset_query();

        return $agents;    
    }

    public function add_comment($form_data){
        $current_user = wp_get_current_user();
        $user_login = $current_user->data->display_name;
        $user_id = $current_user->data->ID;
        $user_email = $current_user->data->user_email;
        $comment = $form_data['comment'];
        $post_id = $form_data['comment_post_ID'];
        $time = current_time('mysql');

        $data = array(
            'comment_post_ID' => $post_id,
            'comment_author' => $user_login,
            'comment_author_email' => $user_email,
            'comment_author_url' => '',
            'comment_content' => $comment,
            'user_id' => $user_id, 
            'comment_date' => $time,
            'comment_approved' => 1,
            'comment_type' => 'ticket'
        );

        if (wp_insert_comment($data)) {
           return array( 'success' => true, 'errors' => array(), 'post_id' =>  $post_id  );
        }
   }

    /* SEND EMAIL */
    public function send_email($email_data){
        $to = $email_data['email_to'];
        $subject = $email_data['email_subject'];
        $body = $email_data['email_body'];
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers);
    }

    public function generate_pagination($args) {
        $count = $args['total_posts_count'];
        $per_page = $args['posts_per_page'];
        $pages = 0;
        $paged = $args['paged'];
        $prev = '';
        $next ='';

        if ($count > $per_page) {
            $pages = ceil($count / $per_page);
            if ($paged > 1) {
                $prev = $paged - 1;
            }

            if ($paged <  $pages) {
                $next = $paged + 1;
            }
        }

        return array('pages' => $pages, 'paged' =>  $paged, 'prev' => $prev, 'next' => $next );
    }

     /* BOLS */

     public function get_all_bol_by($id=0, $bol_number=''){
        $bol_r = array();
        if ($id != 0 ) {
            $args = array(
                'post_type'	=> 'shipping_bol',
                'post_status' => 'publish',
                'p' => $id,
                
            );

            $bol_r  = query_posts( $args );
         
        }
       

        if ($bol_number != '' ) {
            $args = array(
                'post_type'	=> 'shipping_bol',
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                       'key'     => 'bol_number',
                       'value'   =>  $bol_number,
                   )
               )
            );

            $bol_r  = query_posts( $args );
         
        }

        return $bol_r;
     }

     public function get_all_bols($paged=1){
        $bols = array();
      
        $args = array(
            'post_type'	=> 'shipping_bol',
            'post_status' => 'publish'
        );
       
        $bols_r  = query_posts( $args );
       
        $total_posts = count( $bols_r );

        $pagination_args = array(
            'total_posts_count' => $total_posts , 
            'posts_per_page' => $this->post_per_page,
            'paged' => $paged
        );
       
        $paginate = $this->generate_pagination($pagination_args);
        
        $args2 = array(
            'post_type'	=> 'shipping_bol',
            'post_status' => 'publish',
            'posts_per_page' => $this->post_per_page,
            'paged' => $paged,
        );

        // Query the posts
        $bols['data']  = query_posts( $args2 );
        
        $bols['pagination'] =  $paginate;
       
        wp_reset_query();

        if (count($bols) > 0) {
            return $bols;
        }
       
        return  $bols;    
    }

    public function delete_bol($bol_id) {
           $bol_number =  get_post_meta($bol_id,'bol_number', true);
           if (wp_update_post(array(
            'ID'    =>  $bol_id,
            'post_status'   =>  'draft'
            ))) {
                $bol_info = array('action' => 'delete_bol' , 'success' => true, 'bol_number' => $bol_number );

                return $bol_info;    
            }

    }

    public function bol_action($form_data) {
        $bol_info = array();
        $current_user = wp_get_current_user();
        $author_id = $current_user->ID;
        if ($form_data['agent_admin_page'] == 'bol_add_submit') {
            $cargo_info_r = $form_data['cargo_info'];
            $bol_number = $form_data['cargo_info']['bol_number']; 
            $title = 'BoL#'. $bol_number;

            //CHECK IF BOL AREADY EXISTS 
            if ($form_data['bol_id'] == 0) {
                $bol_result = $this->get_all_bol_by(0,$bol_number);
                if (count($bol_result ) > 0) {
                    $bol_info = array('action' => 'create_bol' , 'success' => false, 'bol_number' => $bol_number, 'error' => 'B/L already exists.' );
                    return $bol_info;    
                }
                
            }
          
            // GATHER POST DATA
            $post = array(
                'post_title' => $title,
                'post_content' => ' Bol entry',
                'post_status' => 'publish', 
                'post_type' => 'shipping_bol',
                'post_author' => $author_id
            );
            
            /* EDIT BOL */
            if (isset($form_data['bol_id']) && $form_data['bol_id'] != 0) {
                $bol_id = $form_data['bol_id'];
                
                if ( count($cargo_info_r) > 0 ) {
                    // UPDATE META DATA
                    foreach( $cargo_info_r as $cargo_info_k =>  $cargo_info_v) {
                       update_post_meta($bol_id, $cargo_info_k, $cargo_info_v);
                    }
                }

                $update = array( 'ID' => $bol_id );
                wp_update_post( $update );
               
                $bol_info = array('action' => 'edit_bol' , 'success' => true, 'bol_number' => $bol_number );

            } else{

                // Attempt to add post
                if($id = wp_insert_post($post)) {
                    if ( count($cargo_info_r) > 0 ) {
                        // UPDATE META DATA
                        foreach( $cargo_info_r as $cargo_info_k =>  $cargo_info_v) {
                        update_post_meta($id, $cargo_info_k, $cargo_info_v);
                        }
                    }
                
                    $bol_info = array('action' => 'create_bol' , 'success' => true, 'bol_number' => $bol_number );
                }

            }
    
            return $bol_info;    
        }
    }


}
