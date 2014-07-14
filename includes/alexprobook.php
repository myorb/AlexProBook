<?php

class AlexProBook {

    protected $version = 1.0;
    protected $option_name = 'alx_pro_book_version';
        
    public function getTableName(){
        return $GLOBALS['wpdb']->prefix.'alexprobook';
    }

    public function __construct() {

        add_action('admin_menu',array($this, 'menu'));

        // Listen for the activate event
//        register_activation_hook(ALEX_PRO_FILE, array($this, 'activate'));

        // Deactivation plugin
        register_deactivation_hook(ALEX_PRO_FILE, array($this, 'deactivate'));
    }

    public function deactivate() {
        delete_option($this->option_name);
    }

    function menu() {
         add_menu_page('Pro Book', 'Alex Pro Book', 4, 'alexprobook/alexprobook.php', array($this,'main'));
    }


    function main() {

        if ($_POST['new']) {
            $this->create();
        } else if ($_GET['action']=='delete') {
            $this->delite();
        } else if ($_GET['action']=='edit') {
            $this->edit();
        } else {
            If (get_option($this->option_name)!=$this->version) {
                $this->install();
            }
            $this->view();
        }
    }


    function install() {
        $sql = "CREATE TABLE IF NOT EXISTS " . $this->getTableName() . " (
            id INT(9) NOT NULL AUTO_INCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            cellphone TEXT NOT NULL,
            PRIMARY KEY  (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
        dbDelta($sql);
        update_option($this->option_name, $this->version);
    }

    //Create Update Delite functions
    function create(){
        global $wpdb;
        $wpdb->insert($this->getTableName(),
            array(
                'first_name'=> $wpdb->escape($_POST['first_name']),
                'last_name' => $wpdb->escape($_POST['last_name']),
                'cellphone' => $wpdb->escape($_POST['cellphone'])
            )
        );
        echo '<div id="message" class="updated fade">
                <p><strong>Row was added.</strong> <a href="admin.php?page=alexprobook/alexprobook.php">Continue &raquo;</a></p>
              </div>';
    
    }

    function delite(){
        global $wpdb;

        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id='".$wpdb->escape($_GET['id'])."'";
        $row = $wpdb->get_row($sql);
        if ($_GET['confirm']=='yes') {
            $wpdb->query("DELETE FROM ".$this->getTableName()." WHERE id='".$wpdb->escape($_GET['id'])."'");
            echo '<div id="message" class="updated fade">
                        <p><strong>The row has been deleted.</strong>
                        <a href="admin.php?page=alexprobook/alexprobook.php">Continue &raquo;<a/></p>
                     </div>';
        } else {
            echo "<div class='wrap'>
                  <p><h2>Are you sure you want to delete this row?</h1></p>
                  <p>
                    <a href='admin.php?page=alexprobook/alexprobook.php&action=delete&id=".$row->id."&confirm=yes'>[Yes]</a>
                    <a href='admin.php?page=alexprobook/alexprobook.php'>[No]</a>
                  </p>
                  </div>";
        }
    }
    public function edit(){
        global $wpdb;

        $sql = "SELECT * FROM ".$this->getTableName()." WHERE id='".$wpdb->escape($_GET['id'])."'";
        $row = $wpdb->get_row($sql);

        if ($_POST['save']) {

            $wpdb->update(
                $this->getTableName(),
                array(
                    'first_name' =>$wpdb->escape($_POST['first_name']),	// string
                    'last_name' => $wpdb->escape($_POST['last_name']),	// integer (number)
                    'cellphone' => $wpdb->escape($_POST['cellphone'])	// integer (number)
                ),
                array( 'id' => $wpdb->escape($_GET['id']) )
            );
            echo '<div id="message" class="updated fade"><p><strong>The row has been updated.</strong><a href="admin.php?page=alexprobook/alexprobook.php">Continue &raquo;<a/></p></div>';
        } else { 
            echo '<div class="wrap">
                            <h2><a name="new"></a>Edit Address</h2>';
            echo '<form action="admin.php?page=alexprobook/alexprobook.php&action=edit&id='. $row->id; .'method="post">';
            echo $this->getForm($row); 
            echo '<input type="submit" name="save"/>
                    </form>
                </div>';
        }

    }

    public function view(){
        global $wpdb;
        echo '<div class="wrap">
        
                    <table class="wp-list-table widefat fixed posts">
                        <thead>
                            <tr>
                                <th>First name</th><th>Last name</th><th>Phone</th><th>Options</th>
                            </tr>
                        </thead>
                        <tbody id="the-list">'
                    $sql = "SELECT * FROM ".$this->getTableName()." ORDER BY last_name, first_name";
                    $results = $wpdb->get_results($sql);
                    foreach ($results as $row) {
                        echo "<tr><td>".$row->first_name."</td>
                        <td>".$row->last_name."</td>
                        <td>".$row->cellphone."</td>
                        <td><a href='admin.php?page=alexprobook/alexprobook.php&action=edit&id=".$row->id."'>[Edit]</a>
                        <a href='admin.php?page=alexprobook/alexprobook.php&action=delete&id=".$row->id."'>[Delete]</a></td>
                    </tr>";
                    }

            echo '</tbody></table>;
                        <h2>Add adress</h2>
                        <form action="admin.php?page=alexprobook/alexprobook.php" method="post">';
            echo $this->getForm();
            echo '<input type="submit"  name="new" />
                        </form>
                    </div>';
    }

    function getForm($data='null') {
        return '<div style="width:50%; >
                    <div class="input" style="width:50%">
                        <label for="first_name">First name:</label>
                        <input type="text" name="first_name" value="'.$data->first_name.'" />
                    </div>
                    <div class="input" style="width:49%">
                        <label for="last_name">Last name:</label>
                        <input type="text" name="last_name" value="'.$data->last_name.'" />
                    </div>
                    <div class="input" style="width:49%">
                        <label for="cellphone">Cell phone:</label>
                        <input type="text" name="cellphone" value="'.$data->cellphone.'" />
                    </div>
                </div>';
    }

}
