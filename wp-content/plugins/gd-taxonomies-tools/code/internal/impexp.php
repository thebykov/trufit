<?php

if (!defined('ABSPATH')) exit;

global $gdtt_export_var;
$gdtt_export_var = '';

function gdtt_export_settings($cpt, $tax, $ovr, $met, $set, $cap, $cpt_list = array(), $tax_list = array()) {
    global $gdtt_export_var, $gdtt;
    $data = new gdrClass(array('obj' => null, 'ovr' => null, 'met' => null, 'set' => null, 'caps' => null));

    if ($cpt == 1) {
        $data->obj = new stdClass();
        $data->obj->cpt = array();

        foreach ($gdtt->p as $cpt) {
            if (in_array($cpt["id"], $cpt_list)) {
                $data->obj->cpt[] = gdr2_clone($cpt);
            }
        }
    }

    if ($tax == 1) {
        if (is_null($data->obj)) {
            $data->obj = new stdClass();
        }

        $data->obj->tax = array();

        foreach ($gdtt->t as $tax) {
            if (in_array($tax["id"], $tax_list)) {
                $data->obj->tax[] = gdr2_clone($tax);
            }
        }
    }

    if ($ovr == 1) {
        $data->ovr = new stdClass();

        $data->ovr->cpt = $gdtt->nn_p;
        $data->ovr->tax = $gdtt->nn_t;
    }

    if ($met == 1) {
        $data->met = $gdtt->m;
    }

    if ($set == 1) {
        $data->set = $gdtt->o;
    }

    if ($cap == 1) {
        $caps = get_option('gd-taxonomy-tools-caps');
        $data->caps = $caps;
    }

    $gdtt_export_var = serialize($data);
}

function gdtt_import_settings($data, $settings) {
    global $gdtt;

    $data = maybe_unserialize($data);

    if (!is_object($data)) {
        return 'failed';
    }

    if (!is_null($data->obj) && is_object($data->obj)) {
        if (isset($data->obj->tax) && is_array($data->obj->tax)) {
            if ($settings['tax'] == 'replace') {
                $gdtt->t = $data->obj->tax;

                update_option('gd-taxonomy-tools-tax', $gdtt->t);
            } else if ($settings['tax'] == 'append') {        
                $names = array();
                foreach ($gdtt->t as $id => $tax) {
                    $names[$tax['name']] = $id;
                }

                foreach ($data->obj->tax as $tax) {
                    if (isset($names[$tax['name']])) {
                        $gdtt->t[$names[$tax['name']]] = $tax;
                    } else {
                        $gdtt->o['tax_internal'] = (int)$gdtt->o['tax_internal'] + 1;
                        $tax['id'] = $gdtt->o['tax_internal'];
                        $gdtt->t[] = $tax;
                    }
                }

                update_option('gd-taxonomy-tools', $gdtt->o);
                update_option('gd-taxonomy-tools-tax', $gdtt->t);
            }
        }

        if (isset($data->obj->cpt) && is_array($data->obj->cpt)) {
            if ($settings['tax'] == 'replace') {
                $gdtt->p = $data->obj->cpt;

                update_option('gd-taxonomy-tools-cpt', $gdtt->p);
            } else if ($settings['tax'] == 'append') {        
                $names = array();
                foreach ($gdtt->p as $id => $cpt) {
                    $names[$cpt['name']] = $id;
                }

                foreach ($data->obj->cpt as $cpt) {
                    if (isset($names[$cpt['name']])) {
                        $gdtt->p[$names[$cpt['name']]] = $cpt;
                    } else {
                        $gdtt->o['cpt_internal'] = (int)$gdtt->o['cpt_internal'] + 1;
                        $cpt['id'] = $gdtt->o['cpt_internal'];
                        $gdtt->p[] = $cpt;
                    }
                }

                update_option('gd-taxonomy-tools', $gdtt->o);
                update_option('gd-taxonomy-tools-cpt', $gdtt->p);
            }
        }
    }

    if ($settings['ovr'] == 'replace' && !is_null($data->ovr) && is_object($data->ovr)) {
        $gdtt->nn_p = $data->ovr->cpt;
        $gdtt->nn_t = $data->ovr->tax;

        update_option('gd-taxonomy-tools-nn-cpt', $gdtt->nn_p);
        update_option('gd-taxonomy-tools-nn-tax', $gdtt->nn_t);
    }

    if ($settings['cap'] == 'replace' && !is_null($data->caps) && is_array($data->caps)) {
        $caps = $data->caps;

        update_option('gd-taxonomy-tools-caps', $caps);
    }

    if (!is_null($data->met) && is_array($data->met)) {
        if ($settings['met'] == 'replace') {
            $gdtt->m = $data->met;

            update_option('gd-taxonomy-tools-meta', $gdtt->m);
        } else if ($settings['met'] == 'append') {
            foreach ($data->met['boxes'] as $key => $val) {
                $gdtt->m['boxes'][$key] = gdr2_clone($val);
            }

            foreach ($data->met['fields'] as $key => $val) {
                $gdtt->m['fields'][$key] = gdr2_clone($val);
            }

            foreach ($data->met['map'] as $key => $val) {
                if (isset($gdtt->m['map'][$key])) {
                    $gdtt->m['map'][$key] = array_unique(array_merge($gdtt->m['map'][$key], $val));
                } else {
                    $gdtt->m['map'][$key] = $val;
                }
            }

            update_option("gd-taxonomy-tools-meta", $gdtt->m);
        }
    }

    if ($settings["set"] == "replace" && !is_null($data->set) && is_array($data->set)) {
        $gdtt->o = $data->set;
    }

    $gdtt->reindex_and_save();

    return "transfered";
}

