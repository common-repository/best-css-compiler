<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/baonguyenyam/wp-best-css-compiler
 * @since      1.0.0
 *
 * @package    Best_Css_Compiler
 * @subpackage Best_Css_Compiler/admin/partials
 */
?>
<div class="wrap csscompiler">
    <div id="wp-content-editor-tools" class="wp-heading">
        <div class="alignleft">
            <h1 style="padding: 0"><?php echo esc_html__('SCSS/CSS Code', 'best-css-compiler' )?></h1>
        </div>
        <div class="alignright">
            <a href="admin.php?page=best-css-compiler&action=add" class="button button-primary"><span><?php echo esc_html__('Add New', 'best-css-compiler' );?></span> <span class="dashicons dashicons-plus"></span></a>
        </div>
        <div class="clear"></div>
        <hr>
    </div>

    <div class="form-wrap">
        <?php
        if(isset($resultsGroup) && is_array($resultsGroup) && count($resultsGroup) > 0) {
            echo '<table class="compiler-table">';
            echo '<thead>
            <tr>
            <td>'.esc_html__('Name', 'best-css-compiler' ).'</td>
            <td width="150">'.esc_html__('Hash', 'best-css-compiler' ).'</td>
            <td width="50">'.esc_html__('Output', 'best-css-compiler' ).'</td>
            <td width="50">'.esc_html__('Position', 'best-css-compiler' ).'</td>
            <td width="50">'.esc_html__('Type', 'best-css-compiler' ).'</td>
            <td width="20">'.esc_html__('Edit', 'best-css-compiler' ).'</td>
            <td width="20">'.esc_html__('Delete', 'best-css-compiler' ).'</td>
            </tr>
            </thead>';
            foreach ( $resultsGroup as $item ) {
        ?>
            <tr>
                <td>
                    <a href="admin.php?page=best-css-compiler&id=<?php echo esc_attr($item->compiler_id)?>&action=editor" class="button"><span class="dashicons dashicons-edit"></span> <strong><?php echo esc_attr($item->compiler_title); ?><?php echo (esc_attr($item->compiler_type) == 1) ? '.scss' : '.css'; ?></strong></a>
                </td>
                <td>
                    <?php echo esc_attr(hash('sha256',$item->compiler_content)); ?>
                </td>
                <td><a href="<?php echo content_url();?>/compiler/<?php echo esc_attr($item->compiler_title); ?>-<?php echo esc_attr($item->compiler_id)?>.css" target="_blank"><strong><?php echo esc_attr($item->compiler_title); ?>.css</strong></a></td>
                <td><strong><?php echo esc_attr($item->compiler_order); ?></strong></td>
                <td class="<?php echo (esc_attr($item->compiler_type) == 1) ? 'admin-lift-compiler-text-danger' : ''; ?>"><?php echo (esc_attr($item->compiler_type) == 1) ? 'SCSS' : 'CSS'; ?></td>
                <td class="text-center"><a href="admin.php?page=best-css-compiler&id=<?php echo esc_attr($item->compiler_id)?>&action=edit"><?php echo esc_html__('Edit', 'best-css-compiler' )?></a>
                </td>
                <td class="text-center"><a href="admin.php?page=best-css-compiler&id=<?php echo esc_attr($item->compiler_id)?>&action=delete" onclick="return confirm('<?php echo esc_html__('Are you sure?', 'best-css-compiler' )?>')"><?php echo esc_html__('Delete', 'best-css-compiler' )?></a>
                </td>
            </tr>
            <div class="clear"></div>
        <?php
            }
            echo '</table>';
        } else {
        ?>
            <div class="alert"><p><?php echo esc_html__('No data found.', 'best-css-compiler' ); ?></p></div>
        <?php } ?>

    </div>
</div>