<?php

//if (empty($data) || !is_array($data)) return;

//if (!empty($data['title'])) echo '<h3>'.$data['title'].'</h3>';
?>

<table class="data-table" cellspacing="1">
    <?php if (!empty($data['headers'])) { ?>
        <thead>
            <?php foreach($data['headers'] as $header) echo '<th>'.$header.'</th>'; ?>
        </thead>
    <?php } ?>
    <tbody>
    <?php
    foreach($data['rows'] as $key=>$row) {

        $id = md5($key);
        $id = $key;


	    if ( isset( $row['type'] ) && $row['type'] == 'title' ) { ?>
            <tr class="title">
                <td colspan="10"><?php echo $row['val']; ?></td>
            </tr>
	    <?php
	    } else {
            $row['id'] = 'row-'.$id;
	    ?>
            <tr<?php echo isset($row['class']) ? ' class="'.esc_attr($row['class']).'"':''; ?><?php echo isset($row['id']) ? ' id="'.esc_attr($row['id']).'"':''; ?>>
			    <?php foreach ( $row['cols'] as $col ) { ?>
                    <td
		               class="<?php echo ! empty( $col['class'] ) ? esc_attr( $col['class'] ) : ''; ?>"
		               style="<?php echo ! empty( $col['style'] ) ? esc_attr( $col['style'] ) : ''; ?>"
	                    <?php echo ! empty( $col['attrs'] ) ? implode(' ', $col['attrs']) : ''; ?>
                    >
                    <?php
                        // Value
                        if (is_array($col['val'])) {

                            echo '<span class="data-info" data-id="'.esc_attr($id).'">...Details...</span>';
                            echo '<div id="data-'.esc_attr($id).'" style="display:none"><pre>';
                            print_r($col['val']);
                            echo '</pre></div>';
                        } else {
                            echo wp_kses_post($col['val']);
                        }
                    ?>
                    </td>
			    <?php } ?>
            </tr>
	    <?php }
    }
    ?>
    </tbody>
</table>