function gdtt_export_terms($tax, $hierarchy = 1, $parent = 0, $level = " ") {
    global $gdtt_export_var;
    if (!is_taxonomy_hierarchical($tax)) $hierarchy = 0;

    if ($hierarchy != 1) {
        $total_terms = get_terms($tax, array('hide_empty' => false, 'hierarchical' => false, 'fields' => 'count'));
        $total_terms = intval($total_terms);
        $parts = floor($total_terms / 10000);
        if ($parts * 10000 < $total_terms) $parts++;

        for ($i = 0; $i < $parts; $i++) {
            $terms = get_terms($tax, array('hide_empty' => false, 'hierarchical' => false, 'offset' => 10000 * $i, 'number' => 10000));
            foreach ($terms as $term) {
                $gdtt_export_var.= $term->name.GDR2_EOL;
            }
        }
    } else {
        $terms = get_terms($tax, array('hide_empty' => false, 'hierarchical' => true, 'parent' => $parent));
        foreach ($terms as $term) {
            $gdtt_export_var.= trim($level.$term->name).GDR2_EOL;
            gdtt_export_terms($tax, $hierarchy, $term->term_id, "*".$level);
        }
    }
}

function gdtt_import_terms($taxonomy, $terms = array(), $hierarchy = false) {
    if (!$hierarchy) {
        $count = 0;
        foreach ($terms as $term) {
            $term = trim($term, " *\n\r\t\0");
            gdtt_insert_term($term, $taxonomy);
            $count++;
        }
        return $count;
    } else {
        $level = array();
        $prev = 0;
        $count = 0;
        foreach ($terms as $term) {
            $star = true;
            $stars = 0;
            while ($star) {
                if (substr($term, $stars, 1) == '*') $stars++;
                else $star = false;
            }
            $term = trim($term, " *\n\r\t\0");
            if ($stars == 0) {
                $t = gdtt_insert_term($term, $taxonomy);
                if (is_wp_error($t)) return $t;
            } else {
                if ($stars > $prev + 1) {
                    $stars = $prev + 1;
                }
                $t = gdtt_insert_term($term, $taxonomy, $level[$stars - 1]);
                if (is_wp_error($t)) return $t;
            }
            $level[$stars] = $t;
            $count++;
            $prev = $stars;
        }
        return $count;
    }
}

function gdtt_insert_term($term, $taxonomy, $parent = 0) {
    $t = wp_insert_term($term, $taxonomy, array('parent' => $parent));

    if (is_wp_error($t)) {
        if (isset($t->errors['term_exists'])) {
            return $t->error_data["term_exist's"];
        } else {
            return $t;
        }
    } else {
        return $t['term_id'];
    }
}

?>