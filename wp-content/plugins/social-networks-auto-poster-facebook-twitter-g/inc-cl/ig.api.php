<?php
//## NextScripts Instagram Connection Class
$nxs_snapAPINts[] = array('code'=>'IG', 'lcode'=>'ig', 'name'=>'Instagram');

if (!class_exists("nxs_class_SNAP_IG")) { class nxs_class_SNAP_IG {
    
    var $ntCode = 'IG';
    var $ntLCode = 'ig';     
    
    function doPost($options, $message){ if (!is_array($options)) return false; $out = array();
      foreach ($options as $ii=>$ntOpts) $out[$ii] = $this->doPostToNT($ntOpts, $message);
      return $out;
    }
    function doPostToNT($options, $message){ $badOut = array('pgID'=>'', 'isPosted'=>0, 'pDate'=>date('Y-m-d H:i:s'), 'Error'=>''); if (!class_exists("nxsAPI_IG")){ $badOut['Error'] .= "Instagram API not found"; return $badOut; } 
      //## Check settings
      if (!is_array($options)) { $badOut['Error'] = 'No Options'; return $badOut; } if (empty($options['uPass'])) { $badOut['Error'] = 'Not Configured'; return $badOut; }
      //## Format
      if (!empty($message['pText'])) $msg = $message['pText']; else $msg = nxs_doFormatMsg($options['msgFormat'], $message);     
      if (isset($message['imageURL'])) $imgURL = trim(nxs_getImgfrOpt($message['imageURL'], $options['imgSize'])); else $imgURL = ''; 
      $urlToGo = (!empty($message['url']))?$message['url']:'';  if (empty($options['imgAct'])) $options['imgAct'] = 'E';
      
      $msg = nsTrnc(html_entity_decode($msg) , 2200); $pass = substr($options['uPass'], 0, 5)=='g9c1a'?nsx_doDecode(substr($options['uPass'], 5)):$options['uPass'];
      
      $nt = new nxsAPI_IG(); $nt->debug = false; if (!empty($ck)) $nt->ck = $ck;  if (!empty($options['proxy'])&&!empty($options['proxyOn'])){ $nt->proxy['proxy'] = $options['proxy']['proxy']; if (!empty($options['proxy']['up'])) $nt->proxy['up'] = $options['proxy']['up'];};      
      $loginErr = $nt->connect($options['uName'], $pass);      
      if (!$loginErr) $ret = $nt->post($msg, $imgURL, $options['imgAct']); else { $badOut['Error'] .= 'Something went wrong - '.print_r($loginErr, true); $ret = $badOut; }      
      return $ret;
   }    
}}

?>