<?php
    function lang($word){
         $lang = array(
            'Admin'=> 'HOME',
            'CAT'  => 'CATEGORIES',
            'I'    => 'ITEMS',
            'M'    =>' MEMBERS',
            'C'    =>' COMMENTS',
            'S'    => 'STATISTICS',
            'L'    => 'LOGS'
        );
        return $lang[$word];
    }

?>