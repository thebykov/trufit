	<script> //BYKOV
jQuery(document).ready(function(){
	jQuery('.tl_timerange').each(function(){	
		jQuery(this).text( 
			jQuery(this).text().replace(/:/g,"").replace(/(\d+)/g, function (match) {
			return getFormattedTime(match)
			})	 
		);	
		jQuery(this).html( jQuery(this).html().replace('-','<br>-') );	
	});
	
	jQuery('.event_timespan').each(function(){	
		jQuery(this).text( 
			jQuery(this).text().replace(/:/g,"").replace(/(\d+)/g, function (match) {
			return getFormattedTime(match)
			})	 
		);		
	});
	
	jQuery(".tl_row").each(function(){
		flag = true;
		jQuery(this).find("td").each(function(){
      		if(jQuery(this).text() != ""){flag = false;}
		})
		if(flag){jQuery(this).hide()}else{jQuery(this).show()}
	});
    
    var today = new Date();
    var dayNum = today.getDay();
    jQuery('.data_column.column_'+ dayNum).addClass('today');
});


function getFormattedTime(fourDigitTime) {
	var hours24 = parseInt(fourDigitTime.substring(0, 2),10);
	var hours = ((hours24 + 11) % 12) + 1;
	var amPm = hours24 > 11 ? 'PM' : 'AM';
	var minutes = fourDigitTime.substring(2);

	return hours + ':' + minutes + amPm;
};

function nextActive(dateNum){
    dateNum = jQuery('th.data_column.today').attr('data-value');
    jQuery('.data_column.column_'+ dateNum).removeClass('today');
    if(dateNum == 6){
        dateNum = 0;

    }else{
        dateNum++;
    }
    jQuery('.data_column.column_'+ dateNum).addClass('today');

}

function prevActive(){
    dateNum = jQuery('th.data_column.today').attr('data-value');
    jQuery('.data_column.column_'+ dateNum).removeClass('today');
    if(dateNum == 0){
        dateNum = 6;

    }else{
        dateNum--;
    }
    jQuery('.data_column.column_'+ dateNum).addClass('today');

}


</script>
<?php

    $weekdays = explode(',', $shortcode['weekdays']);

    $weekdays = array_map( 'trim', $weekdays );

	//$location_filter = $shortcode['filter'];

    $start_hour = intval($shortcode['start_hour']);

    $end_hour = intval($shortcode['end_hour']);

    $end_start = $end_hour - $start_hour;



    $step = 1;

    $divider = ' - ';

?>



<?php 

    $timeline_thead_group = '<col class="col-hours" />';

    $timeline_thead_rows = '<th></th>';

    $lastUpdated = "";  

    foreach ($weekdays as $key => $value){

        $timeline_thead_group .= '<col />';

        $timeline_thead_rows .= '<th class="data_column column_'. $key .' ' . $value . '" data-value="'.$key.'"><span>'.substr($value,0,3).'</span></th>';

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
		
		//$timeline_locations = array();



        foreach ($slide['categories'] as $key => $value){

            $categories[$key] = $value;

        }
		

            //$timeline_locations = get_the_terms( get_the_ID() ,'timeline_location');

        
		
            //$timeline_locations = $slide['timeline_locations'];


	
				
			

        if ( !empty($slide['options']['event']) ):

            foreach ($slide['options']['event'] as $key => $option) {
				
				if(get_field('location_filter') == $option['location']){
					
                $events[$i] = 

                    array(

                        'categories' => $categories,

                        'title' =>  $option['title'],

                        'has_event' => $option['checked'],

                        'start_hh' => $option['start_hour'],

                        'start_mm' => $option['start_min'],

                        'end_hh' => $option['end_hour'],

                        'end_mm' => $option['end_min'],

                        'days' => $option['days'],
						
						'id' => $slide['post']->ID

                    );
				
						$date2 = date_create_from_format('Y-m-d H:i:s', $slide['post']->post_modified);
                         
						//print_r("Post Date" . $slide['post']->post_date . "<br>");
						//print_r("date2:" . date_format($date2, 'F jS Y -  h:i:s') . "<br>");
						
						//Post Date2016-02-14 02:15:11
						//date2:2017-03-14 02:03:11
						
						if($lastUpdated == "" || ($lastUpdated < $date2)){
							$lastUpdated = $date2; 
                            //print_r("lastUpdated:" . date_format($lastUpdated, 'F jS Y -  h:i:s') . "<br>");
						}
					
					//print_r($slide['post']->ID);
					//	print_r("<br>");
			}
			
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



                    $days .= '<tr class="tl_row tl_row_' . $start_hour . '"><th><span class="tl_timerange">'. $start_hour.' - '.$start_hour_step.'</span></th>';//BYKOV

                }



                if (($i % 7 == 0) && ($i > 0))  {

                    $weekday_index = 0;

                    $start_hour += $step;

                    $start_hour_step = $start_hour + $step;

                    $start_hour_step = $start_hour_step < 10 ? '0'.$start_hour_step.$prefix : $start_hour_step.$prefix;                    

                    $start_hour = $start_hour < 10 ? '0'.$start_hour.$prefix : $start_hour.$prefix;



                    $days .= '</tr><tr class="tl_row tl_row_' . $start_hour . '"><th><span class="tl_timerange">'.$start_hour.' - '.$start_hour_step.'</span></th>';//BYKOV



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
							
							$instructorInfo = "";
							
							if($events[$key]['title'] != ""){
								$instructorInfo = '<span class="instructor"><i> Trainer: ' . $events[$key]['title'] . '</i></span>';
							}

 if( current_user_can('editor') || current_user_can('administrator')){
 $timeline_event .= '<a target="_blank" href="/wp-admin/post.php?post='.$events[$key]['id'] .'&action=edit" title="'.$cat_title.'">'.$cat_title.'</a><span class="event_timespan">'.$events[$key]['start_hh'].':'.$events[$key]['start_mm'].' - '.$events[$key]['end_hh'].':'.$events[$key]['end_mm'].'</span><br />' . $instructorInfo;
 
 }
 else{
 
 $timeline_event .= '<a href="#" title="'.$cat_title.'">'.$cat_title.'</a><span class="event_timespan">'.$events[$key]['start_hh'].':'.$events[$key]['start_mm'].' - '.$events[$key]['end_hh'].':'.$events[$key]['end_mm'].'</span><br />' . $instructorInfo;
 }
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

                        $days .= '<td style="" data-index="'.$i.'" class="'.$has_event.' data_column column_' . ($i % 7) . '">'.$timeline_event.'</td>';

                    }

                }

            }

            return $days; 

        }     

    }







    $filters = draw_filter_box( $all_categories );

    $tbody = draw_timnline_days( $end_start, $weekdays, $start_hour, $step, $events, $mycount, $end_hour );



    echo $filters;
	

    echo '<div class="prevbtn-wrapper"><div class="prevbtn" onclick="prevActive();">&lt; Prev Day</div></div><div class="nextbtn-wrapper"><div class="nextbtn" onclick="nextActive();">Next Day &gt;</div></div>';

    echo '<div class="level level1">Level 0</div><div class="level level2">Level 2</div><div class="level level3">Level 3</div>';

    echo '';

    echo $thead.$tbody.'</table>';

    echo '<div class="last_updated" style="color: #fff;text-align: center;margin: 20px 0 0;">Last Updated: ' . date_format($lastUpdated, 'F jS Y -  h:i:s') . '</div>';

?>

