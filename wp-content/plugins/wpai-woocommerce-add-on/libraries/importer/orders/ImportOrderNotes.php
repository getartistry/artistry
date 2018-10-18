<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportOrderBase.php';

/**
 *
 * Import Order Notes
 *
 * Class ImportOrderNotes
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderNotes extends ImportOrderBase {

    /**
     * @throws \Exception
     */
    public function import() {
        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_notes']) {
            $this->_import_order_notes();
        }
    }

    /**
     * @throws \Exception
     */
    protected function _import_order_notes() {
        $notes = $this->getValue('notes');
        if (!empty($notes)) {
            $notes_count = 0;
            foreach ($notes as $noteIndex => $note) {
                if (empty($note['content'])) {
                    continue;
                }

                $note_item = new \PMXI_Post_Record();
                $note_item->getBy(array(
                    'import_id' => $this->getImport()->id,
                    'post_id' => $this->getOrderID(),
                    'unique_key' => 'note-item-' . $this->getOrderID() . '-' . $noteIndex
                ));

                if (!$note_item->isEmpty()) {
                    $note_id = str_replace('note-item-', '', $note_item->product_key);
                    $is_note_exist = get_comment($note_id);
                    if (empty($is_note_exist) || $is_note_exist->comment_approved == 'trash') {
                        $note_item->delete();
                        $note_item->clear();
                        if (!empty($is_note_exist) && $is_note_exist->comment_approved == 'trash') {
                            wp_delete_comment($note_id, TRUE);
                        }
                    }
                }

                $comment_author = empty($note['username']) ? 'WP All Import' : $note['username'];
                $comment_author_email = $note['email'];

                if (empty($comment_author) && empty($comment_author_email)) {
                    if (is_user_logged_in()) {
                        $user = get_user_by('id', get_current_user_id());
                        $comment_author_email = $user->user_email;
                    }
                    else {
                        $comment_author_email = strtolower(__('WooCommerce', \PMWI_Plugin::TEXT_DOMAIN)) . '@';
                        $comment_author_email .= isset($_SERVER['HTTP_HOST']) ? str_replace('www.', '', $_SERVER['HTTP_HOST']) : 'noreply.com';
                        $comment_author_email = sanitize_email($comment_author_email);
                    }
                }

                $comment_post_ID = $this->getOrderID();
                $comment_author_url = '';
                $comment_content = $note['content'];
                $comment_agent = 'WooCommerce';
                $comment_type = 'order_note';
                $comment_parent = 0;
                $comment_approved = 1;
                $comment_date = $note['date'];
                $is_customer_note = $note['visibility'] == "private" ? 0 : 1;
                $commentdata = apply_filters('woocommerce_new_order_note_data', compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved', 'comment_date'), array(
                    'order_id' => $this->getOrderID(),
                    'is_customer_note' => $is_customer_note
                ));

                if ($note_item->isEmpty()) {
                    $comment_id = FALSE;

                    if (!$this->isNewOrder()) {
                        $current_notes = $this->get_order_notes();

                        if (!empty($current_notes)) {
                            foreach ($current_notes as $current_note) {
                                if ($current_note->comment_content == $commentdata['comment_content']) {
                                    $comment_id = $current_note->comment_ID;
                                    break;
                                }
                            }
                        }

                    }

                    if (!$comment_id) {
                        $comment_id = wp_insert_comment($commentdata);

                        if ($note['visibility'] != 'private') {
                            add_comment_meta($comment_id, 'is_customer_note', 1);
                            // send customer note notification
                            if (empty($this->getImport()->options['do_not_send_order_notifications'])) {
                                do_action('woocommerce_new_customer_note', array(
                                    'order_id' => $this->getOrderID(),
                                    'customer_note' => $commentdata['comment_content']
                                ));
                            }
                        }
                    }

                    $note_item->set(array(
                        'import_id' => $this->getImport()->id,
                        'post_id' => $this->getOrderID(),
                        'unique_key' => 'note-item-' . $this->getOrderID() . '-' . $noteIndex,
                        'product_key' => 'note-item-' . $comment_id,
                        'iteration' => $this->getImport()->iteration
                    ))->save();
                }
                else {
                    $commentdata['comment_ID'] = str_replace('note-item-', '', $note_item->product_key);

                    wp_update_comment($commentdata);

                    if ($note['visibility'] != 'private') {
                        update_comment_meta($commentdata['comment_ID'], 'is_customer_note', 1);
                    }
                    else {
                        delete_comment_meta($commentdata['comment_ID'], 'is_customer_note');
                    }

                    $note_item->set(array(
                        'iteration' => $this->getImport()->iteration
                    ))->save();
                }
                $notes_count++;
            }

            global $wpdb;

            $wpdb->update($wpdb->posts, array('comment_count' => $notes_count), array('ID' => $this->getOrderID()), array('%d'), array('%d'));
        }
    }

    /**
     * @return array
     */
    protected function get_order_notes() {

        $notes = array();
        $args = array(
            'post_id' => $this->getOrderID(),
            'approve' => 'approve',
            'type' => ''
        );

        remove_filter('comments_clauses', array(
            'WC_Comments',
            'exclude_order_comments'
        ));

        $comments = get_comments($args);

        foreach ($comments as $comment) {
            if ($comment->comment_approved != 'trash') {
                $notes[] = $comment;
            }
        }

        add_filter('comments_clauses', array(
            'WC_Comments',
            'exclude_order_comments'
        ));

        return $notes;
    }
}