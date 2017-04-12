<?php
    $weekdays = explode(',', $shortcode['weekdays']);
    $weekdays = array_map( 'trim', $weekdays );

    $start_hour = intval($shortcode['start_hour']);
    $end_hour = intval($shortcode['end_hour']);
    $end_start = $end_hour - $start_hour;

    $step = 1;
    $divider = ' - ';
?>

<?php 
    $timeline_thead_group = '<col class="col-hours" />';
    $timeline_thead_rows = '<th></th>';

    foreach ($weekdays as $key => $value){
        $timeline_thead_group .= '<col />';
        $timeline_thead_rows .= '<th><span>'.$value.'</span></th>';
    }

    $thead_group = '<colgroup>'.$timeline_thead_group.'</colgroup>';
    $thead_rows =  '<table class="timetable top-border margin-top">'.$thead_group.'
                        <thead>
                            <tr>'.$timeline_thead_rows.'</tr>
                        </thead>';

    $thead = $thead_rows;
    $tbody = '';
?>



<?php 

    if (!function_exists('draw_filter_box')) {
        function draw_filter_box($categories){
            $filters = '';

            foreach( $categories as $category_slug => $category_name ){
                $filters .= '<li>
                                <a href="#" data-cat-slug="'.$category_slug.'">'.$category_name.'</a>
                            </li>';
            }

            $filters =  '<div class="filter-box">
                            <div class="center-me">
                                <ul class="filter filter-all clean-list inline-list filter-tags">
                                    <li>
                                        <a href="#" class="active" data-cat-slug="all">All</a>
                                    </li>'.$filters.'</ul>
                            </div>
                        </div>';

            return $filters;
        }
    }

    $events = array();

    foreach( $slides as $i => $slide ):

        $categories = array();
        $categories_title = array();

        foreach ($slide['categories'] as $key => $value){
            $categories[$key] = $value;
        }

        if ( !empty($slide['options']['event']) ):
            foreach ($slide['options']['event'] as $key => $option) {
                $events[$i] = 
                    array(
                        'categories' => $categories,
                        'title' =>  $option['title'],
                        'has_event' => $option['checked'],
                        'start_hh' => $option['start_hour'],
                        'start_mm' => $option['start_min'],
                        'end_hh' => $option['end_hour'],
                        'end_mm' => $option['end_min'],
                        'days' => $option['days']
                    );
            }
        endif;
    endforeach; 

    $mycount = 0;   

    if (!function_exists('check_weekdays')) {
        function check_weekdays(){

        }
    }

    if (!function_exists('draw_timnline_days')) {

        function draw_timnline_days( $end_start, $weekdays, $start_hour, $step, $events, $mycount, $end_hour ){
            $days = '';  $weekday_index = -1; $prefix = ':00'; $position = array();

            $prev_row = null; $prev_rows = null; $row_desc = 0;
           
            for ($i = 0; $i < $end_start*count($weekdays); $i+=$step){
                
                $weekday_index++;

                if ($i == 0) {
                    $start_hour = $start_hour < 10 ? '0'.$start_hour.$prefix : $start_hour.$prefix;
                    $start_hour_step = $start_hour + $step;
                    $start_hour_step = $start_hour_step < 10 ? '0'.$start_hour_step.$prefix : $start_hour_step.$prefix;

                    $days .= '<tr><th><span>'. $start_hour.' - '.$start_hour_step.'</span></th>';
                }

                if (($i % 7 == 0) && ($i > 0))  {
                    $weekday_index = 0;
                    $start_hour += $step;
                    $start_hour_step = $start_hour + $step;
                    $start_hour_step = $start_hour_step < 10 ? '0'.$start_hour_step.$prefix : $start_hour_step.$prefix;                    
                    $start_hour = $start_hour < 10 ? '0'.$start_hour.$prefix : $start_hour.$prefix;

                    $days .= '</tr><tr><th><span>'.$start_hour.' - '.$start_hour_step.'</span></th>';

                }
                
                
                $timeline_event = ''; $has_event = ''; $rowspan = 1; $is_unique = 1; $day = 'WEEKDAY START';
                foreach ($events as $key => $event){
                    if( intval($start_hour) === intval($events[$key]['start_hh']) && $events[$key]['days'] === $weekdays[$weekday_index]){
              
                        /* check */
                      
                        $evt_end_hour = intval($events[$key]['end_hh']);
                        $evt_end_min = intval($events[$key]['end_mm']);
                        $time_end_hour = intval($start_hour + $step);
                        
                        // $day !== $events[$key]['days'] to avoid extra rowspan++ if goes two and more events in one day

                        if ( ( ($evt_end_hour >= $time_end_hour) || (($evt_end_hour >= $time_end_hour) && $evt_end_min) ) && ($day !== $events[$key]['days']) && ( $evt_end_hour > 0 && $evt_end_hour < $end_hour)){
                            
                            if ( $evt_end_hour === $time_end_hour ) $rowspan--;
                            do {
                                $rowspan++;
                                $time_end_hour++;
                            } while ($evt_end_hour > $time_end_hour);
                            
                            if($evt_end_min) $rowspan++;
                            
                           
                            if ( $rowspan > 1) {
                                $event_rows = ceil($i/7) + $rowspan;
                                $rows_counter = ceil($i/7);

                                $curr_row = null;
                                
                                

                                    if( !$prev_row ){
                                        $prev_row = ceil($i/7);
                                        $prev_rows = $rowspan;
                                    } else  {

                                        $curr_row = ceil($i/7);

                                        if (($prev_rows + $prev_row) > $curr_row && $prev_row !== $curr_row){
                                            $row_desc =  $curr_row - ($prev_rows + $prev_row);
                                        }


                                    }



                                    if ($curr_row) {
                                        $prev_row = $curr_row;
                                        $prev_rows= $rowspan;   
                                    }
                            }

                        }

                         $day = $events[$key]['days']; 
                       
                        /* ^ check */

                        foreach ($events[$key]['categories'] as $cat_slug => $cat_title) {
                            if ( !empty($events[$key]['has_event']) ){
                                if ( !$is_unique ){
                                    $has_event .= $cat_slug.' ';
                                } else {
                                    $has_event .= $cat_slug.' event '; $is_unique = false;
                                }
                                
                            } else{
                                $has_event .= $cat_slug.' ';
                            }

                            //$has_event .= !empty($events[$key]['has_event']) ? ($is_unique ? $cat_slug : $cat_slug.' event '; $is_unique = true; ) : ( $is_unique ? $cat_slug : $cat_slug.' ' ); 
                            $timeline_event .= '<a href="#'.$cat_slug.'" title="'.$cat_title.'">'.$cat_title.'</a>'.$events[$key]['start_hh'].':'.$events[$key]['start_mm'].' - '.$events[$key]['end_hh'].':'.$events[$key]['end_mm'].'<br />';
                        }
                    }
                }

                if ($rowspan > 1){
                    $days .= '<td data-index="'.$i.'" class="'.$has_event.'" rowspan="'.$rowspan.'">'.$timeline_event.'</td>';
                    $pos = $i;

                    do {
                        $position[] = $pos+=7;
                        $rowspan--; // do not delete this line -> cause to out of memory
                    } while ($rowspan > 1);
                } else {
                    if ( !in_array($i, $position) ){
                        $days .= '<td style="" data-index="'.$i.'" class="'.$has_event.'">'.$timeline_event.'</td>';
                    }
                }
            }
            return $days; 
        }     
    }



    $filters = draw_filter_box( $all_categories );
    $tbody = draw_timnline_days( $end_start, $weekdays, $start_hour, $step, $events, $mycount, $end_hour );

    echo $filters;
    echo $thead.$tbody.'</table>';
?>