<?php

$comments_arg = array(
    // 'form' => array(), 
    'fields' => array(
        'author'    => '<div class="form-group">'.
                            '<label>' . __('Name') . '</label>' .
                            '<input id="author" name="author" class="form-control" />' .
                        '</div>',
        // 'email'     => '<div class="form-group">'.
        //                     '<label for="email">' . __( 'Email' ) . '</label> ' .
        //                     '<input type="email" id="email" name="email" class="form-control" type="text" size="30" />
        //                 </div>',
        // 'url'       => '<div class="form-group">'.
        //                     '<label for="url">' . __( 'URL' ) . '</label> ' .
        //                     '<input type="url" id="url" name="url" class="form-control" type="text" size="30" />
        //                 </div>'
    ),
    'comment_field' => '<div class="form-group">' . 
                            '<label for="comment">' . __( 'Comment' ) . '</label><span>*</span>' .
                            '<textarea id="comment" class="form-control" name="comment" rows="3" aria-required="true"></textarea>' . 
                        '</div>',
    'submit_button' => '<input class="btn btn-primary" type="submit" value="Submit" >'
);

comment_form($comments_arg);