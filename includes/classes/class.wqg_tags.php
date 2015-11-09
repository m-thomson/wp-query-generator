<?php

    /**
     * Created by PhpStorm.
     * User: anjan
     * Date: 9/24/15
     * Time: 6:15 PM
     */
    class wqg_tags {

        /**
         * get all tags

         *
         * @return array
         */

        public static function get_tags() {



            $taxonomy = 'post_tag';

            $args = array(
                'orderby'                  => 'name',
                'order'                    => 'ASC',
                'hide_empty'               => 0,
                'hierarchical'             => 0,
                'taxonomy'                 => $taxonomy,
            );

            $tags = get_categories($args);

            return $tags;

        }

        /**
         * Generates a categories dropdown
         *
         * @param array $params
         *
         * @return string
         */

        public static function tags_dropdown($params = array()) {

            $attributes = wqg_utils::__ARRAY_VALUE($params,'attributes',array());

            $selected = wqg_utils::__ARRAY_VALUE($params,'selected','');



            $html = "<select";

            if(is_array($attributes) && count($attributes) > 0) {

                foreach($attributes as $key => $value) {

                    $html .= " {$key}='".(string)$value."'";

                }

            }

            $html .= '>';

            $empty_value = wqg_utils::__ARRAY_VALUE($params,'empty_value',false);

            if(is_array($empty_value) && isset($empty_value['label']) && isset($empty_value['value'])) {

                $html .= "<option value='{$empty_value['value']}'>{$empty_value['label']}</option>";

            }

            $tags = self::get_tags(0);

            if(is_array($tags) && count($tags) > 0) {

                foreach($tags as $t) {

                    $html .= self::generate_tag_option(array(
                        'tag' => $t,
                        'selected' => $selected,
                        'label_field' => wqg_utils::__ARRAY_VALUE($params,'label_field','name'),
                        'value_field' => wqg_utils::__ARRAY_VALUE($params,'value_field','term_id'),
                        'indent' => 0
                    ));

                }

            }

            $html .= '</select>';

            return $html;

        }

        /**
         * Generates <option> tag for a tag
         *
         * @param array $params
         *
         * @return bool|string
         */

        public static function generate_tag_option( $params = array()) {

            $tag = wqg_utils::__ARRAY_VALUE($params,'tag',false);

            if(!is_object($tag)) {
                return false;
            }



            $label_field = wqg_utils::__ARRAY_VALUE($params,'label_field','name');
            $value_field = wqg_utils::__ARRAY_VALUE($params,'value_field','term_id');

            $indent = (int)wqg_utils::__ARRAY_VALUE($params,'indent',0);

            $label = isset($tag->$label_field) ? $tag->$label_field : '';
            $value = isset($tag->$value_field) ? $tag->$value_field : '';

            $label = str_repeat('-',$indent).$label;

            /* Selected attr */

            $selected_attr = '';

            $selected = wqg_utils::__ARRAY_VALUE($params,'selected','');

            if(is_array($selected) && in_array($value,$selected)) {
                $selected_attr = ' selected';
            } else if($selected == $value) {
                $selected_attr = ' selected';
            }

            $html = "<option value='{$value}'{$selected_attr}>{$label}</option>";

            return $html;

        }

    }