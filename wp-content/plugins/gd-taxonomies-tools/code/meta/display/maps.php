<?php

if (!defined('ABSPATH')) exit;

class gdCPT_GoogleMap {
    public $static = false;
    public $traffic = true;
    public $streetview = false;
    public $title = '';
    public $zoom = 10;
    public $maptype = 'ROADMAP';
    public $latitude = 40.7142691;
    public $longitude = -74.0059729;
    public $address = '';
    public $search = '';
    public $note = '';
    public $height = 250;
    public $width = 400;

    private $map_types = array(
        'TERRAIN' => 'terrain',
        'ROADMAP' => 'roadmap',
        'SATELLITE' => 'satellite',
        'HYBRID' => 'hybrid'
    );

    function __construct($value) {
        $this->_ready['bbpress'] = false;
        $this->_ready['column'] = false;
        $this->_ready['filter'] = false;

        if (is_null($value) || $value == '') {
            return;
        }

        $value = (array)$value;
        $this->from_array($value);
    }

    public function from_array($value) {
        if (isset($value['display'])) $this->display = $value['display'];
        if (isset($value['maptype'])) $this->maptype = strtoupper($value['maptype']);
        if (isset($value['address'])) $this->address = $value['address'];
        if (isset($value['title'])) $this->title = $value['title'];
        if (isset($value['search'])) $this->search = $value['search'];
        if (isset($value['note'])) $this->note = $value['note'];
        if (isset($value['zoom'])) $this->zoom = intval($value['zoom']);
        if (isset($value['height'])) $this->height = intval($value['height']);
        if (isset($value['width'])) $this->width = intval($value['width']);
        if (isset($value['latitude'])) $this->latitude = $value['latitude'];
        if (isset($value['longitude'])) $this->longitude = $value['longitude'];
        if (isset($value['static'])) $this->static = $value['static'] || $value['static'] == '1';
        if (isset($value['traffic'])) $this->traffic = $value['traffic'] || $value['traffic'] == '1';
        if (isset($value['streetview'])) $this->streetview = $value['streetview'] || $value['streetview'] == '1';
    }

    public function to_array() {
        return gdr2_object_to_array($this);
    }

    function get_map_type() {
        return in_array($this->maptype, array_keys($this->map_types)) ? $this->maptype : 'ROADMAP';
    }

    public function get_map_admin($id = '#gdtt_map') {
        return 'gdCPTMeta.gmap("'.$id.'", '.json_encode($this).')';
    }

    public function get_map($id = 'gmap') {
        if ($this->static) {
            return $this->get_map_static($id);
        } else {
            return $this->get_map_active($id);
        }
    }

    public function get_map_js($id = 'gmap') {
        if ($this->static) {
            return '';
        } else {
            return $this->get_map_active_js($id);
        }
    }

    public function get_map_active_js($id = '') {
        $js = 'jQuery("#'.$id.'").gmap3(';
        $js.= '{action:"init", options:{ mapTypeId: google.maps.MapTypeId.'.$this->get_map_type().', streetViewControl: '.($this->streetview ? 'true' : 'false').', zoom: '.($this->zoom).', center:['.($this->latitude).', '.($this->longitude).']}},';
        $js.= '{action:"addMarker", latLng:['.($this->latitude).', '.($this->longitude).']';
        if ($this->note != '') {
            $js.= ',infowindow:{options:{content: "'.$this->note.'"}},';
        }
        $js.= '});';

        return $js;
    }

    public function get_map_active($id = '') {
        return '<div style="width: '.$this->width.'px; height: '.$this->height.'px;" id="'.$id.'" class="gdtt-custom-field-gmap"></div>'.GDR2_EOL;
    }

    public function get_map_static($id = '') {
        $values = array(
            'sensor=false',
            'zoom='.$this->zoom,
            'size='.$this->width.'x'.$this->height,
            'center='.$this->latitude.','.$this->longitude,
            'maptype='.$this->map_types[$this->get_map_type()],
            'markers=color:blue|label:S|'.$this->latitude.','.$this->longitude
        );

        $protocol = is_ssl() ? 'https' : 'http';
        $url = $protocol.'://maps.google.com/maps/api/staticmap?'.join('&', $values);
        return sprintf('<div id="%s" class="gdtt-custom-field-gmap"><img alt="" src="%s" /></div>%s', $id, $url, GDR2_EOL);
    }
}

class gdCPT_Field_Display_GoogleMap extends gdCPT_Core_Field_Display {
    public $_name = 'google_map';

    public function get_attributes($field) {
        return array('width' => null, 'height' => null, 'streetview' => null, 'static' => false, 'zoom' => null, 'maptype' => null);
    }

    public function render($value, $field, $atts) {
        global $gdtt;

        $defaults = $this->get_attributes($field);
        $atts = shortcode_atts($defaults, $atts);

        add_filter('gdcpt_enqueue_google_maps', '__return_true');
        $settings = (array)$value;

        if (!is_null($atts['static'])) $settings['static'] = $atts['static'];
        if (!is_null($atts['streetview'])) $settings['streetview'] = $atts['streetview'];
        if (!is_null($atts['zoom'])) $settings['zoom'] = $atts['zoom'];
        if (!is_null($atts['width'])) $settings['width'] = $atts['width'];
        if (!is_null($atts['height'])) $settings['height'] = $atts['height'];
        if (!is_null($atts['maptype'])) $settings['maptype'] = $atts['maptype'];

        $map = new gdCPT_GoogleMap($settings);
        $jsc = $map->get_map_js($this->get_id($field));

        if ($jsc != '') {
            $gdtt->jquery_code[] = $jsc;
        }

        return $map->get_map($this->get_id($field));
    }
}

?>