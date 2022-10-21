<div class="wrap">
    <h1>Custom Taxonomy</h1>
    <?php settings_errors();?>

    <ul class="nav nav-tabs">
        <li class="<?php echo !isset( $_POST['edit_taxonomy'] ) ? 'active' : ''; ?>"><a href="#tab-1">Manage Your Custom Taxonomies</a></li>
        <li class="<?php echo isset( $_POST['edit_taxonomy'] ) ? 'active' : ''; ?>">
            <a href="#tab-2">
            <?php echo isset( $_POST['edit_taxonomy'] ) ? 'Edit' : 'Add'; ?>  Custom Taxonomies
            </a>
        </li>
        <li><a href="#tab-3">Export</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane <?php echo !isset( $_POST['edit_taxonomy'] ) ? 'active' : ''; ?>">
            <h2>Manage Your Custom Taxonomies</h2>
            <?php

        $options = get_option( 'milton_plugin_taxonomy' ) ? get_option( 'milton_plugin_taxonomy' ) : array();

        echo '<table class="cpt-table">
                <tr>
                    <th>ID</th>
                    <th>Singular Name</th>
                    <th class="text-center">Hierarchical</th>
                    <th class="text-center">Actions</th>
                </tr>';

        foreach ( $options as $option ) {

            $hierarchical = isset( $option['hierarchical'] ) ? 'True' : 'False';
            ?>

                <tr>
                    <td><?php echo esc_html( $option['taxonomy'] ); ?></td>
                    <td><?php echo esc_html( $option['singular_name'] ); ?></td>
                    <td><?php echo esc_html( $hierarchical ); ?></td>
                    <td>
                        <?php
            /**Edit Custom Post Type */
            ?>
            <form action="" method="post" class="cpt_edit_form">
                            <?php

            printf( '<input value="%s" name="edit_taxonomy" type="hidden"/>', esc_html( $option['taxonomy'] ) );

            submit_button( 'Edit', 'primary small', 'submit', false );
            ?>
            </form>
            <?php
            /**Delete Custom Post Type */
            ?>
            <form action="options.php" method="post" class="cpt_delete_form">
                <?php
                settings_fields( 'milton_plugin_tax_settings' );
                    printf( '<input value="%s" name="remove" type="hidden"/>', esc_html( $option['taxonomy'] ) );

                    submit_button( 'Delete', 'delete small', 'submit', false, array(
                        'onclick' => 'return confirm("Are you sure to that this custom taxonomy?");',
                    ) );
                    ?>
            </form>
            </td>
            </tr>

            <?php }

        echo '</table>'
        ?>
        </div>

        <div id="tab-2" class="tab-pane <?php echo isset( $_POST['edit_taxonomy'] ) ? 'active' : ''; ?>">
            <form action="options.php" method="post">
                <?php
                    settings_fields( 'milton_plugin_tax_settings' );
                    do_settings_sections( 'milton_taxonomy' );
                    submit_button();
                    ?>
            </form>
        </div>

        <div id="tab-3" class="tab-pane">
            <h2>Export Your Custom Post Type</h2>

        </div>
    </div>
</div>