<div class="wrap">
    <h1>CPT Manager</h1>
    <?php settings_errors(); ?>
    
    <ul class="nav nav-tabs">
        <li class="<?php echo !isset($_POST['edit_post']) ? 'active':''; ?>"><a href="#tab-1">Your Custom Post Types</a></li>
        <li class="<?php echo isset($_POST['edit_post']) ? 'active':''; ?>">
            <a href="#tab-2">
            <?php echo isset($_POST['edit_post']) ? 'Edit':'Add'; ?> Custom Post Type
            </a>
        </li>
        <li><a href="#tab-3">Export</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?php echo !isset($_POST['edit_post']) ? 'active':''; ?>">
            <h2>Manage Your Custom Post Types</h2>
            <?php 

                $options = get_option('milton_plugin_cpt') ? get_option('milton_plugin_cpt'):array();
                
                echo '<table class="cpt-table">
                        <tr>
                            <th>ID</th>
                            <th>Singular Name</th>
                            <th>Plural Name</th>
                            <th class="text-center">Public</th>
                            <th class="text-center">Archive</th>
                            <th class="text-center">Actions</th>
                        </tr>';
                    foreach ($options as $option) { 

                        $public=isset($option['public']) ? 'True':'False';
                        $has_archive=isset($option['has_archive']) ? 'True':'False';

                        ?>
                       
                        <tr>
                            <td><?php echo esc_html($option['post_type']); ?></td>
                            <td><?php echo esc_html( $option['singular_name'] ); ?></td>
                            <td><?php echo esc_html($option['plural_name']); ?></td>
                            <td><?php echo esc_html($public); ?></td>
                            <td><?php echo esc_html($has_archive); ?></td>
                            <td>
                                <?php 
                                    /**Edit Custom Post Type */
                                ?>
                                <form action="" method="post" class="cpt_edit_form">
                                    <?php 

                                        printf('<input value="%s" name="edit_post" type="hidden"/>',esc_html($option['post_type'])); 


                                        submit_button('Edit','primary small','submit',false); 
                                    ?>
                                </form>
                            <?php 
                                /**Delete Custom Post Type */
                            ?>
                                <form action="options.php" method="post" class="cpt_delete_form">
                                    <?php 
                                        settings_fields('alecaddd_plugin_cpt_settings');
                                        printf('<input value="%s" name="remove" type="hidden"/>',esc_html($option['post_type'])); 


                                        submit_button('Delete','delete small','submit',false,array(
                                            'onclick' => 'return confirm("Are you sure to that this custom post type?");'
                                        )); 
                                    ?>
                                </form>
                            </td>
                        </tr>    
                        
                    <?php }
                echo '</table>'
                 ?>
                 
        </div>

        <div id="tab-2" class="tab-pane <?php echo isset($_POST['edit_post']) ? 'active':''; ?>">
            <form action="options.php" method="post">
                <?php 
                    settings_fields('alecaddd_plugin_cpt_settings');
                    do_settings_sections('milton_cpt');
                    submit_button(); 
                ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h2>Export Your Custom Post Type</h2>
        </div>
    </div>
</div>